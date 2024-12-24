<?php

namespace app\models;

use app\helpers\YandexMapHelper;
use frexin\logic\actions\AbstractAction;
use frexin\logic\actions\CancelAction;
use frexin\logic\AvailableActions;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string $description
 * @property string|null $location
 * @property int|null $budget
 * @property string|null $expire_dt
 * @property string|null $dt_add
 * @property int $client_id
 * @property int|null $performer_id
 * @property int $status_id
 * @property int $city_id
 * @property float $lat
 * @property float $long
 *
 * @property File[] $files
 * @property Reply[] $replies
 * @property Opinion[] $opinions
 * @property Category $category
 * @property Status $status
 * @property User $performer
 * @property City $city
 */
class Task extends ActiveRecord
{
    public $noResponses;
    public $noLocation;
    public $filterPeriod;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'client_id',
                'updatedByAttribute' => null
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id'], 'default', 'value' => function($model, $attr) {
                return Status::find()->select('id')->where('id=1')->scalar();
            }],
            [['city_id'], 'default', 'value' => function($model, $attr) {
                if ($model->location) {
                    return \Yii::$app->user->getIdentity()->city_id;
                }

                return null;
            }],
            [['noResponses', 'noLocation'], 'boolean'],
            [['filterPeriod'], 'number'],
            [['category_id', 'budget', 'performer_id', 'status_id', 'city_id'], 'integer'],
            [['budget'], 'integer', 'min' => 1],
            [['description'], 'string'],
            [['expire_dt'], 'date', 'format' => 'php:Y-m-d', 'min' => date('Y-m-d'), 'minString' => 'чем текущий день'],
            [['name', 'location'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
            [['name', 'category_id', 'description', 'status_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'category_id' => 'Категория',
            'description' => 'Описание',
            'location' => 'Место',
            'budget' => 'Бюджет',
            'expire_dt' => 'Крайний срок',
            'dt_add' => 'Дата создания',
            'client_id' => 'Заказчик',
            'city_id' => 'Город',
            'performer_id' => 'Исполнитель',
            'status_id' => 'Статус',
            'noLocation' => 'Удаленная работа',
            'noResponses' => 'Без откликов'
        ];
    }

    public function getSearchQuery()
    {
        $query = self::find();
        $query->where(['status_id' => Status::STATUS_NEW]);

        $query->andFilterWhere(['category_id' => $this->category_id]);

        if ($this->noLocation) {
            $query->andWhere('location IS NULL');
        }

        if ($this->noResponses) {
            $query->joinWith('replies r')->andWhere('r.id IS NULL');
        }

        if ($this->filterPeriod) {
            $query->andWhere('UNIX_TIMESTAMP(tasks.dt_add) > UNIX_TIMESTAMP() - :period', [':period' => $this->filterPeriod]);
        }

        return $query->orderBy('dt_add DESC');
    }

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_uid' => 'uid']);
    }

    /**
     * Gets query for [[Opinion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinion::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reply]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies(IdentityInterface $user = null)
    {
         $allRepliesQuery = $this->hasMany(Reply::class, ['task_id' => 'id']);

        if ($user && $user->getId() !== $this->client_id) {
            $allRepliesQuery->where(['replies.user_id' => $user->getId()]);
        }

        return $allRepliesQuery;
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getPerformer()
    {
        return $this->hasOne(User::class, ['id' => 'performer_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    public function goToNextStatus(AbstractAction $action)
    {
        $actionManager = new AvailableActions($this->status->slug, $this->performer_id, $this->client_id);
        $nextStatusName = $actionManager->getNextStatus($action);

        $status = Status::findOne(['slug' => $nextStatusName]);
        $this->link('status', $status);
        $this->save();
    }

    public function beforeSave($insert)
    {
        if ($this->location) {
            $yandexHelper = new YandexMapHelper(getenv('YANDEX_API_KEY'));
            $coords = $yandexHelper->getCoordinates($this->city->name, $this->location);

            if ($coords) {
                [$lat, $long] = $coords;

                $this->lat = $lat;
                $this->long = $long;
            }
        }

        parent::beforeSave($insert);

        return true;
    }


}