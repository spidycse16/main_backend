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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ShopId'); // Define foreign key for shop
            $table->string('ItemName');
            $table->string('ItemPrice');
            $table->string('Image');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('ShopId')->references('id')->on('start_new_businesses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_of_items');
    }
};
