<?php

namespace App\Controller;

use App\Service\AuthService;

//Controlador HTTP para login y logout
class AuthController
{
    /*
     * Constructor con inyección del servicio de autenticación
     */
    public function __construct(private AuthService $authService) {}

    /*
    * Muestra el formualrio de login
    */
    public function loginForm(string $error = ''): void
    {
        // Renderiza la vista desde un template PHP
        require __DIR__ . '/../../templates/Auth/login.php';
    }

    /*
     * Procesa los datos del formulario de login (POST /login)
     */
    public function loginPOST(): void
    {
        //Obtener credenciales (normalizadas)
        $u = isset($_POST['username']) ? trim((string)$_POST['username']) : '';
        $p = isset($_POST['password']) ? trim((string)$_POST['password']) : '';

        //Intento de autenticación
        if ($this->authService->login($u, $p)) {
            header('Location: /');
        } else {
            // Si falla, vuelve a mostrar el formulario con mensaje de error
            $this->loginForm('Credenciales inválidas');
        }
    }

    /*
     * Cierra sesión y redirige a la pantalla de login (GET /logout)
     */
    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /login');
    }
}
