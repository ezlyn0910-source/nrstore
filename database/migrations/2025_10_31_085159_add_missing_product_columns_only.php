<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('stock_quantity');
            }
            
            if (!Schema::hasColumn('products', 'is_recommended')) {
                $table->boolean('is_recommended')->default(false)->after('is_featured');
            }
            
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('products', 'deleted_at')) {
                $table->softDeletes();
            }
            
            // Add indexes if they don't exist
            $table->index(['is_active', 'is_featured']);
            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Only drop columns that were added by this migration
            $columnsToDrop = [];
            
            if (Schema::hasColumn('products', 'is_active')) {
                $columnsToDrop[] = 'is_active';
            }
            
            if (Schema::hasColumn('products', 'is_recommended')) {
                $columnsToDrop[] = 'is_recommended';
            }
            
            if (Schema::hasColumn('products', 'slug')) {
                $columnsToDrop[] = 'slug';
            }
            
            if (Schema::hasColumn('products', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
            
            // Drop indexes
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['category_id']);
        });
    }
};