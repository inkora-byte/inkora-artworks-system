<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MaterialStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * Note: `unit_cost_cache` and `current_stock_cache` are deliberately
     * NOT listed — both are system-managed. `unit_cost_cache` is written
     * by Purchasing on delivery (Sprint 4), never a staff-facing edit
     * form. `current_stock_cache` is written by StockMovementService via
     * forceFill(), the exact same pattern established for ProductVariant
     * back in Milestone 2.1's bug fix — the reason that bug fix mattered
     * beyond just Products.
     *
     * @var list<string>
     */
    protected $fillable = [
        'material_category_id',
        'material_unit_id',
        'name',
        'brand',
        'sku',
        'minimum_stock',
        'preferred_supplier_id',
        'notes',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_cost_cache' => 'decimal:4',
            'current_stock_cache' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
            'status' => MaterialStatus::class,
        ];
    }

    public function materialCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    public function materialUnit(): BelongsTo
    {
        return $this->belongsTo(MaterialUnit::class);
    }

    // preferredSupplier() relationship is added in Sprint 4, once the
    // Supplier model exists — same deferred-relationship pattern used
    // for Product/ProductType/Design across Sprints 1-2.

    /**
     * Whether this material needs restocking.
     */
    public function needsRestock(): bool
    {
        return $this->status === MaterialStatus::Active
            && $this->current_stock_cache <= $this->minimum_stock;
    }
}
