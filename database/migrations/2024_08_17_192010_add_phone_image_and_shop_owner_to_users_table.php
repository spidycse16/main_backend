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
        Schema::table('users', function (Blueprint $table) {
            // Adding a phone number column
            $table->string('phone')->nullable()->after('email');

            // Adding an image column
            $table->string('image')->nullable()->after('phone');

            // Adding a boolean column to indicate if the user is a shop owner
            $table->boolean('is_shop_owner')->default(false)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Dropping the phone number column
            $table->dropColumn('phone');

            // Dropping the image column
            $table->dropColumn('image');

            // Dropping the is_shop_owner column
            $table->dropColumn('is_shop_owner');
        });
    }
};
