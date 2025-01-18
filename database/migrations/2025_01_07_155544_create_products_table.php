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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('original_price', 10, 2);
            $table->decimal('displayed_price', 10, 2);
            $table->decimal('discount', 5, 2); // Discount as percentage
            $table->decimal('selling_price', 10, 2);
            $table->decimal('profit', 10, 2)->default(0); // Removed ->after('selling_price')
            $table->string('unit');
            $table->integer('quantity');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
