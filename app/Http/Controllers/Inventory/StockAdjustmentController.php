<?php

declare(strict_types=1);

namespace App\Http\Controllers\Inventory;

use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Models\AdjustmentReason;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Services\Inventory\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use InvalidArgumentException;

class StockAdjustmentController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService,
    ) {
    }

    /**
     * Display the Stock Levels overview — every active product variant,
     * current stock, minimum stock, and a quick low-stock flag.
     */
    public function index(): View
    {
        $this->authorize('viewAny', StockMovement::class);

        $variants = ProductVariant::with('product')
            ->where('status', 'active')
            ->orderBy('current_stock_cache')
            ->get();

        return view('inventory.stock.index', compact('variants'));
    }

    /**
     * Show the form for adjusting a specific variant's stock.
     */
    public function create(ProductVariant $productVariant): View
    {
        $this->authorize('create', StockMovement::class);

        $reasons = AdjustmentReason::where('is_active', true)
            ->whereIn('applies_to', ['product', 'both'])
            ->orderBy('name')
            ->get();

        return view('inventory.adjustments.create', compact('productVariant', 'reasons'));
    }

    /**
     * Store a new stock adjustment.
     *
     * Both `quantity_change` and `adjustment_reason_id` are explicitly
     * cast — HTML form fields always arrive as strings regardless of
     * the FormRequest's `integer`/`numeric` validation rules (those
     * rules check the string's *content*, they don't change its PHP
     * type), and StockMovementService::record() strictly type-hints
     * `float` and `?int` respectively.
     *
     * StockMovementService::record() also throws InvalidArgumentException
     * if the adjustment would take stock negative — caught here and
     * translated into a normal form validation error, per Step 7's
     * Error Handling standard.
     */
    public function store(StoreStockAdjustmentRequest $request): RedirectResponse
    {
        $variant = ProductVariant::findOrFail((int) $request->validated('product_variant_id'));

        try {
            $this->stockMovementService->record(
                stockable: $variant,
                quantityChange: (float) $request->validated('quantity_change'),
                type: StockMovementType::Adjustment,
                adjustmentReasonId: (int) $request->validated('adjustment_reason_id'),
                notes: $request->validated('notes'),
            );
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['quantity_change' => $e->getMessage()]);
        }

        return redirect()->route('inventory.stock.index')->with('status', 'Stock adjustment recorded successfully.');
    }
}
