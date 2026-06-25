<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id('case_id');
            $table->string('case_title', 150);
            $table->string('case_type', 50)->nullable();
            $table->string('case_description', 1000)->nullable();
            $table->string('case_status', 20)->default('OPEN');
            $table->date('opened_date')->useCurrent();
            $table->date('closed_date')->nullable();
            $table->foreignId('officer_id')->references('user_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
