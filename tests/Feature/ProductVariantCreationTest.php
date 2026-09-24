<?php

declare(strict_types=1);

use App\Enums\ProductStatus;
use App\Models\Design;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\Catalog\ProductService;

beforeEach(function () {
    $this->productType = ProductType::create([
        'name' => 'Magnetic Bookmark',
        'code' => 'MB',
        'is_active' => true,
    ]);
});

test('creating a product automatically creates exactly one default variant', function () {
    $service = app(ProductService::class);

    $product = $service->create([
        'name' => 'Sylveon Bookmark',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '150.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 5,
        'time_to_make_minutes' => 10,
    ]);

    expect($product->variants)->toHaveCount(1);

    $variant = $product->variants->first();

    expect($variant->is_default_variant)->toBeTrue()
        ->and($variant->variant_name)->toBeNull()
        ->and($variant->price)->toEqual('150.00')
        ->and($variant->status)->toBe(ProductStatus::Active);
});

test('the auto-created variant gets a correctly formatted, sequential SKU', function () {
    $service = app(ProductService::class);

    $productOne = $service->create([
        'name' => 'Sylveon Bookmark',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '150.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 0,
        'time_to_make_minutes' => null,
    ]);

    $productTwo = $service->create([
        'name' => 'Umbreon Bookmark',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '150.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 0,
        'time_to_make_minutes' => null,
    ]);

    expect($productOne->variants->first()->sku)->toBe('MB-0001')
        ->and($productTwo->variants->first()->sku)->toBe('MB-0002');
});

test('a product can never exist in the database without at least one variant', function () {
    $service = app(ProductService::class);

    $product = $service->create([
        'name' => 'Sylveon Bookmark',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '150.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 0,
        'time_to_make_minutes' => null,
    ]);

    // Re-fetch fresh from the database — not the in-memory object — to
    // prove the invariant actually holds in persisted data, not just in
    // what the Service happened to return.
    $freshProduct = Product::with('variants')->findOrFail($product->id);

    expect($freshProduct->variants)->not->toBeEmpty();
});

test('a product can optionally be linked to a design', function () {
    $design = Design::create([
        'name' => 'Sylveon Artwork',
        'status' => 'active',
    ]);

    $service = app(ProductService::class);

    $product = $service->create([
        'name' => 'Sylveon Sticker',
        'product_type_id' => $this->productType->id,
        'design_id' => $design->id,
        'description' => null,
        'price' => '50.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 0,
        'time_to_make_minutes' => null,
    ]);

    expect($product->design_id)->toBe($design->id)
        ->and($product->design->name)->toBe('Sylveon Artwork');
});

test('a product created without a design has a null design_id', function () {
    $service = app(ProductService::class);

    $product = $service->create([
        'name' => 'No Design Product',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '50.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 0,
        'time_to_make_minutes' => null,
    ]);

    expect($product->design_id)->toBeNull();
});

test('discontinuing a product also discontinues its default variant, and reactivating reverses it', function () {
    $service = app(ProductService::class);

    $product = $service->create([
        'name' => 'Sylveon Bookmark',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '150.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 0,
        'time_to_make_minutes' => null,
    ]);

    $service->toggleStatus($product);
    $product->refresh();

    expect($product->status)->toBe(ProductStatus::Discontinued)
        ->and($product->defaultVariant->status)->toBe(ProductStatus::Discontinued);

    $service->toggleStatus($product);
    $product->refresh();

    expect($product->status)->toBe(ProductStatus::Active)
        ->and($product->defaultVariant->status)->toBe(ProductStatus::Active);
});

test('updating a product changes its default variant without creating a second variant', function () {
    $service = app(ProductService::class);

    $product = $service->create([
        'name' => 'Sylveon Bookmark',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '150.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 0,
        'time_to_make_minutes' => null,
    ]);

    $service->update($product, [
        'name' => 'Sylveon Bookmark (Updated)',
        'product_type_id' => $this->productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '175.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 3,
        'time_to_make_minutes' => 15,
    ]);

    $product->refresh();

    expect($product->variants)->toHaveCount(1)
        ->and($product->defaultVariant->price)->toEqual('175.00')
        ->and($product->defaultVariant->minimum_stock)->toBe(3)
        ->and($product->defaultVariant->sku)->toBe('MB-0001'); // unchanged — SKU is immutable after creation
});
