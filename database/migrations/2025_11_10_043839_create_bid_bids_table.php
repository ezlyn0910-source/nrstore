<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bid_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bid_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_auto_bid')->default(false);
            $table->decimal('max_auto_bid', 10, 2)->nullable();
            $table->ipAddress('ip_address');
            $table->timestamp('outbid_at')->nullable();
            $table->timestamps();
            
            $table->index(['bid_id', 'amount']);
            $table->index(['user_id', 'bid_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bid_bids');
    }
};