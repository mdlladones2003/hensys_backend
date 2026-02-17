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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->uuid('inventory_transaction_id');
            $table->foreignUuid('inventory_id')->constrained('inventories', 'inventory_id')->onDelete('cascade');
            $table->foreignUuid('production_batch_id')->nullable()->constrained('production_batches', 'production_batch_id')->onDelete('set null');
            $table->enum('transaction_type', ['Production', 'Sale', 'Adjustment', 'Waste'])->default('Production');
            $table->integer('quantity');
            $table->date('transaction_date');
            $table->timestamps();

            $table->index('inventory_id');
            $table->index('production_batch_id');
            $table->index('transaction_date');
            $table->index('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
