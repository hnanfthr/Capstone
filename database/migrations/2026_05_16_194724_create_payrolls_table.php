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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('hours_worked', 5, 2);
            $table->integer('total_quantity')->default(0);
            $table->integer('rate_per_toples')->default(0);
            $table->decimal('wage', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
