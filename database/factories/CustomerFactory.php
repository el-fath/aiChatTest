<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'gender' => $faker->randomElement(['male', 'female']),
        'date_of_birth' => $faker->dateTime(),
        'contact_number' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail
    ];
});
