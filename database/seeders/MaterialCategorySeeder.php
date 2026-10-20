<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MaterialCategory;
use Illuminate\Database\Seeder;

class MaterialCategorySeeder extends Seeder
{
    /**
     * Matches the original Inkora Artworks workbook's Material Category
     * list. No dedicated CRUD screen exists for these yet (Milestone
     * 3.2's scope decision) — this seeder is how the initial set gets
     * created; adding a new category later is a small future addition.
     *
     * @var array<int, array{name: string, code: string}>
     */
    private const CATEGORIES = [
        ['name' => 'Sticker Paper', 'code' => 'SPR'],
        ['name' => 'Packaging', 'code' => 'PKG'],
        ['name' => 'Ink', 'code' => 'INK'],
        ['name' => 'Hardware', 'code' => 'HDW'],
        ['name' => 'Equipment', 'code' => 'EQP'],
    ];

    /**
     * Run the database seeds.
     *
     * Idempotent via firstOrCreate — safe to re-run.
     */
    public function run(): void
    {
        foreach (self::CATEGORIES as $category) {
            MaterialCategory::firstOrCreate(
                ['code' => $category['code']],
                ['name' => $category['name'], 'is_active' => true]
            );
        }
    }
}
