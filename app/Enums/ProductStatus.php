<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case Active = 'active';
    case Discontinued = 'discontinued';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Discontinued => 'Discontinued',
        };
    }

    /**
     * The <x-status-badge> color this status maps to.
     */
    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Discontinued => 'secondary',
        };
    }
}
