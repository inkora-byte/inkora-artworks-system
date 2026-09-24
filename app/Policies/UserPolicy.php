<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view the list of users.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-users');
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-users');
    }

    /**
     * Determine whether the user can update the given user's profile/role.
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('manage-users');
    }

    /**
     * Determine whether the user can activate/deactivate the given user.
     *
     * Kept as a distinct method from `update`, even though the permission
     * check is identical today — this is the seam Step 4's "deactivate
     * over delete" rule lives at, so a future business rule change (e.g.
     * "Managers can deactivate but not edit profile details") only needs
     * to change this one method, not restructure anything.
     *
     * A user can never deactivate themselves — prevents the only
     * Administrator from accidentally locking themselves out.
     */
    public function toggleActive(User $user, User $model): bool
    {
        return $user->can('manage-users') && $user->isNot($model);
    }
}
