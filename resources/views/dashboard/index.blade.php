@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        @foreach ($kpis as $kpi)
            @if (! $kpi['financial'] || auth()->user()->can('view-financials'))
                <div class="col-md-3">
                    <x-kpi-card
                        :label="$kpi['label']"
                        :value="$kpi['value']"
                        :icon="$kpi['icon']"
                        :color="$kpi['color']"
                    />
                </div>
            @endif
        @endforeach
    </div>

    @if ($lowStockVariants->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 text-muted text-uppercase mb-3">Low Stock — Top 10</h2>
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Minimum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockVariants as $variant)
                            <tr>
                                <td><code>{{ $variant->sku }}</code></td>
                                <td>{{ $variant->product->name }}</td>
                                <td class="text-danger fw-semibold">{{ $variant->current_stock_cache }}</td>
                                <td>{{ $variant->minimum_stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @can('view-financials')
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 text-muted text-uppercase mb-3">Monthly Revenue (placeholder — real data arrives module by module)</h2>
                <canvas id="revenue-chart" height="80"></canvas>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('revenue-chart');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Revenue',
                            data: @json($chartData),
                            backgroundColor: '#7E6BAF',
                            borderRadius: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            y: { beginAtZero: true },
                        },
                    },
                });
            });
        </script>
    @endcan
@endsection
