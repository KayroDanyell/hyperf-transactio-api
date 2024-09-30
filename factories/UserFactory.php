<?php
declare(strict_types=1);

use App\Enum\UserTypesEnum;
use App\Model\User;
use Faker\Factory as Faker;
use Hyperf\Database\Model\Factory;

/** @var Factory $factory */

$faker = Faker::create('pt_BR');

 $factory->define(User::class, function () use ($faker) {
     return [
         'id' => $faker->uuid(),
         'name' => $faker->name,
         'email' => $faker->unique()->safeEmail,
         'document' => $faker->unique()->cpf,
         'type' => $faker->randomElement(UserTypesEnum::values())
     ];
 });