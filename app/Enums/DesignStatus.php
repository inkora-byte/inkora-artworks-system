<?php

declare(strict_types=1);

namespace App\Enums;

enum DesignStatus: string
{
    case Active = 'active';
    case Retired = 'retired';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Retired => 'Retired',
        };
    }

    /**
     * The <x-status-badge> color this status maps to.
     */
    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Retired => 'secondary',
        };
    }
}
