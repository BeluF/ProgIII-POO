<?php
require __DIR__ . '/../vendor/autoload.php';

use Bel\Poo\Usuario;

$u = new Usuario("Belén");
echo $u->getNombre();
