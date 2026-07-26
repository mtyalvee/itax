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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('pps_number')->unique();
            $table->string('department');
            $table->string('job_title');
            $table->decimal('hourly_rate', 8, 2)->default(0.00);
            $table->decimal('salary', 10, 2)->default(0.00); // Annual salary
            $table->boolean('active')->default(true);
            $table->decimal('tax_credit', 8, 2)->default(3750.00); // Annual Tax Credit (standard e.g. €3,750)
            $table->decimal('cutoff_point', 8, 2)->default(42000.00); // Annual Standard Rate Cutoff Point (standard e.g. €42,000)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
