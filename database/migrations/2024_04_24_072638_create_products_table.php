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
        Schema::create('market_places', function (Blueprint $table) {
            $table->id();
            $table->string('ItemName');
            $table->string('ItemPrice');
            $table->string('Image');
            $table->string('ProductInformation');
            $table->string('DeliveryInformation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_places');
    }
};
