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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Standard, Premium
            $table->integer('monthly_price');
            $table->integer('yearly_price');
            $table->integer('max_products'); // 0 = unlimited
            $table->integer('max_riders');   // 0 = unlimited
            $table->boolean('stock_alerts')->default(false);
            $table->boolean('sales_reports')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
