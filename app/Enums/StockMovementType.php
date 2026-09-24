<?php

declare(strict_types=1);

namespace App\Enums;

enum StockMovementType: string
{
    case Restock = 'restock';
    case Production = 'production';
    case Sale = 'sale';
    case Adjustment = 'adjustment';
    case Returned = 'return';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Restock => 'Restock',
            self::Production => 'Production',
            self::Sale => 'Sale',
            self::Adjustment => 'Adjustment',
            self::Returned => 'Return',
        };
    }
}

