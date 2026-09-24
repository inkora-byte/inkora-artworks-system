<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Create a new staff user with the given role.
     *
     * Password hashing is handled automatically by the User model's
     * 'hashed' cast (Milestone 0.3) — no manual Hash::make() needed here.
     *
     * @param array{name: string, email: string, password: string, phone: ?string, role: string} $data
     */
    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'is_active' => true,
        ]);

        $user->syncRoles([$data['role']]);

        return $user;
    }

    /**
     * Update an existing staff user's profile and role.
     *
     * Password is only changed if a new one was provided — an empty
     * password field means "keep the current one," never "clear it."
     *
     * @param array{name: string, email: string, phone: ?string, password: ?string, role: string} $data
     */
    public function update(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
        $user->syncRoles([$data['role']]);

        return $user;
    }

    /**
     * Toggle a user's active status — deactivation, not deletion, per
     * Step 4's business rule. Preserves every historical `created_by`
     * attribution across the system; only blocks future login and force-
     * logs-out an active session (Milestone 0.4's EnsureUserIsActive
     * middleware enforces that side, unchanged here).
     */
    public function toggleActive(User $user): User
    {
        $user->update(['is_active' => ! $user->is_active]);

        return $user;
    }
}
