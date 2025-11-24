<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only create the table if it doesn't exist
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->decimal('price', 10, 2);                
            });
        }
        
        // Always ensure the price column exists
        if (Schema::hasTable('cart_items') && !Schema::hasColumn('cart_items', 'price')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->after('quantity');
            });
        }
    }

    public function down()
    {
        // Don't drop the table to avoid data loss
        // Only drop if you're sure you want to reset
        // Schema::dropIfExists('cart_items');
    }
};