<?php
namespace App\Repository;

use App\Domain\Ticket;
use App\Domain\TicketStatus;

class TicketRepository {

    /* Seeder de ejemplo */
    public function seed(): void {
        if(!isset($_SESSION["tickets"])) {
            $_SESSION["tickets"] = []; //Lista de tickets asociativo
            //Ejemplo de tickets
             $this->insert(new Ticket(1, "Alta de impresora", "Configurar HP 107w", TicketStatus::OPEN, "admin"));
            $this->insert(new Ticket(2, "Bug login", "Error cookie expira mal", TicketStatus::IN_PROGRESS, "user"));
        }
    }

    //Calcular el proximo ID
     private function nextId(): int {
        $ids = array_map(fn($t)=>$t['id'], $_SESSION['tickets']); // Extrae ids
        return empty($ids) ? 1 : max($ids) + 1; // Si no hay, arranca en 1
    }

    //Insertar un ticket en la tabla de sesion como un array simple
    public function insert(Ticket $t): void {
        $_SESSION['tickets'][] = [
            'id' => $t->getId(),
            'name' => $t->getName(),
            'descripcion' => $t->getDescripcion(),
            'status' => $t->getStatus()->value, // Persistimos el string del enum
            'created_for' => $t->getCreatedFor(),
            'updated_for'=> $t->getUpdatedFor(),
            'created_at'=> $t->getCreatedAt(),
            'updated_at'=> $t->getUpdatedAt()
        ];
    }

    //Crear un nuevo ticket
    public function create(string $name, string $descripcion, string $created_for): Ticket {
        $this->seed();
        $id = $this->nextID(); //Genera el ID
        $t = new Ticket($id, $name, $descripcion, TicketStatus::OPEN, $created_for, null, date('Y-m-d H:i:s'), null);
        $this->insert($t); //Guarda
        return $t;
    }

    //Obtener los tickets (como objetos Ticket)
    public function getAll(): array {
        $this->seed();

        //Mapear las filas del array a una entidad Ticket
        return array_map(function($t) {
            return new Ticket(
                $t['id'],
                $t['name'],
                $t['descripcion'],
                TicketStatus::from($t['status']), // Reconstruir el enum desde el string
                $t['created_for'],
                $t['updated_for'],
                $t['created_at'],
                $t['updated_at']
            );
        }, $_SESSION['tickets']);
    }

    //Buscar un ticket por ID
    public function find(int $id): ?Ticket {
        $this->seed();
        foreach ($_SESSION['tickets'] as $row) {
            if ($row['id'] === $id) {
                return new Ticket(
                    $row['id'],
                    $row['titulo'],
                    $row['descripcion'],
                    TicketStatus::from($row['status']),
                    $row['creadoPor']
                );
            }
        }
        return null; // No encontrado
    }

    // Actualiza el estado de un ticket en la "persistencia" de sesión
    public function updateStatus(int $id, TicketStatus $nuevo, string $updated_for): void {
        foreach ($_SESSION['tickets'] as &$row) {     // Recorre por referencia
            if ($row['id'] === $id) {
                $row['status'] = $nuevo->value;       // Cambia el string del estado
                $row['updated_for'] = $updated_for;  // Quién hizo el cambio
                $row['updated_at'] = date('Y-m-d H:i:s'); // Fecha de actualización (ahora)
                break;                                // Sale al encontrarlo
            }
        }
    }
}