<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\StockMovement;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockAdjustmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', StockMovement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Reason is required — no silent adjustments (Step 4's explicit
     * rule). Quantity must be non-zero; negative-goes-below-zero is
     * enforced by StockMovementService itself, not duplicated here.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity_change' => ['required', 'numeric', 'not_in:0'],
            'adjustment_reason_id' => ['required', 'integer', 'exists:adjustment_reasons,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
