<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('material_category_id')->constrained('material_categories')->restrictOnDelete();
            $table->foreignId('material_unit_id')->constrained('material_units')->restrictOnDelete();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('sku')->unique();
            $table->decimal('unit_cost_cache', 10, 4)->nullable();
            $table->decimal('current_stock_cache', 12, 2)->default(0);
            $table->decimal('minimum_stock', 12, 2)->default(0);

            // Nullable, NO foreign key constraint yet — `suppliers`
            // doesn't exist until Sprint 4 (Purchasing). Sprint 4 adds
            // the FK constraint via a new migration once it exists, per
            // the sequencing correction already documented in Step 6 V2.
            $table->unsignedBigInteger('preferred_supplier_id')->nullable();

            $table->text('notes')->nullable();
            $table->string('status')->default('active')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['current_stock_cache', 'minimum_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
