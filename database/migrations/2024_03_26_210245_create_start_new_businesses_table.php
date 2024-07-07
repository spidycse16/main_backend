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
        Schema::create('start_new_businesses', function (Blueprint $table) {
            $table->id();
            $table->string('ShopName');
            $table->string('ShopLocation');
            $table->string('ShopType');
            $table->string('PhoneNumber');
            $table->string('Image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('start_new_businesses');
    }
};
