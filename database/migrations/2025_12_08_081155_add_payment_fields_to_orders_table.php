<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_gateway');
            }

            if (!Schema::hasColumn('orders', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('orders', 'gateway_meta')) {
                $table->json('gateway_meta')->nullable()->after('gateway_transaction_id');
            }

            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('cancelled_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'payment_status',
                'gateway_transaction_id',
                'gateway_meta',
                'paid_at',
            ]);
        });
    }
};