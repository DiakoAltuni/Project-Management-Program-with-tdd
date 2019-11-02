<?php

/** @var Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(\App\Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'project_id' =>factory(\App\Project::class),
        'completed' => false,
    ];
});
