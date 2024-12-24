<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "replies".
 *
 * @property int $id
 * @property int $user_id
 * @property string $dt_add
 * @property string $description
 * @property int $budget
 * @property int $task_id
 * @property int|null $is_approved
 * @property int|null $is_denied
 *
 * @property Task $task
 * @property User $user
 */
class Reply extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'replies';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
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
            [['description', 'budget'], 'required'],
            [['budget'], 'integer', 'min' => 1],
            [['description'], 'string', 'max' => 255],
            [['description'], 'unique', 'targetAttribute' => ['task_id', 'user_id'], 'message' => 'Вы уже оставляли отклик к этому заданию'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'dt_add' => 'Dt Add',
            'description' => 'Комментариий',
            'budget' => 'Стоимость',
            'task_id' => 'Task ID',
            'is_approved' => 'Is Approved',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    public function getIsHolded()
    {
        return $this->is_denied || $this->is_approved || $this->task->status_id == Status::STATUS_IN_PROGRESS;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}