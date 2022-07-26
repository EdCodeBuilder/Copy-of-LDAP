<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modules\CitizenPortal\src\Models\ActivityAsisstance;
use Faker\Generator as Faker;

$factory->define(ActivityAsisstance::class, function (Faker $faker) {
    return [
        'activity_id'   =>rand(1, 10),
        'date'          =>$faker->date($format = 'Y-m-d'),
        // 'date'          =>null,
        'stage'         =>$faker->word(),
        'activity_name' =>$faker->sentence(),
        'content'       =>$faker->paragraph(),
        'start_time'    =>$faker->dateTime(),
        'end_time'      =>$faker->dateTime()
        /*
        'start_time'    =>null,
        'end_time'      =>null,
        'deleted_at'    =>null
        */
    ];
});
