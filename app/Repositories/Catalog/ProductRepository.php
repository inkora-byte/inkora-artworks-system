<?php

declare(strict_types=1);

namespace App\Repositories\Catalog;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products with their type, design, variants, and gallery
     * media eager-loaded, for the catalog index view.
     */
    public function allWithRelations(): Collection
    {
        return Product::with(['productType', 'design', 'variants', 'media'])
            ->orderBy('name')
            ->get();
    }
}
