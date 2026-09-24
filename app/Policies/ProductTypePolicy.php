<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProductType;
use App\Models\User;

class ProductTypePolicy
{
    /**
     * Determine whether the user can view the list of product types.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-catalog');
    }

    /**
     * Determine whether the user can create product types.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-catalog');
    }

    /**
     * Determine whether the user can update the given product type.
     */
    public function update(User $user, ProductType $productType): bool
    {
        return $user->can('manage-catalog');
    }
}
