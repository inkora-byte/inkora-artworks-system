<?php

declare(strict_types=1);

namespace App\Services\Materials;

use App\Enums\MaterialStatus;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Traits\GeneratesSku;

class MaterialService
{
    use GeneratesSku;

    /**
     * Create a new material, auto-generating its SKU from its
     * category's code — the exact same {CODE}-{0001} pattern used for
     * Product Variants (Milestone 1.2), via the shared trait Step 5
     * always intended this for.
     *
     * @param array{
     *     name: string,
     *     material_category_id: int,
     *     material_unit_id: int,
     *     brand: ?string,
     *     minimum_stock: ?float,
     *     notes: ?string,
     * } $data
     */
    public function create(array $data): Material
    {
        $category = MaterialCategory::findOrFail($data['material_category_id']);

        return Material::create([
            'name' => $data['name'],
            'material_category_id' => $data['material_category_id'],
            'material_unit_id' => $data['material_unit_id'],
            'brand' => $data['brand'] ?? null,
            'sku' => static::generateSku('materials', $category->code),
            'minimum_stock' => $data['minimum_stock'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'status' => MaterialStatus::Active->value,
        ]);
    }

    /**
     * Update an existing material's editable fields.
     *
     * `unit_cost_cache` is never touched here — Purchasing owns that
     * write (Sprint 4), never a staff-facing edit form, matching the
     * exact fix applied to `materials.unit_cost_cache` during the
     * original workbook migration.
     *
     * @param array{
     *     name: string,
     *     material_category_id: int,
     *     material_unit_id: int,
     *     brand: ?string,
     *     minimum_stock: ?float,
     *     notes: ?string,
     * } $data
     */
    public function update(Material $material, array $data): Material
    {
        $material->update([
            'name' => $data['name'],
            'material_category_id' => $data['material_category_id'],
            'material_unit_id' => $data['material_unit_id'],
            'brand' => $data['brand'] ?? null,
            'minimum_stock' => $data['minimum_stock'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);

        return $material->fresh();
    }

    /**
     * Toggle a material's active/phased-out status — deactivation, not
     * deletion, same pattern as Products (Milestone 1.2) and Designs
     * (Milestone 1.1).
     */
    public function toggleStatus(Material $material): Material
    {
        $newStatus = $material->status === MaterialStatus::Active
            ? MaterialStatus::PhasedOut
            : MaterialStatus::Active;

        $material->update(['status' => $newStatus->value]);

        return $material->fresh();
    }
}
