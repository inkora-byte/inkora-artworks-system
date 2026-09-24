<?php

declare(strict_types=1);

namespace App\Enums;

enum MaterialStatus: string
{
    case Active = 'active';
    case PhasedOut = 'phased_out';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::PhasedOut => 'Phased Out',
        };
    }

    /**
     * The <x-status-badge> color this status maps to.
     */
    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::PhasedOut => 'secondary',
        };
    }
}
