<?php
declare(strict_types=1);          // Activa verificación estricta de tipos en PHP

session_start();                  // Habilita el uso de $_SESSION para auth y datos

require __DIR__ . '/../vendor/autoload.php'; // Carga el autoloader de Composer

// Importa clases de controladores, repos y servicios
use App\Controller\AuthController;
use App\Controller\TicketController;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\TicketService;

// ----- "Bootstrap" manual (sin contenedor de inyección de dependencias) -----
// Instancia repositorios
$userRepo   = new UserRepository();
$ticketRepo = new TicketRepository();

// Instancia servicios pasando repositorios
$auth   = new AuthService($userRepo);
$ticket = new TicketService($ticketRepo);

// Instancia controladores pasando servicios
$authController   = new AuthController($auth);
$ticketController = new TicketController($auth, $ticket);

// ----- Router mínimo basado en path + method -----
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Extrae la ruta (sin query)
$method = $_SERVER['REQUEST_METHOD'];                        // GET/POST

switch (true) {
    // Muestra formulario de login
    case $path === '/login' && $method === 'GET':
        $authController->loginForm();
        break;

    // Procesa el login
    case $path === '/login' && $method === 'POST':
        $authController->loginPost();
        break;

    // Logout
    case $path === '/logout':
        $authController->logout();
        break;

    // Crear ticket (form del tablero)
    case $path === '/tickets/create' && $method === 'POST':
        $ticketController->create();
        break;

    // Mover ticket entre columnas
    case $path === '/tickets/move' && $method === 'POST':
        $ticketController->move();
        break;

    // Home: muestra el tablero
    default:
        $ticketController->getBoard();
        break;
}
