<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * The full permission list, per the Master Software Design Document,
     * Section 9 (Security).
     *
     * @var list<string>
     */
    private const PERMISSIONS = [
        'manage-catalog',
        'manage-inventory',
        'manage-materials',
        'manage-purchasing',
        'manage-production',
        'manage-sales',
        'manage-custom-orders',
        'manage-customers',
        'manage-expenses',
        'view-reports',
        'view-financials',
        'manage-users',
        'manage-settings',
    ];

    /**
     * Permissions withheld from the Manager role — everything except
     * user management, per the Master Design Document.
     *
     * @var list<string>
     */
    private const MANAGER_EXCLUDED = [
        'manage-users',
    ];

    /**
     * Permissions withheld from the Staff role — operational access only,
     * excluding user management and financial visibility (Staff can
     * process an order without seeing its profit margin, per the Master
     * Design Document's Security section).
     *
     * @var list<string>
     */
    private const STAFF_EXCLUDED = [
        'manage-users',
        'view-financials',
        'manage-settings',
    ];

    private const KEEPER_EXCLUDED = [
        'manage-catalog',
        'manage-inventory',
        'manage-materials',
        'manage-purchasing',
        'manage-production',
        'manage-sales',
        'manage-custom-orders',
        'manage-customers',
        'manage-expenses',
        'manage-users',
        'manage-settings',
    ];


    /**
     * Run the database seeds.
     *
     * Idempotent by design (firstOrCreate + syncPermissions) — safe to run
     * multiple times without duplicate-key errors, whether via a fresh
     * `migrate:fresh --seed` or a standalone re-run of this seeder alone.
     */
    public function run(): void
    {
        // Permissions are created once, up front, so every role assignment
        // below references an already-existing record.
        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $administrator = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        $administrator->syncPermissions(self::PERMISSIONS);

        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions(array_values(array_diff(self::PERMISSIONS, self::MANAGER_EXCLUDED)));

        $staff = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $staff->syncPermissions(array_values(array_diff(self::PERMISSIONS, self::STAFF_EXCLUDED)));

        $reporter = Role::firstOrCreate(['name' => 'Reporter', 'guard_name' => 'web']);
        $reporter->syncPermissions(array_values(array_diff(self::PERMISSIONS, self::KEEPER_EXCLUDED)));
    }
}
