<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('product_id')->primary();
            $table->string('product_name', 100);
            $table->text('description')->nullable();
            $table->enum('category', ['fresh_eggs', 'processed', 'organic', 'free-range'])->default('fresh_eggs');
            $table->enum('grade', ['a', 'b', 'c'])->nullable();
            $table->enum('size', ['small', 'medium', 'large', 'extra_large'])->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index('category');
            $table->index('grade');
            $table->index('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
