<?php

use App\Domain\TicketStatus;

$id = $t->getId();
$ttl = htmlspecialchars($t->getName());
$desc = htmlspecialchars($t->getDescripcion());
?>
<div class="ticket">
    <strong>#<?= $id ?></strong> <?= $ttl ?><br>
    <small><?= $desc ?></small><br>
    <?= $this->btnMover($id, TicketStatus::OPEN, '→ Open') ?>
    <?= $this->btnMover($id, TicketStatus::IN_PROGRESS, '→ In Progress') ?>
    <?= $this->btnMover($id, TicketStatus::CLOSED, '→ Closed') ?>
</div>
