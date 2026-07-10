<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdgs_website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50);
            $table->string('key', 100);
            $table->longText('value')->nullable();
            $table->string('type', 20)->default('string');
            $table->timestamps();

            $table->unique(['group', 'key']);
        });

        Schema::create('bdgs_list_seo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seoable_id');
            $table->string('seoable_type', 100);
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('og_title', 200)->nullable();
            $table->string('og_description', 500)->nullable();
            $table->foreignId('og_image_media_id')->nullable()->constrained('bdgs_media')->nullOnDelete();
            $table->string('canonical_url', 500)->nullable();
            $table->string('robots', 100)->nullable();
            $table->json('schema_markup')->nullable();
            $table->timestamps();

            $table->unique(['seoable_id', 'seoable_type']);
        });

        Schema::create('bdgs_activity_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->string('subject_type', 100)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });

        Schema::create('bdgs_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url', 500)->unique();
            $table->string('to_url', 500);
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdgs_redirects');
        Schema::dropIfExists('bdgs_activity_log');
        Schema::dropIfExists('bdgs_list_seo');
        Schema::dropIfExists('bdgs_website_settings');
    }
};
