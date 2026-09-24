<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get all products with their type, design, and variants
     * eager-loaded, for the catalog index view.
     */
    public function allWithRelations(): Collection;
}
