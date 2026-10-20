<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MaterialUnit;
use App\Models\User;

class MaterialUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-materials');
    }

    public function view(User $user, MaterialUnit $materialUnit): bool
    {
        return $user->can('manage-materials');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-materials');
    }

    public function update(User $user, MaterialUnit $materialUnit): bool
    {
        return $user->can('manage-materials');
    }

    public function delete(User $user, MaterialUnit $materialUnit): bool
    {
        return $user->can('manage-materials');
    }
}
