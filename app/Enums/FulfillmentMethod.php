<?php

declare(strict_types=1);

namespace App\Enums;

enum FulfillmentMethod: string
{
    case InHouse = 'in_house';
    case Outsourced = 'outsourced';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::InHouse => 'In-House',
            self::Outsourced => 'Outsourced',
        };
    }
}
