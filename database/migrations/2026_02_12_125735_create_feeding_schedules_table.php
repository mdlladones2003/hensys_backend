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
        Schema::create('feeding_schedules', function (Blueprint $table) {
            $table->uuid('feeding_schedule_id')->primary();
            $table->foreignUuid('flock_id')->constrained('flocks', 'flock_id')->onDelete('cascade');
            $table->string('feed_type', 100);
            $table->time('feeding_time');
            $table->decimal('quantity_kg', 8, 2);
            $table->timestamps();

            $table->index('flock_id');
            $table->index('feeding_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_schedules');
    }
};
