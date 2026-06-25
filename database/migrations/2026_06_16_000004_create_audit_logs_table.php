<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action_type', 20)->nullable();
            $table->string('table_name', 50)->nullable();
            $table->timestamp('action_time')->useCurrent();
            $table->string('description', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
