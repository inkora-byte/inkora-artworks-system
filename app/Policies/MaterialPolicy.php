<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    /**
     * Determine whether the user can view the list of materials.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-materials');
    }

    /**
     * Determine whether the user can create materials.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-materials');
    }

    /**
     * Determine whether the user can update the given material.
     */
    public function update(User $user, Material $material): bool
    {
        return $user->can('manage-materials');
    }

    /**
     * Determine whether the user can activate/phase-out the given
     * material.
     */
    public function toggleStatus(User $user, Material $material): bool
    {
        return $user->can('manage-materials');
    }
}
