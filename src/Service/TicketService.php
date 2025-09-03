<?php
namespace App\Service;

use App\Domain\Ticket;
use App\Domain\TicketStatus;
use App\Repository\TicketRepository;

//Gestionar las opercaiones sobre los tickets
class TicketService{
    //Inyección de dependencias
    public function __construct(private TicketRepository $ticketRepository){}

    //Crear un ticket nuevo: se delega al repositorio
    public function create(string $name, string $descripcion, string $created_for): bool{
        $this->ticketRepository->create($name, $descripcion, $created_for);
        return true;
    }

    //Cambiar el estado de un ticket
    public function update(int $id, TicketStatus $nuevo, string $updated_for): bool{
        $this->ticketRepository->updateStatus($id, $nuevo, $updated_for);
        return true;
    }

    //Listar todos los tickets
    public function getAll(): array{
        return $this->ticketRepository->getAll();
    }
}
