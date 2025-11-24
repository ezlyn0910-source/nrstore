<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Fix carts table - add missing columns
        if (Schema::hasTable('carts')) {
            if (!Schema::hasColumn('carts', 'total_amount')) {
                Schema::table('carts', function (Blueprint $table) {
                    $table->decimal('total_amount', 10, 2)->default(0)->after('session_id');
                });
            }
            
            if (!Schema::hasColumn('carts', 'item_count')) {
                Schema::table('carts', function (Blueprint $table) {
                    $table->integer('item_count')->default(0)->after('total_amount');
                });
            }
        }

        // Fix cart_items table - add price column
        if (Schema::hasTable('cart_items') && !Schema::hasColumn('cart_items', 'price')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->after('quantity');
            });
        }

        // Fix users table - add missing columns from pending migrations
        if (Schema::hasTable('users')) {
            $columnsToAdd = [
                'phone' => function (Blueprint $table) {
                    $table->string('phone')->nullable()->after('email');
                },
                // Add other missing user columns here as needed
            ];

            foreach ($columnsToAdd as $column => $callback) {
                if (!Schema::hasColumn('users', $column)) {
                    Schema::table('users', function (Blueprint $table) use ($callback) {
                        $callback($table);
                    });
                }
            }
        }
    }

    public function down()
    {
        // Safe rollback - don't drop data
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('carts', 'item_count')) {
                $table->dropColumn('item_count');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'price')) {
                $table->dropColumn('price');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = ['phone'/*, add other columns you want to drop on rollback */];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};