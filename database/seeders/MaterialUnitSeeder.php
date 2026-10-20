<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MaterialUnit;
use Illuminate\Database\Seeder;

class MaterialUnitSeeder extends Seeder
{
    /**
     * @var list<string>
     */
    private const UNITS = ['pc', 'sheet', 'roll', 'ml', 'g', 'kg', 'box', 'pack', 'meter', 'set'];

    /**
     * Run the database seeds.
     *
     * Idempotent via firstOrCreate — safe to re-run.
     */
    public function run(): void
    {
        foreach (self::UNITS as $name) {
            MaterialUnit::firstOrCreate(['name' => $name]);
        }
    }
}
