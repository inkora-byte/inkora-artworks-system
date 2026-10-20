<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Design;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\User;
use App\Policies\DesignPolicy;
use App\Policies\MaterialPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProductTypePolicy;
use App\Policies\RolePolicy;
use App\Policies\SettingPolicy;
use App\Policies\StockMovementPolicy;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Policy registrations accumulate here across milestones — further
     * entries are added as later sprints introduce their own
     * Models/Policies.
     */
    public function boot(): void
    {
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Setting::class, SettingPolicy::class);
        Gate::policy(ProductType::class, ProductTypePolicy::class);
        Gate::policy(Design::class, DesignPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(StockMovement::class, StockMovementPolicy::class);
        Gate::policy(Material::class, MaterialPolicy::class);
        Gate::policy(\App\Models\MaterialCategory::class, \App\Policies\MaterialCategoryPolicy::class);
        Gate::policy(\App\Models\MaterialUnit::class, \App\Policies\MaterialUnitPolicy::class);

        // Morph map for polymorphic relationships (Milestone 2.1) — see
        // that milestone's notes on why this is morphMap(), not
        // enforceMorphMap(). 'material' added here (Milestone 3.1) is
        // the actual proof this design works: StockMovementService,
        // completely unmodified since Sprint 2, now serves a second
        // entity type just by this map growing by one line.
        Relation::morphMap([
            'product_variant' => ProductVariant::class,
            'material' => Material::class,
        ]);
    }
}
