<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('session_id');
            }
            
            if (!Schema::hasColumn('carts', 'item_count')) {
                $table->integer('item_count')->default(0)->after('total_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'item_count']);
        });
    }
};