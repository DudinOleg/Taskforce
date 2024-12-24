<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Task;
use app\models\User;
use yii\db\Expression;

return [
    'description' => $faker->sentence,
    'rate' => rand(1, 5),
    'dt_add' => $faker->dateTimeBetween('-1 month')->format('Y-m-d'),
    'owner_id' => User::find()->select('id')->orderBy(new Expression('rand()'))->scalar(),
    'task_id' => Task::find()->select('id')->orderBy(new Expression('rand()'))->scalar(),
    'performer_id' => User::find()->select('id')->orderBy(new Expression('rand()'))->scalar(),
];