<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdgs_data_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->json('supports')->nullable();
            $table->json('meta_schema')->nullable();
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('bdgs_data_posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('post_type_id')->constrained('bdgs_data_types')->restrictOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 500);
            $table->string('slug', 500);
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'members_only'])->default('public');
            $table->foreignId('featured_media_id')->nullable()->constrained('bdgs_media')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('pinned')->default(false);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->json('additional_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['post_type_id', 'slug']);
            $table->index(['post_type_id', 'status', 'published_at']);
        });

        Schema::create('bdgs_data_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('bdgs_data_posts')->cascadeOnDelete();
            $table->string('key', 100);
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'key']);
        });

        Schema::create('bdgs_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('bdgs_categories')->nullOnDelete();
            $table->string('name', 200);
            $table->string('slug', 200);
            $table->text('description')->nullable();
            $table->foreignId('post_type_id')->nullable()->constrained('bdgs_data_types')->nullOnDelete();
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['slug', 'post_type_id']);
        });

        Schema::create('bdgs_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('bdgs_rel_categories', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('bdgs_data_posts')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('bdgs_categories')->cascadeOnDelete();
            $table->primary(['post_id', 'category_id']);
        });

        Schema::create('bdgs_rel_tags', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('bdgs_data_posts')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('bdgs_tags')->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdgs_rel_tags');
        Schema::dropIfExists('bdgs_rel_categories');
        Schema::dropIfExists('bdgs_tags');
        Schema::dropIfExists('bdgs_categories');
        Schema::dropIfExists('bdgs_data_meta');
        Schema::dropIfExists('bdgs_data_posts');
        Schema::dropIfExists('bdgs_data_types');
    }
};
