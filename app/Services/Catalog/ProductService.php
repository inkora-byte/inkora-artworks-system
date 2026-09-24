<?php

declare(strict_types=1);

namespace App\Services\Catalog;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\Traits\GeneratesSku;
use Illuminate\Support\Facades\DB;

class ProductService
{
    use GeneratesSku;

    /**
     * Create a new product AND its required default variant, atomically.
     *
     * This is the single most load-bearing method in the Catalog module
     * (flagged explicitly as a risk in Step 6's Roadmap) — every
     * downstream module (Bill of Materials, Production, Sales) keys off
     * `product_variants`, never `products` directly. A product existing
     * without at least one variant is an invalid state the rest of the
     * system has no way to handle gracefully, so this method guarantees
     * it can never happen: both rows are created inside one transaction,
     * or neither is.
     *
     * @param array{
     *     name: string,
     *     product_type_id: int,
     *     design_id: ?int,
     *     description: ?string,
     *     price: string,
     *     fulfillment_method: string,
     *     minimum_stock: ?int,
     *     time_to_make_minutes: ?int,
     * } $data
     */
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create([
                'name' => $data['name'],
                'product_type_id' => $data['product_type_id'],
                'design_id' => $data['design_id'] ?? null,
                'description' => $data['description'] ?? null,
                'status' => ProductStatus::Active->value,
            ]);

            $productType = ProductType::findOrFail($data['product_type_id']);

            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => static::generateSku('product_variants', $productType->code),
                'is_default_variant' => true,
                'variant_name' => null,
                'price' => $data['price'],
                'fulfillment_method' => $data['fulfillment_method'] ?? 'in_house',
                'minimum_stock' => $data['minimum_stock'] ?? 0,
                'time_to_make_minutes' => $data['time_to_make_minutes'] ?? null,
                'status' => ProductStatus::Active->value,
            ]);

            return $product->load('variants');
        });
    }

    /**
     * Update an existing product and its default variant together.
     *
     * Phase 1 scope: only the default variant is editable through this
     * path — adding genuinely separate additional variants (e.g. size or
     * color combinations) is intentionally not built yet, since Inkora's
     * current catalog doesn't need it. The schema already supports it
     * (Step 2's original design decision); the UI for it is deferred
     * until a real product actually requires more than one variant, per
     * the AI Development Contract's "avoid unnecessary complexity" rule.
     *
     * @param array{
     *     name: string,
     *     product_type_id: int,
     *     design_id: ?int,
     *     description: ?string,
     *     price: string,
     *     fulfillment_method: string,
     *     minimum_stock: ?int,
     *     time_to_make_minutes: ?int,
     * } $data
     */
    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update([
                'name' => $data['name'],
                'product_type_id' => $data['product_type_id'],
                'design_id' => $data['design_id'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            $defaultVariant = $product->defaultVariant;

            if ($defaultVariant !== null) {
                $defaultVariant->update([
                    'price' => $data['price'],
                    'fulfillment_method' => $data['fulfillment_method'] ?? $defaultVariant->fulfillment_method->value,
                    'minimum_stock' => $data['minimum_stock'] ?? 0,
                    'time_to_make_minutes' => $data['time_to_make_minutes'] ?? null,
                ]);
            }

            return $product->fresh(['variants']);
        });
    }

    /**
     * Toggle a product's (and its default variant's) active status —
     * deactivation, not deletion, mirroring the pattern established for
     * Users (Milestone 0.8) and Designs (Milestone 1.1). Discontinuing a
     * product excludes it from new order-entry pickers (Sales, Sprint 7)
     * without touching its historical sales/stock data.
     */
    public function toggleStatus(Product $product): Product
    {
        return DB::transaction(function () use ($product) {
            $newStatus = $product->status === ProductStatus::Active
                ? ProductStatus::Discontinued
                : ProductStatus::Active;

            $product->update(['status' => $newStatus->value]);

            $defaultVariant = $product->defaultVariant;

            if ($defaultVariant !== null) {
                $defaultVariant->update(['status' => $newStatus->value]);
            }

            return $product->fresh();
        });
    }
}
