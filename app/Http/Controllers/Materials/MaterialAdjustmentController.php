<?php

declare(strict_types=1);

namespace App\Http\Controllers\Materials;

use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialAdjustmentRequest;
use App\Models\AdjustmentReason;
use App\Models\Material;
use App\Models\StockMovement;
use App\Services\Inventory\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use InvalidArgumentException;

class MaterialAdjustmentController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService,
    ) {
    }

    /**
     * Display the Materials Adjustments overview — the Materials-side
     * equivalent of Inventory's Stock Levels page (Milestone 2.2).
     */
    public function index(): View
    {
        $this->authorize('viewAny', StockMovement::class);

        $materials = Material::with(['materialCategory', 'materialUnit'])
            ->where('status', 'active')
            ->orderBy('current_stock_cache')
            ->get();

        return view('materials.adjustments.index', compact('materials'));
    }

    /**
     * Show the form for adjusting a specific material's stock.
     *
     * Reasons are filtered to `material`/`both` — this is exactly where
     * "Test Print / Sample" now correctly appears, having been fixed
     * back in the Sprint 2 seeder correction.
     */
    public function create(Material $material): View
    {
        $this->authorize('create', StockMovement::class);

        $reasons = AdjustmentReason::where('is_active', true)
            ->whereIn('applies_to', ['material', 'both'])
            ->orderBy('name')
            ->get();

        return view('materials.adjustments.create', compact('material', 'reasons'));
    }

    /**
     * Store a new material adjustment.
     *
     * Both `material_id` and `adjustment_reason_id` are explicitly cast
     * to int, and `quantity_change` to float — the exact same casting
     * discipline that was missed once already on the Product side
     * (Milestone 2.2's TypeError bug), applied correctly here from the
     * start.
     */
    public function store(StoreMaterialAdjustmentRequest $request): RedirectResponse
    {
        $material = Material::findOrFail((int) $request->validated('material_id'));

        try {
            $this->stockMovementService->record(
                stockable: $material,
                quantityChange: (float) $request->validated('quantity_change'),
                type: StockMovementType::Adjustment,
                adjustmentReasonId: (int) $request->validated('adjustment_reason_id'),
                notes: $request->validated('notes'),
            );
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['quantity_change' => $e->getMessage()]);
        }

        return redirect()->route('materials.adjustments.index')
            ->with('status', 'Material adjustment recorded successfully.');
    }
}
