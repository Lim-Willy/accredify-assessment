<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_verification_results', function (Blueprint $table) {
            $table->id();
            $table->string('file_type');
            $table->unsignedBigInteger('user_id');
            $table->string('verification_result');
            $table->string('issuer_name')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->nullable();
            $table->bigInteger('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_verification_results');
    }
};
