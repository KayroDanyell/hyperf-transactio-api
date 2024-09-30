<?php

namespace App\Repository;

use App\Entity\UserEntity\UserEntity;
use App\Model\User;

class UserRepository
{
    public User $userModel;

    public function __construct(){}

    public static function find(string $id) : User
    {
        return User::find($id);
    }
}