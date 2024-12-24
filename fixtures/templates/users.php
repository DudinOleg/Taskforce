<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\City;
use yii\db\Expression;

return [
    'email' => $faker->unique()->email,
    'name' => $faker->name,
    'city_id' => City::find()->select('id')->orderBy(new Expression('rand()'))->scalar(),
    'password' => Yii::$app->security->generatePasswordHash('qwerty'),
    'avatar' => '/img/avatars/' . rand(1, 5) . '.png'
];