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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();

            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method')->nullable();
            $table->text('url')->nullable();

            $table->longText('request_body')->nullable();
            $table->longText('response_body')->nullable();

            $table->integer('status_code')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
