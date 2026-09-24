<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;

class StockMovementPolicy
{
    /**
     * Determine whether the user can view stock levels / movement history.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-inventory');
    }

    /**
     * Determine whether the user can record a manual stock adjustment.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-inventory');
    }
}
