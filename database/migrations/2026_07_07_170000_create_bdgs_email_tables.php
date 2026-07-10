<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdgs_email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 200);
            $table->string('subject', 500);
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bdgs_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 100);
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::table('bdgs_inquiries', function (Blueprint $table) {
            $table->string('status', 32)->default('new')->after('source');
            $table->text('admin_reply')->nullable()->after('status');
            $table->foreignId('user_id')->nullable()->after('inquiry_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bdgs_inquiries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['status', 'admin_reply', 'user_id']);
        });

        Schema::dropIfExists('bdgs_notifications');
        Schema::dropIfExists('bdgs_email_templates');
    }
};
