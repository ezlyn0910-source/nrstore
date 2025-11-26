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
        Schema::table('bids', function (Blueprint $table) {
            // Add the nullable variation_id column after product_id
            $table->unsignedBigInteger('variation_id')->nullable()->after('product_id');

            // Add foreign key constraint (optional but recommended)
            $table->foreign('variation_id')
                  ->references('id')
                  ->on('variations')
                  ->onDelete('cascade'); // If variation is deleted, delete the bid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['variation_id']);
            // Then drop the column
            $table->dropColumn('variation_id');
        });
    }
};