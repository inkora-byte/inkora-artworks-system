<?php

declare(strict_types=1);

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Display stock movement history, optionally filtered to one item.
     *
     * Standard pagination for now, not server-side DataTables — Step 5
     * anticipated this table growing large enough to need AJAX-driven
     * server-side processing eventually, but there's zero real movement
     * data yet, so building that infrastructure now would be solving a
     * problem that doesn't exist yet. Straightforward to upgrade once
     * real volume justifies it.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', StockMovement::class);

        $query = StockMovement::with(['stockable', 'adjustmentReason', 'creator'])
            ->orderByDesc('created_at');

        if ($request->filled('product_variant_id')) {
            $query->where('stockable_type', 'product_variant')
                ->where('stockable_id', $request->integer('product_variant_id'));
        }

        $movements = $query->paginate(50)->withQueryString();

        $variants = ProductVariant::with('product')->orderBy('sku')->get();

        return view('inventory.movements.index', compact('movements', 'variants'));
    }
}
