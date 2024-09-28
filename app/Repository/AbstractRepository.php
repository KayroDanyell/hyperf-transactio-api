<?php

namespace App\Repository;

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