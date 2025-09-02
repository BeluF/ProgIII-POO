<?php 
namespace Bel\Poo;

class Usuario {
    public function __construct(private string $nombre){} // Constructor

    public function getNombre(): string {
        return $this->nombre;
    }
}