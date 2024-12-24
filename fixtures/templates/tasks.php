<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\User;
use yii\db\Expression;

return [
    'name' => $faker->sentence,
    'category_id' => rand(1, 8),
    'description' => $faker->realTextBetween(),
    'budget' => rand(1000, 10000),
    'dt_add' => $faker->dateTimeBetween('-1 month')->format('Y-m-d'),
    'client_id' => User::find()->select('id')->orderBy(new Expression('rand()'))->scalar(),
    'expire_dt' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
    'status_id' => 1
];