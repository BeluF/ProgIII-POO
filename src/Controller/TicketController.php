<?php

namespace App\Controller;

use App\Domain\TicketStatus;
use App\Service\TicketService;
use App\Service\AuthService;

//Controlador del tablero
class TicketController
{
    //Inyección de dependencias
    public function __construct(
        private AuthService $authService,
        private TicketService $ticketsService
    ) {}

    //Mostrar el tablero (GET)
    public function getBoard(): void
    {
        $this->authService->isLoged(); // Proteger la ruta
        $username = $this->authService->usernameActual(); //Obtener el usuario actual
        $tickets = $this->ticketsService->getAll(); //Obtener todos los tickets

        //Separar los tickets por columnas y estado
        $statusOptions = [
            'open' => [],
            'in_progress' => [],
            'done' => [],
        ];

        foreach ($tickets as $t) {
            $statusOptions[$t->status()->value][] = $t; //Agrrupar por estado
        }

        // Cabecera básica del tablero
        echo '<h2>Ticket Board</h2>';
        echo '<p>Usuario: ' . htmlspecialchars($username) . ' | <a href="/logout">Logout</a></p>';

        // Formulario para crear nuevo ticket rápido
        echo <<<HTML
        <fieldset>
          <legend>Nuevo ticket</legend>
          <form method="post" action="/tickets/create">
            <input name="titulo" placeholder="Título" required>
            <input name="descripcion" placeholder="Descripción" required>
            <button type="submit">Crear</button>
          </form>
        </fieldset>
        <style>
        /* Estilos inline simples para maquetar el tablero */
        .board { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
        .col { background:#f4f4f4; border:1px solid #ddd; padding:10px; border-radius:8px; }
        .ticket { background:#fff; border:1px solid #ccc; padding:8px; margin-bottom:8px; border-radius:6px; }
        .col h3 { margin-top:0 }
        form.inline { display:inline; }
        </style>
        <div class="board">
          <div class="col">
            <h3>Todo</h3>
HTML;

        //Renderizar los tickets "open"
        foreach ($statusOptions['open'] as $t) {
            $this->renderTicket($t);
        }

        // Columna IN PROGRESS
        echo <<<HTML
          </div>
          <div class="col">
            <h3>In Progress</h3>
HTML;

        foreach ($statusOptions['in_process'] as $t) {
            $this->renderTicket($t);
        }
        // Columna DONE
        echo <<<HTML
          </div>
          <div class="col">
            <h3>Done</h3>
HTML;

        foreach ($statusOptions['done'] as $t) {
            $this->renderTicket($t);
        }

        // Cierra el grid
        echo "</div>";
    }

    //Renderizar un ticket individual
    private function renderTicket($t): void
    {
        $id = $t->id();
        $ttl = htmlspecialchars($t->titulo());       // Escapa HTML
        $desc = htmlspecialchars($t->descripcion()); // Escapa HTML
        echo "<div class='ticket'><strong>#$id</strong> $ttl<br><small>$desc</small><br>";

        // Botones (formularios inline) para mover a cada estado
        echo $this->btnMover($id, TicketStatus::OPEN, '→ Open');
        echo $this->btnMover($id, TicketStatus::IN_PROGRESS, '→ In Progress');
        echo $this->btnMover($id, TicketStatus::CLOSED, '→ Closed');

        echo "</div>";
    }

    //Botón para mover un ticket a otro estado
    private function btnMover(int $id, TicketStatus $nuevo, string $label): string
    {
        $username = $this->authService->usernameActual();
        return <<<HTML
         <form class="inline" method="post" action="/tickets/move">
            <input type="hidden" name="id" value="{$id}">
            <input type="hidden" name="to" value="{$nuevo->value}">
            <button type="submit">$label</button>
        </form>
        HTML;
    }

    // Crea un ticket (POST /tickets/create)
    public function create(): void {
        $this->authService->isLoged();               // Protege acción
        $titulo = trim($_POST['titulo'] ?? '');     // Lee y sanitiza entradas
        $desc   = trim($_POST['descripcion'] ?? '');
        $user   = $this->authService->usernameActual() ?? 'system';

        // Valida que haya datos
        if ($titulo !== '' && $desc !== '') {
            $this->ticketsService->create($titulo, $desc, $user); // Crea ticket
        }
        header("Location: /"); // Vuelve al tablero
    }

    // Mueve un ticket a otro estado (POST /tickets/move)
    public function move(): void {
        $this->authService->isLoged();                 // Protege acción
        $id = (int)($_POST['id'] ?? 0);               // ID de ticket
        $to = $_POST['to'] ?? 'OPEN';                 // String de estado destino
        $updated_for = $this->authService->usernameActual() ?? 'system';
        $this->ticketsService->update($id, TicketStatus::from($to), $updated_for); // Convierte a enum y mueve
        header("Location: /");                        // Vuelve al tablero
    }
}
