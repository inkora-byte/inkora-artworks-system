<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ProductVariant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * "Materials Low in Stock" is real now (Milestone 3.2), joining
     * "Products Low in Stock" (Milestone 2.2). The Low Stock Top 10
     * widget stays Products-only for now — merging both entity types
     * into one ranked list is a reasonable future enhancement, better
     * suited to Reports (Sprint 10) than added piecemeal here.
     */
    public function index(): View
    {
        $lowStockProductsQuery = ProductVariant::with('product')
            ->where('status', 'active')
            ->whereColumn('current_stock_cache', '<=', 'minimum_stock');

        $lowStockProductsCount = (clone $lowStockProductsQuery)->count();
        $lowStockVariants = (clone $lowStockProductsQuery)->orderBy('current_stock_cache')->limit(10)->get();

        $lowStockMaterialsCount = Material::where('status', 'active')
            ->whereColumn('current_stock_cache', '<=', 'minimum_stock')
            ->count();

        $kpis = [
            ['label' => "Today's Sales", 'value' => '₱0.00', 'icon' => 'fa-peso-sign', 'color' => 'primary', 'financial' => true],
            ['label' => 'Monthly Revenue', 'value' => '₱0.00', 'icon' => 'fa-chart-line', 'color' => 'success', 'financial' => true],
            ['label' => 'Pending Orders', 'value' => '0', 'icon' => 'fa-receipt', 'color' => 'warning', 'financial' => false],
            ['label' => 'Products Low in Stock', 'value' => (string) $lowStockProductsCount, 'icon' => 'fa-triangle-exclamation', 'color' => 'danger', 'financial' => false],
            ['label' => 'Materials Low in Stock', 'value' => (string) $lowStockMaterialsCount, 'icon' => 'fa-flask', 'color' => 'warning', 'financial' => false],
        ];

        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $chartData = [0, 0, 0, 0, 0, 0];

        return view('dashboard.index', compact('kpis', 'chartLabels', 'chartData', 'lowStockVariants'));
    }
}
