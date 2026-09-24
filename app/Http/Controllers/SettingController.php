<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService,
    ) {
    }

    /**
     * Display all settings, grouped.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Setting::class);

        $settings = Setting::orderBy('group')->orderBy('key')->get();

        return view('settings.index', compact('settings'));
    }

    /**
     * Update a single setting's value.
     *
     * The set of settings is fixed by the architecture (Step 3) — only
     * values are staff-editable, never the key/type/group, which is why
     * this only ever updates an existing Setting, never creates one from
     * user input.
     */
    public function update(UpdateSettingRequest $request, Setting $setting): RedirectResponse
    {
        $this->settingService->set(
            $setting->key,
            $request->validated('value'),
            $setting->type,
            $setting->group,
        );

        return redirect()->route('settings.index')->with('status', 'Setting updated successfully.');
    }
}
