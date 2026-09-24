<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * "Products Low in Stock" and the Low Stock Top 10 widget are real
     * now (Milestone 2.2) — everything else remains a placeholder,
     * filled in module by module as later sprints ship, per the plan
     * laid out back in Milestone 0.7.
     */
    public function index(): View
    {
        $lowStockQuery = ProductVariant::with('product')
            ->where('status', 'active')
            ->whereColumn('current_stock_cache', '<=', 'minimum_stock');

        // Cloned so the count query and the limited list query run
        // independently — a shared limit()'d query would undercount
        // the KPI card whenever there are more than 10 low-stock items.
        $lowStockCount = (clone $lowStockQuery)->count();
        $lowStockVariants = (clone $lowStockQuery)->orderBy('current_stock_cache')->limit(10)->get();

        $kpis = [
            ['label' => "Today's Sales", 'value' => '₱0.00', 'icon' => 'fa-peso-sign', 'color' => 'primary', 'financial' => true],
            ['label' => 'Monthly Revenue', 'value' => '₱0.00', 'icon' => 'fa-chart-line', 'color' => 'success', 'financial' => true],
            ['label' => 'Pending Orders', 'value' => '0', 'icon' => 'fa-receipt', 'color' => 'warning', 'financial' => false],
            ['label' => 'Products Low in Stock', 'value' => (string) $lowStockCount, 'icon' => 'fa-triangle-exclamation', 'color' => 'danger', 'financial' => false],
        ];

        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $chartData = [0, 0, 0, 0, 0, 0];

        return view('dashboard.index', compact('kpis', 'chartLabels', 'chartData', 'lowStockVariants'));
    }
}
