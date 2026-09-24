<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired only on the transition INTO a low-stock state — not repeatedly
 * while an item stays low (Step 4's explicit anti-spam rule). No
 * listener until Sprint 11 — see StockLevelChanged's note for why that's
 * intentional, not an oversight.
 */
class StockLevelCrossedLowThreshold
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Model $stockable,
    ) {
    }
}
