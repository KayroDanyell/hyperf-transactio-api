<?php

namespace App\Entity\UserEntity;

class UserEntity
{
    private string $id;
    private string $name;
    private string $email;
    private string $type;
    public function __construct(string $id,string $name,string $email,string $type) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->type = $type;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getType(): string
    {
        return $this->type;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

}