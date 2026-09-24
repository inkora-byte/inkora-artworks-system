<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use App\Models\Design;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DesignController extends Controller
{
    /**
     * Display a listing of designs.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Design::class);

        $designs = Design::with('media')->orderBy('name')->get();

        return view('catalog.designs.index', compact('designs'));
    }

    /**
     * Show the form for creating a new design.
     */
    public function create(): View
    {
        $this->authorize('create', Design::class);

        return view('catalog.designs.create');
    }

    /**
     * Store a newly created design, attaching any uploaded source files.
     */
    public function store(StoreDesignRequest $request): RedirectResponse
    {
        $design = Design::create([
            ...$request->safe()->except('source_files'),
            'created_by' => Auth::id(),
        ]);

        foreach ($request->file('source_files', []) as $file) {
            $design->addMedia($file)->toMediaCollection('source_files');
        }

        return redirect()->route('catalog.designs.index')->with('status', 'Design created successfully.');
    }

    /**
     * Show the form for editing an existing design.
     */
    public function edit(Design $design): View
    {
        $this->authorize('update', $design);

        return view('catalog.designs.edit', compact('design'));
    }

    /**
     * Update the specified design, attaching any newly uploaded source
     * files alongside whatever was already there (uploads are additive,
     * not a replacement — no removal UI in this milestone).
     */
    public function update(UpdateDesignRequest $request, Design $design): RedirectResponse
    {
        $design->update($request->safe()->except('source_files'));

        foreach ($request->file('source_files', []) as $file) {
            $design->addMedia($file)->toMediaCollection('source_files');
        }

        return redirect()->route('catalog.designs.index')->with('status', 'Design updated successfully.');
    }
}
