<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AdjustmentAppliesTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdjustmentReason extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'applies_to',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'applies_to' => AdjustmentAppliesTo::class,
            'is_active' => 'boolean',
        ];
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
