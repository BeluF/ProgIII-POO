<?php

namespace App\Controller;

use App\Service\AuthService;

//Controlador HTTP para login y logout
class AuthController
{
    //Iyección del servicio de autenticación
    public function __construct(private AuthService $authService) {}

    //Formulario de login
    public function loginForm(): void
    {
        // Salida HTML mínima (en un proyecto real: template)
        echo <<<HTML
        <h2>Login</h2>
        <form method="post" action="/login">
            <label>Usuario: <input name="username" required></label><br>
            <label>Clave: <input name="password" type="password" required></label><br>
            <button type="submit">Ingresar</button>
        </form>
        <p><small>Demo: admin/admin123 o belu/1234</small></p>
HTML;
    }

    //Procesar el login
    public function loginPOST(): void
    {
        //Obtener credenciales
        $u = $_POST["username"] ?? "";
        $p = $_POST["password"] ?? "";

        //Intento de autenticación
        if ($this->authService->login($u, $p)) {
            header("Location: /");
        } else {
            echo "<p>Credenciales inválidas</p>";
            $this->loginForm();
        }
    }


    // Cierra sesión y redirige a login (GET /logout)
    public function logout(): void
    {
        $this->authService->logout();
        header("Location: /login");
    }
}
