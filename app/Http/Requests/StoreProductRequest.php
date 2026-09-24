<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Product::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Covers the Product, its default variant, and any uploaded photos
     * together — all handled within one form (Milestone 1.2/1.3).
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'product_type_id' => ['required', 'integer', 'exists:product_types,id'],
            'design_id' => ['nullable', 'integer', 'exists:designs,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'fulfillment_method' => ['required', 'string', 'in:in_house,outsourced'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'time_to_make_minutes' => ['nullable', 'integer', 'min:0'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'max:10240', 'mimes:png,jpg,jpeg,webp'],
        ];
    }
}
