<?php

declare(strict_types=1);

use App\Enums\StockMovementType;
use App\Events\StockLevelChanged;
use App\Events\StockLevelCrossedLowThreshold;
use App\Models\AdjustmentReason;
use App\Models\ProductType;
use App\Models\StockMovement;
use App\Services\Catalog\ProductService;
use App\Services\Inventory\StockMovementService;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $productType = ProductType::create([
        'name' => 'Magnetic Bookmark',
        'code' => 'MB',
        'is_active' => true,
    ]);

    // minimum_stock = 5, current_stock_cache starts at 0 by default
    // (Step 3) — meaning this variant is already "low" from creation,
    // which the low-stock transition tests below rely on deliberately.
    $product = app(ProductService::class)->create([
        'name' => 'Sylveon Bookmark',
        'product_type_id' => $productType->id,
        'design_id' => null,
        'description' => null,
        'price' => '150.00',
        'fulfillment_method' => 'in_house',
        'minimum_stock' => 5,
        'time_to_make_minutes' => null,
    ]);

    $this->variant = $product->variants->first();
});

test('recording a positive movement increases current_stock_cache', function () {
    $service = app(StockMovementService::class);

    $service->record(
        stockable: $this->variant,
        quantityChange: 20,
        type: StockMovementType::Restock,
    );

    expect($this->variant->fresh()->current_stock_cache)->toBe(20);
});

test('recording a negative movement decreases current_stock_cache', function () {
    $service = app(StockMovementService::class);

    $service->record($this->variant, 20, StockMovementType::Restock);
    $service->record($this->variant, -5, StockMovementType::Sale);

    expect($this->variant->fresh()->current_stock_cache)->toBe(15);
});

test('quantity_before and quantity_after are captured correctly on the ledger row', function () {
    $service = app(StockMovementService::class);

    $service->record($this->variant, 20, StockMovementType::Restock);
    $movement = $service->record($this->variant, -8, StockMovementType::Sale);

    expect((float) $movement->quantity_before)->toBe(20.0)
        ->and((float) $movement->quantity_after)->toBe(12.0)
        ->and((float) $movement->quantity_change)->toBe(-8.0);
});

test('a movement that would take stock negative is rejected, with no partial write', function () {
    $service = app(StockMovementService::class);

    $service->record($this->variant, 5, StockMovementType::Restock);

    expect(fn () => $service->record($this->variant, -10, StockMovementType::Sale))
        ->toThrow(InvalidArgumentException::class);

    // Confirm the transaction genuinely rolled back — stock is
    // unchanged, and no movement row exists for the rejected attempt.
    expect($this->variant->fresh()->current_stock_cache)->toBe(5)
        ->and(StockMovement::where('stockable_id', $this->variant->id)->count())->toBe(1);
});

test('an adjustment movement requires a reason, enforced by the service itself', function () {
    $service = app(StockMovementService::class);

    expect(fn () => $service->record(
        stockable: $this->variant,
        quantityChange: 3,
        type: StockMovementType::Adjustment,
        adjustmentReasonId: null,
    ))->toThrow(InvalidArgumentException::class);
});

test('an adjustment movement with a valid reason succeeds and records it', function () {
    $reason = AdjustmentReason::create([
        'name' => 'Damaged / Wasted',
        'applies_to' => 'both',
        'is_active' => true,
    ]);

    $service = app(StockMovementService::class);

    // Restock first — the variant starts at 0, and a -2 adjustment
    // against 0 stock would correctly be rejected as "would go negative"
    // (that's a different, already-covered test). This test is about
    // confirming a VALID adjustment succeeds, so it needs real stock to
    // adjust downward from first.
    $service->record($this->variant, 10, StockMovementType::Restock);

    $movement = $service->record(
        stockable: $this->variant,
        quantityChange: -2,
        type: StockMovementType::Adjustment,
        adjustmentReasonId: $reason->id,
    );

    expect($movement->adjustment_reason_id)->toBe($reason->id)
        ->and($this->variant->fresh()->current_stock_cache)->toBe(8);
});

test('the StockLevelChanged event fires on every recorded movement', function () {
    Event::fake([StockLevelChanged::class]);

    $service = app(StockMovementService::class);

    $service->record($this->variant, 10, StockMovementType::Restock);
    $service->record($this->variant, -3, StockMovementType::Sale);

    Event::assertDispatched(StockLevelChanged::class, 2);
});

test('the low-stock event fires only on the transition into a low-stock state, not repeatedly', function () {
    Event::fake([StockLevelCrossedLowThreshold::class]);

    $service = app(StockMovementService::class);

    // Starting stock is 0, minimum_stock is 5 — already "low" from
    // creation, so this first movement is a RECOVERY out of low stock,
    // not an entry into it. No event expected.
    $service->record($this->variant, 20, StockMovementType::Restock); // 0 -> 20
    Event::assertNotDispatched(StockLevelCrossedLowThreshold::class);

    // 20 -> 2 genuinely crosses into low stock (2 <= 5). Event expected,
    // exactly once.
    $service->record($this->variant, -18, StockMovementType::Sale); // 20 -> 2
    Event::assertDispatched(StockLevelCrossedLowThreshold::class, 1);

    // 2 -> 1 stays low, but was ALREADY low before this movement — per
    // the anti-spam rule, no additional event should fire.
    $service->record($this->variant, -1, StockMovementType::Sale); // 2 -> 1
    Event::assertDispatched(StockLevelCrossedLowThreshold::class, 1); // still only 1 total
});
