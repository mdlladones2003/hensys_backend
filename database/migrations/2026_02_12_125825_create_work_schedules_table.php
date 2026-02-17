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
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->uuid('work_schedule_id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees', 'employee_id')->onDelete('cascade');
            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('task', 200)->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('work_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
