<?php

namespace App\Domain;

class User
{

    //Constructor
    public function __construct(
        private string $username,
        private string $password
    ) {}

    //Getters
    public function username(): string
    {
        return $this->username;
    }
    public function verificaPassword(string $p): bool
    {
        return $this->password === $p;
    }
}
