<?php

declare(strict_types=1);

use App\Enum\UserTypesEnum;
use App\Model\Wallet;
use Hyperf\Database\Seeders\Seeder;
use App\Model\User;
use function FriendsOfHyperf\ModelFactory\factory;
use Hyperf\Database\Model\Factory;
/** @var Factory $factory */

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchantUser = factory(User::class)->create(['type', UserTypesEnum::MERCHANT]);
        factory(Wallet::class)->create(['owner_id', $merchantUser->id]);

        $commonUser =  factory(User::class)->create(['type', UserTypesEnum::COMMON]);
        factory(Wallet::class)->create(['owner_id', $commonUser->id]);
    }
}
