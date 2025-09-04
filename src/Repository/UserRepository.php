<?php
namespace App\Repository;

use App\Domain\User;

class UserRepository {
    /* Seeder de ejemplo */
    public function seed(): void{
        $needsSeed = false;
        if (!isset($_SESSION['users']) || !is_array($_SESSION['users'])) {
            $needsSeed = true;
        } else {
            // Verificación básica de estructura esperada
            $admin = $_SESSION['users']['admin'] ?? null;
            if (!is_array($admin) || !isset($admin['username'], $admin['password'])) {
                $needsSeed = true;
            }
        }

        if ($needsSeed) {
            // Guardamos un mapa asociativo por username, con datos planos
            $_SESSION['users'] = [
                'admin' => ['username' => 'admin', 'password' => 'admin'],
                'user'  => ['username' => 'user',  'password' => 'user'],
            ];
        }
    }

    public function findByUsername(string $username): ?User {
        $this->seed();
        if (!isset($_SESSION['users'][$username])) return null;
        $us = $_SESSION['users'][$username];
        return new User($us['username'], $us['password']); //Mapeo manual a entidad User
    }
}
