<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->foreignId('role_id')->references('role_id')->on('roles');
            $table->string('full_name', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('phone', 20)->nullable();
            $table->string('status', 20)->default('ACTIVE');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
