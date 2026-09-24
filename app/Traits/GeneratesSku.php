<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait GeneratesSku
{
    /**
     * Generate the next sequential SKU for the given prefix code, in the
     * `{CODE}-{0001}` format matching the original workbook's Item ID
     * pattern (e.g. "MB-0001"). Shared across Services — used by
     * ProductService now, and by a future MaterialService (Sprint 3) —
     * one implementation, not duplicated per module.
     *
     * `lockForUpdate()` inside a transaction prevents a race condition
     * where two near-simultaneous creates could generate the same
     * sequence number.
     *
     * Note: zero-padded to 4 digits, matching the workbook's convention.
     * String comparison for ordering works correctly up to 9999 items
     * per prefix — a limit far beyond Phase 1's realistic catalog size,
     * not worth over-engineering around now.
     */
    public static function generateSku(string $table, string $prefix): string
    {
        return DB::transaction(function () use ($table, $prefix) {
            $lastSku = DB::table($table)
                ->where('sku', 'like', "{$prefix}-%")
                ->lockForUpdate()
                ->orderByDesc('sku')
                ->value('sku');

            $nextSequence = 1;

            if ($lastSku !== null) {
                $lastSequence = (int) substr($lastSku, strrpos($lastSku, '-') + 1);
                $nextSequence = $lastSequence + 1;
            }

            return sprintf('%s-%04d', $prefix, $nextSequence);
        });
    }
}
