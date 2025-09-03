<?php
namespace App\Domain;

enum TicketStatus: string {
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';
}