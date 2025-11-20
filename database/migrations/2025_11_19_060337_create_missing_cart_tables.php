<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create carts table if it doesn't exist
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('session_id')->nullable();
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->integer('item_count')->default(0);
                $table->timestamps();
                
                $table->unique(['user_id']);
                $table->unique(['session_id']);
                $table->index(['user_id', 'session_id']);
            });
        }

        // Create cart_items table if it doesn't exist
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('variation_id')->nullable()->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->decimal('price', 10, 2);
                $table->timestamps();
                
                $table->unique(['cart_id', 'product_id', 'variation_id']);
                $table->index(['cart_id']);
                $table->index(['product_id']);
            });
        }
    }

    public function down()
    {
        // Don't drop tables in down method to avoid data loss
    }
};