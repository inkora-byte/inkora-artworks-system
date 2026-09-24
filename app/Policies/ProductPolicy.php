<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view the list of products.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-catalog');
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-catalog');
    }

    /**
     * Determine whether the user can update the given product.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->can('manage-catalog');
    }

    /**
     * Determine whether the user can activate/discontinue the given
     * product.
     */
    public function toggleStatus(User $user, Product $product): bool
    {
        return $user->can('manage-catalog');
    }
}
