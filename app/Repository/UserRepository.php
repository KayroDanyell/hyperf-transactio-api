<?php

namespace App\Repository;

class UserRepository
{

    public function __construct()
    {

    }

    public function find(int $id) : UserEntity
    {
        return new UserEntity();
    }
}