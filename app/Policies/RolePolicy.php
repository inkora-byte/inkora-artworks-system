<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view the list of roles.
     *
     * Full role CRUD is deferred per the approved Sprint 0 scope reduction
     * — roles are seeded, not staff-managed — so this policy currently
     * gates only the read-only roles list (Milestone 0.8's `roles/index`).
     * Additional methods (create/update/delete) are added only if a real
     * business need for role-management UI emerges, per the AI
     * Development Contract's "avoid unnecessary complexity" rule.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-users');
    }
}
