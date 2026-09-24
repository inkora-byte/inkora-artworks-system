<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates one login-able Administrator for local development and
     * demos. Guarded against production here directly, as well as by
     * DatabaseSeeder's environment check before this class is even
     * called — a seeder should never trust it's only ever invoked the
     * way its caller currently intends.
     *
     * Idempotent via firstOrCreate — safe to re-run.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@inkoraartworks.local'],
            [
                'name' => 'Administrator',
                'password' => 'password', // hashed automatically by User's 'hashed' cast (Milestone 0.3)
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['Administrator']);
    }
}
