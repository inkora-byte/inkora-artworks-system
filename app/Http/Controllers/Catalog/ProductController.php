<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Design;
use App\Models\Product;
use App\Models\ProductType;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Catalog\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    /**
     * Display a listing of products.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->productRepository->allWithRelations();

        return view('catalog.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $this->authorize('create', Product::class);

        $productTypes = ProductType::where('is_active', true)->orderBy('name')->get();
        $designs = Design::where('status', 'active')->orderBy('name')->get();

        return view('catalog.products.create', compact('productTypes', 'designs'));
    }

    /**
     * Store a newly created product, its default variant, and any
     * uploaded photos.
     *
     * Uses validated() + unset(), not safe()->except(), for the data
     * passed to ProductService — ProductService::create() has a strict
     * `array` type-hint, and safe()->except() returns a ValidatedInput
     * object in some Laravel versions, not a plain array, which would
     * be a real TypeError here. validated() is reliably a plain array.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photos']);

        $product = $this->productService->create($data);

        foreach ($request->file('photos', []) as $photo) {
            $product->addMedia($photo)->toMediaCollection('gallery');
        }

        return redirect()->route('catalog.products.index')->with('status', 'Product created successfully.');
    }

    /**
     * Show the form for editing an existing product.
     */
    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        $product->load('defaultVariant');

        $productTypes = ProductType::where('is_active', true)->orderBy('name')->get();
        $designs = Design::where('status', 'active')->orderBy('name')->get();

        return view('catalog.products.edit', compact('product', 'productTypes', 'designs'));
    }

    /**
     * Update the specified product, its default variant, and attach any
     * newly uploaded photos (additive, not a replacement — no removal
     * UI in this milestone, same scope decision as Design's source files).
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photos']);

        $this->productService->update($product, $data);

        foreach ($request->file('photos', []) as $photo) {
            $product->addMedia($photo)->toMediaCollection('gallery');
        }

        return redirect()->route('catalog.products.index')->with('status', 'Product updated successfully.');
    }

    /**
     * Toggle the specified product's active/discontinued status.
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        $this->authorize('toggleStatus', $product);

        $updated = $this->productService->toggleStatus($product);

        return redirect()->route('catalog.products.index')
            ->with('status', "Product marked {$updated->status->label()}.");
    }
}
