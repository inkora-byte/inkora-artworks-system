<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaterialUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('unit'));
    }

    public function rules(): array
    {
        $unitId = $this->route('unit')->id;

        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('material_units', 'name')->ignore($unitId)],
        ];
    }
}
