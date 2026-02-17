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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->uuid('production_batch_id')->primary();
            $table->foreignUuid('flock_id')->constrained('flocks', 'flock_id')->onDelete('cascade');
            $table->date('production_date');
            $table->integer('total_eggs')->default(0);
            $table->integer('grade_a')->default(0);
            $table->integer('grade_b')->default(0);
            $table->integer('rejected')->default(0);
            $table->timestamps();

            $table->index('flock_id');
            $table->index('production_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
