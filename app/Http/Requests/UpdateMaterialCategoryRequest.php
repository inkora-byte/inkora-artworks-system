<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaterialCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('category'));
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('material_categories', 'name')->ignore($categoryId)],
            'code' => ['required', 'string', 'max:10', Rule::unique('material_categories', 'code')->ignore($categoryId)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper((string) $this->input('code')),
        ]);
    }
}
