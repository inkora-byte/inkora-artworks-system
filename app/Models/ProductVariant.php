<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FulfillmentMethod;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * Note: `cost_cache`, `cost_calculated_at`, and `current_stock_cache`
     * are deliberately NOT listed — these are system-managed cache
     * columns (Step 3), written only by the Costing Engine (Sprint 5+)
     * and Inventory/Stock's movement service, never by a staff-facing
     * form.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'sku',
        'external_sku',
        'variant_name',
        'is_default_variant',
        'price',
        'fulfillment_method',
        'minimum_stock',
        'time_to_make_minutes',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default_variant' => 'boolean',
            'price' => 'decimal:2',
            'fulfillment_method' => FulfillmentMethod::class,
            'cost_cache' => 'decimal:4',
            'cost_calculated_at' => 'datetime',
            'current_stock_cache' => 'integer',
            'minimum_stock' => 'integer',
            'time_to_make_minutes' => 'integer',
            'status' => ProductStatus::class,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Whether this variant needs restocking — matches the workbook's
     * Need Restock calculation.
     */
    public function needsRestock(): bool
    {
        return $this->status === ProductStatus::Active
            && $this->current_stock_cache <= $this->minimum_stock;
    }
}
