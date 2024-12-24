<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Task;
use app\models\User;
use yii\db\Expression;

return [
    'user_id' => User::find()->select('id')->orderBy(new Expression('rand()'))->scalar(),
    'task_id' => Task::find()->select('id')->orderBy(new Expression('rand()'))->scalar(),
    'description' => $faker->realTextBetween(),
    'dt_add' => $faker->dateTimeBetween('-1 month')->format('Y-m-d'),
    'budget' => rand(1000, 10000)
];