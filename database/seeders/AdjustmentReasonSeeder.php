<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AdjustmentReason;
use Illuminate\Database\Seeder;

class AdjustmentReasonSeeder extends Seeder
{
    /**
     * Matches the original Inkora Artworks workbook's Material
     * Adjustment Reasons list, with `applies_to` corrected per-reason
     * (not everything is genuinely `both`) and one new reason —
     * `Initial Stock / Opening Balance` — added for products
     * specifically.
     *
     * `Initial Stock / Opening Balance` exists because Adjustment is
     * currently the *only* way to put stock on a newly created product
     * — Restock (Purchasing, Sprint 4) and real Production output
     * (Sprint 5) don't exist yet. Once they ship, this reason becomes
     * unnecessary for new products, but existing adjustment history
     * using it stays meaningful — it's not removed just because a
     * better path exists later, since the adjustments already recorded
     * with it are accurate history, not a mistake to erase.
     *
     * @var array<int, array{name: string, applies_to: string}>
     */
    private const REASONS = [
        ['name' => 'Initial Stock / Opening Balance', 'applies_to' => 'product'],
        ['name' => 'Test Print / Sample', 'applies_to' => 'material'],
        ['name' => 'Damaged / Wasted', 'applies_to' => 'both'],
        ['name' => 'Given Away', 'applies_to' => 'both'],
        ['name' => 'Personal Use', 'applies_to' => 'both'],
        ['name' => 'Other', 'applies_to' => 'both'],
    ];

    /**
     * Run the database seeds.
     *
     * Idempotent via updateOrCreate — safe to re-run, and correctly
     * fixes `applies_to` on rows seeded by an earlier, less accurate
     * version of this seeder rather than leaving them stale.
     */
    public function run(): void
    {
        foreach (self::REASONS as $reason) {
            AdjustmentReason::updateOrCreate(
                ['name' => $reason['name']],
                ['applies_to' => $reason['applies_to'], 'is_active' => true]
            );
        }
    }
}
