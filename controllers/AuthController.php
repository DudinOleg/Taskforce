<?php

namespace app\controllers;
use app\models\Auth;
use app\models\City;
use app\models\LoginForm;
use app\models\User;
use yii\web\Controller;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'vk' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $userData = $client->getUserAttributes();

        $auth = Auth::find()->where(['source' => $client->getId(), 'source_id' => $userData['id']])->one();

        if ($auth) {
            Yii::$app->user->login($auth->user);
        } else {
            if (isset($userData['email']) && User::find()->where(['email' => $userData['email']])->exists()) {
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан"),
                ]);
            } else {
                $city = City::find()->one();

                if (isset($userData['city']['title'])) {
                    $city = City::find()->where(['name' => $userData['city']['title']])->one();
                }

                $password = Yii::$app->security->generateRandomString(6);
                $user = new User([
                    'name' => $userData['first_name'],
                    'email' => $userData['email'],
                    'city_id' => $city->id,
                    'password' => Yii::$app->security->generatePasswordHash($password),
                ]);

                if ($user->save()) {
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $client->getId(),
                        'source_id' => (string) $userData['id'],
                    ]);
                    $auth->save();

                    Yii::$app->user->login($user);
                }
            }
        }

        $this->goHome();
    }

    public function actionSignup()
    {
        $user = new User(['scenario' => 'insert']);
        $cities = City::find()->orderBy('name')->all();

        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($user);
            }

            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);

                $user->save(false);
                $this->goHome();
            }
        }

        return $this->render('signup', ['model' => $user, 'cities' => $cities]);
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();

        if (\Yii::$app->request->getIsPost()) {
            $loginForm->load(\Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                \Yii::$app->user->login($loginForm->getUser());

                return $this->goHome();
            }
        }
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->goHome();
    }
}