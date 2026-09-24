<?php

declare(strict_types=1);

use App\Enums\StockMovementType;
use App\Events\StockLevelCrossedLowThreshold;
use App\Models\MaterialCategory;
use App\Models\MaterialUnit;
use App\Services\Inventory\StockMovementService;
use App\Services\Materials\MaterialService;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $category = MaterialCategory::create([
        'name' => 'Sticker Paper',
        'code' => 'SPR',
        'is_active' => true,
    ]);

    $unit = MaterialUnit::create(['name' => 'sheet']);

    // minimum_stock = 10, current_stock_cache starts at 0 by default —
    // already "low" from creation, same as ProductVariant's tests.
    $this->material = app(MaterialService::class)->create([
        'name' => 'Glossy A4 Sticker Paper',
        'material_category_id' => $category->id,
        'material_unit_id' => $unit->id,
        'brand' => null,
        'minimum_stock' => 10,
        'notes' => null,
    ]);
});

test('material creation generates a correctly formatted, sequential SKU', function () {
    expect($this->material->sku)->toBe('SPR-0001');
});

test('the stockable_type is correctly recorded using the material morph alias', function () {
    $service = app(StockMovementService::class);

    $movement = $service->record($this->material, 20, StockMovementType::Restock);

    expect($movement->stockable_type)->toBe('material');
});

test('StockMovementService works against Material with zero code changes to the service itself', function () {
    // This is the actual proof of Sprint 2's polymorphic ledger design —
    // the exact same StockMovementService, completely unmodified since
    // Milestone 2.1, now operating on a second, entirely different
    // entity type. If this test passes, the whole "Material joins with
    // zero changes" claim made throughout Steps 3-6 is genuinely true,
    // not just a nice idea on paper.
    $service = app(StockMovementService::class);

    $service->record(
        stockable: $this->material,
        quantityChange: 50.5,
        type: StockMovementType::Restock,
    );

    expect((float) $this->material->fresh()->current_stock_cache)->toBe(50.5);
});

test('materials correctly support fractional stock quantities, unlike whole-unit ProductVariants', function () {
    $service = app(StockMovementService::class);

    $service->record($this->material, 100.25, StockMovementType::Restock);
    $service->record($this->material, -30.75, StockMovementType::Production);

    expect((float) $this->material->fresh()->current_stock_cache)->toBe(69.5);
});

test('low-stock detection works identically for Material as it does for ProductVariant', function () {
    Event::fake([StockLevelCrossedLowThreshold::class]);

    $service = app(StockMovementService::class);

    // 0 -> 50 is a recovery out of low stock (already low at minimum_stock
    // = 10, current = 0) — no event expected.
    $service->record($this->material, 50, StockMovementType::Restock);
    Event::assertNotDispatched(StockLevelCrossedLowThreshold::class);

    // 50 -> 5 genuinely crosses into low stock (5 <= 10) — event expected.
    $service->record($this->material, -45, StockMovementType::Production);
    Event::assertDispatched(StockLevelCrossedLowThreshold::class, 1);
});

test('needsRestock() correctly reflects Material stock state', function () {
    expect($this->material->needsRestock())->toBeTrue(); // starts at 0, minimum 10

    app(StockMovementService::class)->record($this->material, 50, StockMovementType::Restock);

    expect($this->material->fresh()->needsRestock())->toBeFalse();
});
