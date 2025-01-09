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
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone_number')->unique();
        $table->text('address');
        // $table->decimal('loan', 10, 2)->default(0); // Assuming loan can be zero if there's no loan
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('customers');
}

};
