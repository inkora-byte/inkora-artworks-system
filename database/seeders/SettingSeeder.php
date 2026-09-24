<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\SettingService;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds the one setting Phase 1 needs so far — Labor Rate, required
     * by Sprint 5's Production costing. The default (₱75/hour) matches
     * the rate established during the original workbook's Shopee fee
     * migration work. Idempotent via SettingService::set()'s
     * updateOrCreate — safe to re-run.
     */
    public function run(): void
    {
        app(SettingService::class)->set(
            key: 'labor_rate_per_hour',
            value: '75.00',
            type: 'decimal',
            group: 'costing',
        );
    }
}
