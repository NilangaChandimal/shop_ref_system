<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('product_returns', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_id')->constrained()->onDelete('cascade');
        $table->string('customer_name');
        $table->string('product_name');
        $table->integer('returned_quantity');
        $table->decimal('price_per_unit', 8, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};
