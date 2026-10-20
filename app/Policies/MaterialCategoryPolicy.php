<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MaterialCategory;
use App\Models\User;

class MaterialCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-materials');
    }

    public function view(User $user, MaterialCategory $materialCategory): bool
    {
        return $user->can('manage-materials');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-materials');
    }

    public function update(User $user, MaterialCategory $materialCategory): bool
    {
        return $user->can('manage-materials');
    }

    public function delete(User $user, MaterialCategory $materialCategory): bool
    {
        return $user->can('manage-materials');
    }
}
