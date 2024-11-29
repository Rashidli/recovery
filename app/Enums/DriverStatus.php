<?php

namespace App\Enums;

enum DriverStatus: string
{
    case DRIVER_ASSIGN = 'driver_assigned';
    case DRIVER_REACHED_CUSTOMER = 'driver_reached_customer'; // Corrected name
    case DRIVER_PICK = 'driver_pick';
    case DRIVER_REACHED_DROP = 'driver_reached_drop'; // New unique status
    case DRIVER_DROP = 'driver_drop';

    // Method to get the status text based on the status value
    public static function getStatusText(string $status): string
    {
        return match($status) {
            self::DRIVER_ASSIGN->value => 'Driver Assigned',
            self::DRIVER_REACHED_CUSTOMER->value => 'Driver Reached at the customer location',
            self::DRIVER_PICK->value => 'Driver Picked up the car',
            self::DRIVER_REACHED_DROP->value => 'Driver Reached at the drop-off location',
            self::DRIVER_DROP->value => 'Driver Dropped off the car',
            default => 'Unknown status',
        };
    }

    public static function getStatusPriority(string $status): int
    {
        return match($status) {
            self::DRIVER_ASSIGN->value => 1,
            self::DRIVER_REACHED_CUSTOMER->value => 2,
            self::DRIVER_PICK->value => 3,
            self::DRIVER_REACHED_DROP->value => 4,
            self::DRIVER_DROP->value => 5,
            default => 0,
        };
    }
}
