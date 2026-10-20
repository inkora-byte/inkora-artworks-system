<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Orchestrates every module's baseline seeder, in dependency order —
     * this is the single entry point `php artisan migrate:fresh --seed`
     * calls, and the pattern every later sprint's own seeder extends:
     * add a new `::class` line here, positioned after anything it
     * depends on. See README.md's "Seeder Convention" section.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            SettingSeeder::class,
            AdjustmentReasonSeeder::class,
            MaterialCategorySeeder::class,
            MaterialUnitSeeder::class,
        ]);

        // Local-only sample data — never runs in production, regardless
        // of whether this method is ever accidentally invoked there.
        // DevelopmentSeeder guards itself identically as a second layer.
        if (! app()->environment('production')) {
            $this->call(DevelopmentSeeder::class);
        }
    }
}
