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
        Schema::create('tax_liabilities', function (Blueprint $table) {
            $table->id();
            $table->string('tax_period')->unique(); // e.g. "2026-07"
            $table->decimal('paye', 12, 2)->default(0.00);
            $table->decimal('usc', 12, 2)->default(0.00);
            $table->decimal('prsi_employee', 12, 2)->default(0.00);
            $table->decimal('prsi_employer', 12, 2)->default(0.00);
            $table->decimal('total_liability', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_liabilities');
    }
};
