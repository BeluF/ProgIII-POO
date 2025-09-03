<?php
namespace App\Service;

use App\Domain\User;
use App\Repository\UserRepository;

//Maneja login, logout y sesión
class AuthService{
    //Inyección del repo de usuarios
    public function __construct(private UserRepository $userRepository){}

    //Validacion de usuario y contraseña
    public function login(string $username, string $password): bool{
        $user = $this->userRepository->findByUsername($username);
        if(!$user) return false; //No existe el usuario
        if(!$user->verificaPassword($password)) return false; //Contraseña incorrecta

        //Guardar los datos mínimos en sesión
        $_SESSION["auth"] = ['username' => $user->username()];
        return true;
    }

    //Cerrar la sesión
    public function logout(): bool{
        if(isset($_SESSION["auth"])){ //Si hay sesión iniciada
            unset($_SESSION["auth"]); //Destruir
        }
        return true;        
    }

    // Devuelve el username logueado o null si no hay sesión
    public function usernameActual(): ?string {
        return $_SESSION['auth']['username'] ?? null;
    }

    //Protección de rutas: si no está logueado, redirigir al login
    public function isLoged(): void{
        if (!$this->usernameActual()) {
            header("Location: /login"); // Redirección HTTP
            exit;                       // Corta la ejecución
        }
    }
}