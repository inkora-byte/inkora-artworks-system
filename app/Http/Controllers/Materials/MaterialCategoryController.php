<?php

declare(strict_types=1);

namespace App\Http\Controllers\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialCategoryRequest;
use App\Http\Requests\UpdateMaterialCategoryRequest;
use App\Models\MaterialCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MaterialCategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', MaterialCategory::class);

        $categories = MaterialCategory::withCount('materials')
            ->orderBy('name')
            ->paginate(20);

        return view('materials.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', MaterialCategory::class);

        return view('materials.categories.create');
    }

    public function store(StoreMaterialCategoryRequest $request): RedirectResponse
    {
        MaterialCategory::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Material category created successfully.');
    }

    public function edit(MaterialCategory $category): View
    {
        $this->authorize('update', $category);

        return view('materials.categories.edit', ['category' => $category]);
    }

    public function update(UpdateMaterialCategoryRequest $request, MaterialCategory $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Material category updated successfully.');
    }
}
