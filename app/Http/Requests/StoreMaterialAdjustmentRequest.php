<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\StockMovement;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', StockMovement::class);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'material_id' => ['required', 'integer', 'exists:materials,id'],
            'quantity_change' => ['required', 'numeric', 'not_in:0'],
            'adjustment_reason_id' => ['required', 'integer', 'exists:adjustment_reasons,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
