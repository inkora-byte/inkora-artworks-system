<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    /**
     * StockMovement is append-only (Step 4's explicit rule: no UI ever
     * edits or deletes a stock_movements row — corrections happen by
     * adding a new movement, never altering history). $fillable still
     * lists every writable column since creation happens exclusively
     * through StockMovementService, never raw mass assignment from a
     * form.
     *
     * @var list<string>
     */
    protected $fillable = [
        'stockable_type',
        'stockable_id',
        'type',
        'quantity_change',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'adjustment_reason_id',
        'notes',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
            'quantity_change' => 'decimal:2',
            'quantity_before' => 'decimal:2',
            'quantity_after' => 'decimal:2',
        ];
    }

    /**
     * The item this movement affected — polymorphic. ProductVariant for
     * now; Material joins in Sprint 3 with zero changes here, which is
     * the entire point of this ledger design.
     */
    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * What caused this movement — polymorphic (an Order, ProductionOrder,
     * PurchaseOrder, etc. in later sprints). Null for manual adjustments.
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function adjustmentReason(): BelongsTo
    {
        return $this->belongsTo(AdjustmentReason::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
