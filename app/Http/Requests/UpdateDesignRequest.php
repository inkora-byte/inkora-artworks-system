<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('design'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'artist_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,retired'],
            'source_files' => ['nullable', 'array'],
            'source_files.*' => ['file', 'max:51200', 'mimes:ai,psd,png,jpg,jpeg,pdf,svg'],
        ];
    }
}
