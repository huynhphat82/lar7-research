<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'code' => 'R00001',
        'email' => $faker->unique()->safeEmail,
        'company_id' => 1,
    ];
});
