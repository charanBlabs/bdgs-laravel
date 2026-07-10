<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdgs_inquiries', function (Blueprint $table) {
            $table->id('inquiry_id');
            $table->string('name', 120);
            $table->string('email', 255);
            $table->string('phone', 40);
            $table->string('directory_url', 500)->nullable();
            $table->string('need', 80);
            $table->text('message')->nullable();
            $table->string('source', 32)->default('api');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('email');
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdgs_inquiries');
    }
};
