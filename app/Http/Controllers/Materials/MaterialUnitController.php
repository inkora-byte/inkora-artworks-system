<?php

declare(strict_types=1);

namespace App\Http\Controllers\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialUnitRequest;
use App\Http\Requests\UpdateMaterialUnitRequest;
use App\Models\MaterialUnit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MaterialUnitController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', MaterialUnit::class);

        $units = MaterialUnit::withCount('materials')
            ->orderBy('name')
            ->paginate(20);

        return view('materials.units.index', compact('units'));
    }

    public function create(): View
    {
        $this->authorize('create', MaterialUnit::class);

        return view('materials.units.create');
    }

    public function store(StoreMaterialUnitRequest $request): RedirectResponse
    {
        MaterialUnit::create($request->validated());

        return redirect()
            ->route('units.index')
            ->with('success', 'Material unit created successfully.');
    }

    public function edit(MaterialUnit $unit): View
    {
        $this->authorize('update', $unit);

        return view('materials.units.edit', ['unit' => $unit]);
    }

    public function update(UpdateMaterialUnitRequest $request, MaterialUnit $unit): RedirectResponse
    {
        $unit->update($request->validated());

        return redirect()
            ->route('units.index')
            ->with('success', 'Material unit updated successfully.');
    }
}
