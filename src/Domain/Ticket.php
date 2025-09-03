<?php
namespace App\Domain;

class Ticket{

    //Constructor
    public function __construct(
        private int $id,
        private string $name,
        private string $descripcion,
        private TicketStatus $status = TicketStatus::OPEN,
        private ?string $created_for = null,
        private ?string $updated_for = null,
        private ?string $created_at = null,
        private ?string $updated_at = null
    ){}

    //Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }
    public function getStatus(): TicketStatus
    {
        return $this->status;
    }
    public function getCreatedFor(): ?string {
        return $this->created_for;
    }
    public function getUpdatedFor(): ?string {
        return $this->updated_for;
    }
    public function getCreatedAt(): ?string {
        return $this->created_at;
    }
    public function getUpdatedAt(): ?string {
        return $this->updated_at;
    }
    
    public function __toString(): string {
        return "Ticket [id={$this->id}, name={$this->name}, descripcion={$this->descripcion}, status={$this->status->value}, created_for={$this->created_for}, updated_for={$this->updated_for}, created_at={$this->created_at}, updated_at={$this->updated_at}]";
    }

    public function moverA(TicketStatus $nuevo): void {
        $this->status = $nuevo;
    }
}