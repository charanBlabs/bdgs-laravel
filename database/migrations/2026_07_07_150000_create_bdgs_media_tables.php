<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdgs_media', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('filename', 255);
            $table->string('disk', 30)->default('public');
            $table->string('path', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_text', 500)->nullable();
            $table->string('title', 500)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('bdgs_media_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('bdgs_media')->cascadeOnDelete();
            $table->string('variant_name', 50);
            $table->string('path', 500);
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->unsignedBigInteger('size_bytes');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['media_id', 'variant_name']);
        });

        Schema::create('bdgs_mediables', function (Blueprint $table) {
            $table->foreignId('media_id')->constrained('bdgs_media')->cascadeOnDelete();
            $table->unsignedBigInteger('mediable_id');
            $table->string('mediable_type', 100);
            $table->string('collection', 50)->default('default');
            $table->smallInteger('sort_order')->default(0);

            $table->primary(['media_id', 'mediable_id', 'mediable_type', 'collection'], 'bdgs_mediables_primary');
        });

        Schema::table('bdgs_user_data', function (Blueprint $table) {
            $table->foreign('avatar_media_id')->references('id')->on('bdgs_media')->nullOnDelete();
            $table->foreign('logo_media_id')->references('id')->on('bdgs_media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bdgs_user_data', function (Blueprint $table) {
            $table->dropForeign(['avatar_media_id']);
            $table->dropForeign(['logo_media_id']);
        });

        Schema::dropIfExists('bdgs_mediables');
        Schema::dropIfExists('bdgs_media_variants');
        Schema::dropIfExists('bdgs_media');
    }
};
