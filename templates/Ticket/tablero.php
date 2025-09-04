<?php

/** @var string $username */ ?>
<h2>Tablero de ticket</h2>
<p>Usuario: <?= htmlspecialchars($username) ?> | <a href="/logout">Logout</a></p>

<fieldset>
    <legend>Nuevo ticket</legend>
    <form method="post" action="/tickets/create">
        <input name="name" placeholder="Título" required>
        <input name="descripcion" placeholder="Descripción" required>
        <button type="submit">Crear</button>
    </form>
</fieldset>

<style>
    /* Estilos inline simples para maquetar el tablero */
    .board {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
    }

    .col {
        background: #f4f4f4;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
    }

    .ticket {
        background: #fff;
        border: 1px solid #ccc;
        padding: 8px;
        margin-bottom: 8px;
        border-radius: 6px;
    }

    .col h3 {
        margin-top: 0
    }

    form.inline {
        display: inline;
    }
</style>

<div class="board">
    <div class="col">
        <h3>Open</h3>
        <?php foreach ($statusOptions['open'] as $t): ?>
            <?php include __DIR__ . '/ticket.php'; ?>
        <?php endforeach; ?>
    </div>
    <div class="col">
        <h3>In Progress</h3>
        <?php foreach ($statusOptions['in_progress'] as $t): ?>
            <?php include __DIR__ . '/ticket.php'; ?>
        <?php endforeach; ?>
    </div>
    <div class="col">
        <h3>Closed</h3>
        <?php foreach ($statusOptions['closed'] as $t): ?>
            <?php include __DIR__ . '/ticket.php'; ?>
        <?php endforeach; ?>
    </div>
</div>
