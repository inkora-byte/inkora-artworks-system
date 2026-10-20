<?php

declare(strict_types=1);

namespace App\Http\Controllers\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialUnit;
use App\Services\Materials\MaterialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function __construct(
        private readonly MaterialService $materialService,
    ) {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Material::class);

        $materials = Material::with(['materialCategory', 'materialUnit'])
            ->orderBy('name')
            ->get();

        return view('materials.index', compact('materials'));
    }

    public function create(): View
    {
        $this->authorize('create', Material::class);

        $categories = MaterialCategory::where('is_active', true)->orderBy('name')->get();
        $units = MaterialUnit::orderBy('name')->get();

        return view('materials.create', compact('categories', 'units'));
    }

    public function store(StoreMaterialRequest $request): RedirectResponse
    {
        $this->materialService->create($request->validated());

        return redirect()->route('materials.index')->with('status', 'Material created successfully.');
    }

    public function edit(Material $material): View
    {
        $this->authorize('update', $material);

        $categories = MaterialCategory::where('is_active', true)->orderBy('name')->get();
        $units = MaterialUnit::orderBy('name')->get();

        return view('materials.edit', compact('material', 'categories', 'units'));
    }

    public function update(UpdateMaterialRequest $request, Material $material): RedirectResponse
    {
        $this->materialService->update($material, $request->validated());

        return redirect()->route('materials.index')->with('status', 'Material updated successfully.');
    }

    public function toggleStatus(Material $material): RedirectResponse
    {
        $this->authorize('toggleStatus', $material);

        $updated = $this->materialService->toggleStatus($material);

        return redirect()->route('materials.index')
            ->with('status', "Material marked {$updated->status->label()}.");
    }
}
