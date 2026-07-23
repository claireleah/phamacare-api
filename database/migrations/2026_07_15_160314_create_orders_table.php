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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pharmacy_id')->constrained()->onDelete('cascade');
        $table->string('customer_name');
        $table->string('customer_phone');
        $table->integer('total_amount')->default(0);
        $table->enum('status', ['Pending', 'Confirmed', 'Out for Delivery', 'Delivered'])->default('Pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
