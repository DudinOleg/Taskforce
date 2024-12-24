<?php

namespace app\controllers;

use app\helpers\UIHelper;
use app\models\Category;
use app\models\File;
use app\models\Opinion;
use app\models\Reply;
use app\models\Status;
use app\models\Task;
use frexin\logic\actions\CancelAction;
use frexin\logic\actions\DenyAction;
use frexin\logic\AvailableActions;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TasksController extends SecuredController
{
    public function actionIndex(): string
    {
        $task = new Task();
        $task->load(Yii::$app->request->post());

        $tasksQuery = $task->getSearchQuery();
        $categories = Category::find()->all();

        $countQuery = clone $tasksQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 5]);
        $models = $tasksQuery->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', ['models' => $models, 'pages' => $pages, 'task' => $task, 'categories' => $categories]);
    }

    public function actionCreate()
    {
        $task = new Task();
        $categories = Category::find()->all();

        if (!Yii::$app->session->has('task_uid')) {
            Yii::$app->session->set('task_uid', uniqid('upload'));
        }

        if (Yii::$app->request->isPost) {
            $task->load(Yii::$app->request->post());
            $task->uid = Yii::$app->session->get('task_uid');
            $task->save();

            if ($task->id) {
                Yii::$app->session->remove('task_uid');
                return $this->redirect(['tasks/view', 'id' => $task->id]);
            }
        }

        return $this->render('create', ['model' => $task, 'categories' => $categories]);
    }

    public function actionMy($status = null)
    {
        $menuItems = UIHelper::getMyTasksMenu($this->getUser()->is_contractor);

        if (!$status) {
            $this->redirect($menuItems[0]['url']);
        }

        $tasks = $this->getUser()->getTasksByStatus($status)->all();

        return $this->render('my', ['menuItems' => $menuItems, 'tasks' => $tasks]);
    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {
            $model = new File();
            $model->task_uid = Yii::$app->session->get('task_uid');
            $model->file = UploadedFile::getInstanceByName('file');

            $model->upload();

            return $this->asJson($model->getAttributes());
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $task = $this->findOrDie($id, Task::class);
        $reply = new Reply;
        $opinion = new Opinion;

        return $this->render('view', ['model' => $task, 'newReply' => $reply, 'opinion' => $opinion]);
    }

    public function actionCancel($id)
    {
        /**
         * @var Task $task
         */
        $task = $this->findOrDie($id, Task::class);
        $task->goToNextStatus(new CancelAction);

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    public function actionDeny($id)
    {
        /**
         * @var Task $task
         */
        $task = $this->findOrDie($id, Task::class);
        $task->goToNextStatus(new DenyAction());

        $performer = $task->performer;
        $performer->increaseFailCount();

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }
}