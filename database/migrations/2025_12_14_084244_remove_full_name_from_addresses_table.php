<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Check if column exists before dropping
            if (Schema::hasColumn('addresses', 'full_name')) {
                $table->dropColumn('full_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Add back the full_name column if rolling back
            $table->string('full_name')->nullable()->after('type');
        });
    }
};