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
        Schema::create('health_records', function (Blueprint $table) {
            $table->uuid('health_record_id')->primary();
            $table->foreignUuid('flock_id')->constrained('flocks', 'flock_id')->onDelete('cascade');
            $table->foreignUuid('employee_id')->constrained('employees', 'employee_id')->onDelete('cascade');
            $table->date('check_date');
            $table->string('health_status', 100);
            $table->string('vaccination', 200)->nullable();
            $table->text('treatment')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('flock_id');
            $table->index('employee_id');
            $table->index('check_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
