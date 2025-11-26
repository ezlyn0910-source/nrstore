<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('storage_type')->nullable()->after('storage');
            $table->string('graphics_card')->nullable()->after('processor');
            $table->string('screen_size')->nullable()->after('graphics_card');
            $table->string('os')->nullable()->after('screen_size');
            $table->string('warranty')->nullable()->after('os');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'storage_type',
                'graphics_card', 
                'screen_size',
                'os',
                'warranty'
            ]);
        });
    }
};