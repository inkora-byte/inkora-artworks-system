<?php

declare(strict_types=1);

namespace App\Enums;

enum AdjustmentAppliesTo: string
{
    case Product = 'product';
    case Material = 'material';
    case Both = 'both';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Product => 'Products',
            self::Material => 'Materials',
            self::Both => 'Both',
        };
    }
}
