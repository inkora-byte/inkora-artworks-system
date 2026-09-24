<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * TEMPORARY — Milestone 0.10 diagnostic only.
 *
 * Exists solely to prove the queue pipeline works end to end (dispatch ->
 * database `jobs` table -> `queue:work` -> execution) before Sprint 2's
 * real domain events depend on it. Delete this class once verified — it
 * is not part of the permanent architecture, the same treatment as
 * Milestone 0.6's temporary `layout-preview` page.
 */
class SmokeTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SmokeTestJob executed successfully — queue pipeline confirmed working.');
    }
}
