<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Material;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Material::class);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'material_category_id' => ['required', 'integer', 'exists:material_categories,id'],
            'material_unit_id' => ['required', 'integer', 'exists:material_units,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'minimum_stock' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
