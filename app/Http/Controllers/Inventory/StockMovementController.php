<?php

declare(strict_types=1);

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Display stock movement history, optionally filtered to one
     * product variant OR one material — one shared history view for
     * both entity types, matching the ledger's own polymorphic design
     * (Milestone 2.1/3.1). Standard pagination, not server-side
     * DataTables — see Milestone 2.2's note on why.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', StockMovement::class);

        $query = StockMovement::with(['stockable', 'adjustmentReason', 'creator'])
            ->orderByDesc('created_at');

        if ($request->filled('product_variant_id')) {
            $query->where('stockable_type', 'product_variant')
                ->where('stockable_id', $request->integer('product_variant_id'));
        } elseif ($request->filled('material_id')) {
            $query->where('stockable_type', 'material')
                ->where('stockable_id', $request->integer('material_id'));
        }

        $movements = $query->paginate(50)->withQueryString();

        $variants = ProductVariant::with('product')->orderBy('sku')->get();
        $materials = Material::orderBy('sku')->get();

        return view('inventory.movements.index', compact('movements', 'variants', 'materials'));
    }
}
