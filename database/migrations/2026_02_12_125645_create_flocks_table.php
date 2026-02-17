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
        Schema::create('flocks', function (Blueprint $table) {
            $table->uuid('flock_id')->primary();
            $table->foreignUuid('housing_id')->constrained('housings', 'housing_id')->onDelete('cascade');
            $table->string('flock_name', 100);
            $table->string('breed', 100);
            $table->integer('bird_count');
            $table->integer('age_weeks');
            $table->date('arrival_date');
            $table->enum('status', ['active', 'inactive', 'culled'])->default('active');
            $table->timestamps();

            $table->index('housing_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flocks');
    }
};
