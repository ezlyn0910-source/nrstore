<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('starting_price', 10, 2);
            $table->decimal('current_price', 10, 2);
            $table->decimal('reserve_price', 10, 2)->nullable();
            $table->decimal('bid_increment', 10, 2)->default(1.00);
            $table->timestamp('start_time')->useCurrent();
            $table->timestamp('end_time')->useCurrent();
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('draft');
            $table->integer('bid_count')->default(0);
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('winning_bid_amount', 10, 2)->nullable();
            $table->text('terms_conditions')->nullable();
            $table->boolean('auto_extend')->default(false);
            $table->integer('extension_minutes')->default(5);
            $table->timestamps();
            
            $table->index(['status', 'end_time']);
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bids');
    }
};