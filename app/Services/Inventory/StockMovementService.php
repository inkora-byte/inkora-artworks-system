<?php

declare(strict_types=1);

namespace App\Services\Inventory;

use App\Enums\StockMovementType;
use App\Events\StockLevelChanged;
use App\Events\StockLevelCrossedLowThreshold;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockMovementService
{
    /**
     * Record a stock movement for the given stockable item, updating its
     * current_stock_cache atomically in the same transaction.
     *
     * This is the single write-path every module that touches stock
     * goes through — Purchasing, Production, Sales, and manual
     * Adjustments all call this same method, never touching
     * `current_stock_cache` directly. That is the entire point of the
     * cache/ledger split from Step 3: `stock_movements` is the source of
     * truth, the cache is a derived convenience, and this method is the
     * only place they are allowed to change, together, atomically.
     *
     * Quantities are handled as floats throughout — deliberately, not
     * cast to int — so this Service works unchanged for both
     * `product_variants` (integer stock) now and `materials` (decimal
     * stock, e.g. 2.5 meters of ribbon) once Sprint 3 introduces it.
     * Eloquent's own attribute casts on each model coerce the value
     * correctly on save.
     *
     * @param  Model  $stockable  Must have `current_stock_cache` and
     *                            `minimum_stock` attributes.
     * @param  float  $quantityChange  Signed — positive increases stock,
     *                                 negative decreases it.
     * @param  Model|null  $reference  What caused this movement (an
     *                                 Order, ProductionOrder, etc. in
     *                                 later sprints) — null for manual
     *                                 adjustments.
     * @param  int|null  $adjustmentReasonId  Required when $type is
     *                                        Adjustment (Step 4's
     *                                        validation rule) —
     *                                        enforced here, not only at
     *                                        the form layer, since this
     *                                        Service is the actual
     *                                        source of truth.
     *
     * @throws InvalidArgumentException if the movement would take stock
     *                                  negative, or if an Adjustment is
     *                                  attempted without a reason.
     */
    public function record(
        Model $stockable,
        float $quantityChange,
        StockMovementType $type,
        ?Model $reference = null,
        ?int $adjustmentReasonId = null,
        ?string $notes = null,
    ): StockMovement {
        if ($type === StockMovementType::Adjustment && $adjustmentReasonId === null) {
            throw new InvalidArgumentException(
                'An adjustment reason is required for adjustment-type stock movements.'
            );
        }

        return DB::transaction(function () use ($stockable, $quantityChange, $type, $reference, $adjustmentReasonId, $notes) {
            // Lock the row for the duration of this transaction — two
            // near-simultaneous movements against the same item (e.g.
            // two orders selling the last unit at once) must not both
            // read the same "before" quantity.
            /** @var Model $stockable */
            $stockable = $stockable->newQuery()->lockForUpdate()->findOrFail($stockable->getKey());

            $quantityBefore = (float) $stockable->current_stock_cache;
            $quantityAfter = $quantityBefore + $quantityChange;

            if ($quantityAfter < 0) {
                throw new InvalidArgumentException(
                    "This movement would take stock negative ({$quantityBefore} -> {$quantityAfter})."
                );
            }

            $minimumStock = (float) $stockable->minimum_stock;
            $wasLow = $this->isLowStock($quantityBefore, $minimumStock);

            $movement = StockMovement::create([
                'stockable_type' => $stockable->getMorphClass(),
                'stockable_id' => $stockable->getKey(),
                'type' => $type->value,
                'quantity_change' => $quantityChange,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'adjustment_reason_id' => $adjustmentReasonId,
                'notes' => $notes,
                'created_by' => Auth::id(),
            ]);

            // forceFill(), not update() — current_stock_cache is
            // deliberately excluded from ProductVariant's $fillable
            // (Milestone 1.2, so no form can ever set it directly), but
            // that same protection means a plain update() here would
            // silently no-op instead of erroring, since Eloquent mass
            // assignment just drops non-fillable fields by default
            // rather than throwing. forceFill() bypasses that
            // protection for this one, legitimate, system-level write —
            // the only place in the codebase allowed to touch this
            // column directly.
            $stockable->forceFill(['current_stock_cache' => $quantityAfter])->save();

            event(new StockLevelChanged($movement));

            $isLowNow = $this->isLowStock($quantityAfter, $minimumStock);

            // Fire only on the transition INTO a low-stock state — not
            // on every movement while it stays low (Step 4's explicit
            // anti-spam rule).
            if (! $wasLow && $isLowNow) {
                event(new StockLevelCrossedLowThreshold($stockable));
            }

            return $movement;
        });
    }

    /**
     * Whether a given stock level counts as "low" against a minimum.
     */
    private function isLowStock(float $currentStock, float $minimumStock): bool
    {
        return $currentStock <= $minimumStock;
    }
}
