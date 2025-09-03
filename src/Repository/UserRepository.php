<?php
namespace App\Repository;

use App\Domain\User;

class UserRepository {
    /* Seeder de ejemplo */
    public function seed(): void{
        if (!isset($_SESSION["users"])) {
            $_SESSION["users"] = [
            $_SESSION["users"][] = new User("admin", "admin"),
            $_SESSION["users"][] = new User("user", "user")
            ];
        }
    }

    public function findByUsername(string $username): ?User {
        $this->seed();
        if (!isset($_SESSION["users"][$username])) return null;
        $us = $_SESSION["users"][$username];
        return new User($us['username'], $us['password']); //Mapeo manual a entidad User
    }
}