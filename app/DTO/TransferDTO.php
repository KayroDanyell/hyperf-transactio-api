<?php

namespace App\DTO;

use App\Model\User;
use App\Repository\UserRepository;

readonly class TransferDTO
{
    public function __construct(
        public readonly User $payer,
        public readonly User $payee,
        public readonly int $value
    ){}

    public static function fromArray(array $data) : self
    {
        return new self(
            payer: UserRepository::find($data['payer']),
            payee: UserRepository::find($data['payee']),
            value: $data['value']
        );
    }

    public function getPayer() : UserEntity
    {
    }

    public function getPayee() : UserEntity
    {
    }

    public function getValue() : int
    {
    }

    public function setId(int $id)
    {
    }

    public function getId() : int
    {
    }
}