<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaymentMethodOnOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make it a longer string, nullable if you want
            $table->string('payment_method', 50)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // If previously it was, for example, string(20) NOT NULL:
            $table->string('payment_method', 20)->nullable(false)->change();
        });
    }
}
