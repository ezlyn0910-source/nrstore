<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('first_name')->after('type')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            // Optionally remove full_name or keep it
            // $table->dropColumn('full_name'); // If you want to remove full_name
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
