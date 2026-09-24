<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Design;
use App\Models\User;

class DesignPolicy
{
    /**
     * Determine whether the user can view the list of designs.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-catalog');
    }

    /**
     * Determine whether the user can create designs.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-catalog');
    }

    /**
     * Determine whether the user can update the given design.
     */
    public function update(User $user, Design $design): bool
    {
        return $user->can('manage-catalog');
    }
}
