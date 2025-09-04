<?php

namespace App\Controller;

use App\Domain\TicketStatus;
use App\Service\TicketService;
use App\Service\AuthService;

//Controlador del tablero
class TicketController
{
    /*
     * Constructor con inyección de dependencias
     */
    public function __construct(
        private AuthService $authService,
        private TicketService $ticketsService
    ) {}

    /*
     * Muestra el tablero principal con todas las columnas
     */
    public function getBoard(): void
    {
        $this->authService->isLoged();                      // Proteger la ruta
        $username = $this->authService->usernameActual();   //Obtener el usuario actual
        $tickets = $this->ticketsService->getAll();         //Obtener todos los tickets

        //Separar los tickets por columnas según su estado
        $statusOptions = [
            'open'          => [],
            'in_progress'   => [],
            'closed'        => [],
        ];

        foreach ($tickets as $t) {
            $statusOptions[$t->getStatus()->value][] = $t; //Agrrupar por estado
        }

        // Renderizar tablero usando template
        require __DIR__ . '/../../templates/Ticket/tablero.php';
    }

    /*
     * Genera un formulario (botón) para mover un ticket de columna
     */
    private function btnMover(int $id, TicketStatus $nuevo, string $label): string
    {
        return <<<HTML
         <form class="inline" method="post" action="/tickets/move">
            <input type="hidden" name="id" value="{$id}">
            <input type="hidden" name="to" value="{$nuevo->value}">
            <button type="submit">$label</button>
        </form>
        HTML;
    }

    /*
     * Crea un ticket (POST /tickets/create)
     */
    public function create(): void
    {
        $this->authService->isLoged();               // Protege acción
        $name = trim($_POST['name'] ?? '');     // Lee y sanitiza 
        $desc   = trim($_POST['descripcion'] ?? '');
        $user   = $this->authService->usernameActual() ?? 'system';

        // Valida que haya datos
        if ($name !== '' && $desc !== '') {
            $this->ticketsService->create($name, $desc, $user); // Crea ticket
        }
        header("Location: /"); // Vuelve al tablero
    }

    /*
     * Mueve un ticket a otro estado (POST /tickets/move)
     */
    public function move(): void
    {
        $this->authService->isLoged();                 // Protege acción
        $id = (int)($_POST['id'] ?? 0);               // ID de ticket
        $to = $_POST['to'] ?? 'open';                 // String de estado destino
        $updated_for = $this->authService->usernameActual() ?? 'system';
        $this->ticketsService->update($id, TicketStatus::from($to), $updated_for);
        header('Location: /');                         // Vuelve al tablero
    }
}
