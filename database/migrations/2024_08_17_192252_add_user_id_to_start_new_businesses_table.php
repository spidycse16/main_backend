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
        Schema::table('start_new_businesses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('Image'); // Adds the user_id column after the Image column
            
            // Optionally, you can add a foreign key constraint if you have a related 'users' table
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('start_new_businesses', function (Blueprint $table) {
            $table->dropColumn('user_id');

            // Optionally, you can drop the foreign key constraint if you added one
            // $table->dropForeign(['user_id']);
        });
    }
};
