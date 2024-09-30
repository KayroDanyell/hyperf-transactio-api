<?php

namespace factories;

use Hyperf\Database\Model\Factory;
use App\Model\Wallet;
use Faker\Factory as Faker;

/** @var Factory $factory */

$faker = Faker::create('pt_BR');

$factory->define(Wallet::class, function () use ($faker) {
    return [
        'id' => $faker->uuid(),
        'owner_id' => $faker->numberBetween(1, 10), // Assuming there are 10 users
        'balance' => $faker->randomNumber(2),
    ];
});