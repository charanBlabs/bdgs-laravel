<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdgs_user_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('company', 255)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('website', 500)->nullable();
            $table->text('bio')->nullable();
            $table->unsignedBigInteger('avatar_media_id')->nullable();
            $table->unsignedBigInteger('logo_media_id')->nullable();
            $table->string('address_line_1', 255)->nullable();
            $table->string('address_line_2', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->decimal('wallet_balance', 10, 2)->default(0);
            $table->json('preferences')->nullable();
            $table->json('additional_fields')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdgs_user_data');
    }
};
