<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Basic info
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);

            // Electronics specifications
            $table->string('model')->nullable();
            $table->string('processor')->nullable();
            $table->integer('ram')->nullable();
            $table->integer('storage')->nullable();
            $table->string('storage_type')->nullable();
            $table->string('graphics_card')->nullable();
            $table->string('screen_size')->nullable();
            $table->string('os')->nullable();
            $table->string('warranty')->nullable();
            $table->string('voltage')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variations');
    }
};
