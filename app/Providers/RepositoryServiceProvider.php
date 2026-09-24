<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Catalog\ProductRepository;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Interface-to-implementation bindings accumulate here as later
     * sprints introduce their own Repositories (Purchasing, Sales,
     * Reports — per Step 5's module table).
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }
}
