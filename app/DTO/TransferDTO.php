<?php

namespace App\DTO;

use App\Model\User;
use App\Repository\UserRepository;

readonly class TransferDTO
{
    private ?string $id;
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

    public function toArray() : array
    {
        return [
            'payer' => $this->payer->id,
            'payee' => $this->payee->id,
            'value' => $this->value
        ];
    }

    public function getPayer() : User
    {
        return $this->payer;
    }

    public function getPayee() : User
    {
        return $this->payee;
    }

    public function getValue() : int
    {
        return $this->value;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }
}