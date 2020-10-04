<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'subtotal' => $faker->numberBetween(100000, 800000),
        'shipping_fee' => $faker->numberBetween(10000, 25000),
        'discount' => $faker->numberBetween(8000, 100000),
        'reward_amount' => $faker->numberBetween(5000, 50000),
        'total' => $faker->numberBetween(20000, 1000000),
        'items' => json_encode([
            'code' => $faker->swiftBicNumber,
            'name' => $faker->name,
            'short_name' => $faker->name,
            'description' => $faker->text(150),
            'qty' => $faker->numberBetween(1, 10),
            'price' => $faker->numberBetween(8000, 400000),
            'unit' => 'VND',
        ]),
        'user_id' => $faker->numberBetween(1, 10),
        'channel_id' => 1,
    ];
});
