<?php

namespace App\Interface\Repository;

use Hyperf\DbConnection\Db;

interface RepositoryInterface
{
    public function beginTransaction(): void;


    public function commit() : void;


    public function rollback() : void;

}