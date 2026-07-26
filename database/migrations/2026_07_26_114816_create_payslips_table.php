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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('gross_pay', 10, 2);
            $table->decimal('paye', 10, 2);
            $table->decimal('usc', 10, 2);
            $table->decimal('prsi', 10, 2); // Employee PRSI
            $table->decimal('employer_prsi', 10, 2);
            $table->decimal('net_pay', 10, 2);
            $table->decimal('hours_worked', 8, 2)->default(0.00);
            $table->decimal('overtime_hours', 8, 2)->default(0.00);
            $table->decimal('bonus', 8, 2)->default(0.00);
            $table->date('period_start');
            $table->date('period_end');
            $table->string('status')->default('draft'); // draft, processed, paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
