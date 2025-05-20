<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'new';
    case ACCEPTED = 'accepted';
    case IN_PROGRESS = 'in_progress';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
    case REACHED_CANCELLED = 'reached_cancelled';

    // Label for each status (English)
    public function label(): string
    {
        return match($this) {
            self::NEW => 'New',
            self::ACCEPTED => 'Accepted',
            self::IN_PROGRESS => 'In Progress',
            self::CANCELED => 'Canceled',
            self::COMPLETED => 'Completed',
            self::REACHED_CANCELLED => 'Reached and Cancelled',
        };
    }

    // Color for each status
    public function color(): string
    {
        return match($this) {
            self::NEW => 'blue',
            self::ACCEPTED => 'purple',
            self::IN_PROGRESS => 'orange',
            self::CANCELED => 'red',
            self::COMPLETED => 'green',
            self::REACHED_CANCELLED => 'gray',
        };
    }
}
