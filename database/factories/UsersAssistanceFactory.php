<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modules\CitizenPortal\src\Models\UsersAssistance;
use Faker\Generator as Faker;

$factory->define(UsersAssistance::class, function (Faker $faker) {
    return [
        'activity_id'   =>rand(1, 30),
        'users_id'      =>rand(752, 761),
    ];
});
