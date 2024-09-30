<?php

namespace App\Repository;

use App\Interface\Repository\RepositoryInterface;
use Hyperf\DbConnection\Db;

abstract class AbstractRepository implements RepositoryInterface
{

    public function __construct(private Db $database)
    {}

    public function beginTransaction(): void
    {
        $this->database->beginTransaction();
    }

    public function commit() : void
    {
        $this->database->commit();
    }

    public function rollback() : void
    {
        $this->database->rollBack();
    }

}