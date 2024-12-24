<?php

namespace app\controllers;

use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

abstract class SecuredController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @param $id
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findOrDie($id, $modelClass): ActiveRecord
    {
        $reply = $modelClass::findOne($id);

        if (!$reply) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $reply;
    }

    public function getUser()
    {
        return \Yii::$app->user->getIdentity();
    }
}