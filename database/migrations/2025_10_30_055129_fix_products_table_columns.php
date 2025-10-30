<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('products', 'is_recommended')) {
                $table->boolean('is_recommended')->default(false);
            }
            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable();
            }
            if (!Schema::hasColumn('products', 'ram')) {
                $table->string('ram')->nullable();
            }
            if (!Schema::hasColumn('products', 'storage')) {
                $table->string('storage')->nullable();
            }
            if (!Schema::hasColumn('products', 'processor')) {
                $table->string('processor')->nullable();
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            
            // Rename existing columns to match your code
            if (Schema::hasColumn('products', 'product_name') && !Schema::hasColumn('products', 'name')) {
                $table->renameColumn('product_name', 'name');
            }
            if (Schema::hasColumn('products', 'base_price') && !Schema::hasColumn('products', 'price')) {
                $table->renameColumn('base_price', 'price');
            }
            if (Schema::hasColumn('products', 'total_stock') && !Schema::hasColumn('products', 'stock_quantity')) {
                $table->renameColumn('total_stock', 'stock_quantity');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverse the changes if needed
            $table->dropColumn(['is_recommended', 'brand', 'ram', 'storage', 'processor', 'is_featured']);
        });
    }
};