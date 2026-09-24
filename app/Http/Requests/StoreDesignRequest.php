<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Design;
use Illuminate\Foundation\Http\FormRequest;

class StoreDesignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Design::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Note on `mimes:ai,psd,...`: Laravel's `mimes` rule validates the
     * file's actual detected MIME type against its extension, not just
     * the extension string. PNG/JPG/PDF/SVG are unambiguous. AI and PSD
     * are worth testing for real once you have actual sample files —
     * Adobe Illustrator files are internally PDF-based and can sometimes
     * report as `application/pdf`, which may or may not match Laravel's
     * extension map cleanly. Flagging this now rather than asserting
     * confidence I don't have; if real .ai uploads get rejected
     * unexpectedly, the fix is switching to an explicit `mimetypes` rule.
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
