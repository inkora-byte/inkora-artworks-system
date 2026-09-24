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
        Schema::create('product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('external_sku')->nullable()->index();
            $table->string('variant_name')->nullable();
            $table->boolean('is_default_variant')->default(true);
            $table->decimal('price', 10, 2);
            $table->string('fulfillment_method')->default('in_house')->index();
            $table->decimal('cost_cache', 10, 4)->nullable();
            $table->timestamp('cost_calculated_at')->nullable();
            $table->integer('current_stock_cache')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->integer('time_to_make_minutes')->nullable();
            $table->string('status')->default('active');
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
        Schema::dropIfExists('product_variants');
    }
};
