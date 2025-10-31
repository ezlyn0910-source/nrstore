<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only add the column if it doesn't exist
        if (!Schema::hasColumn('products', 'is_recommended')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_recommended')->default(false);
            });
        }
    }

    public function down()
    {
        // Only drop the column if it exists
        if (Schema::hasColumn('products', 'is_recommended')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_recommended');
            });
        }
    }
};