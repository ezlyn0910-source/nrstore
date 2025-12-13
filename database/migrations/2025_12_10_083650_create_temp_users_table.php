<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // Add this
            $table->string('last_name');  // Add this
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->json('registration_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temp_users');
    }
};
