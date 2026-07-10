<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bdgs_zoom_clinics', function (Blueprint $table) {
            $table->integer('clinic_id')->autoIncrement();
            $table->string('slug', 120);
            $table->string('title', 255);
            $table->string('agenda', 500)->default('');
            $table->text('description')->nullable();
            $table->dateTime('session_starts_at');
            $table->dateTime('session_ends_at');
            $table->dateTime('buffer_ends_at')->nullable();
            $table->string('source_timezone', 64)->default('Asia/Kolkata');
            $table->string('format_note', 255)->default('60-min open Q&A with our devs');
            $table->string('zoom_meeting_url', 1000)->default('');
            $table->unsignedSmallInteger('max_capacity')->nullable();
            $table->enum('status', ['scheduled', 'live', 'completed', 'cancelled'])->default('scheduled');
            $table->tinyInteger('is_published')->default(1);
            $table->tinyInteger('is_featured')->default(0);
            $table->integer('sort_order')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('slug', 'uk_zoom_clinic_slug');
            $table->index('session_starts_at', 'idx_session_starts_at');
            $table->index(['is_published', 'status', 'session_starts_at'], 'idx_published_status_starts');
        });

        Schema::create('bdgs_zoom_clinic_registrations', function (Blueprint $table) {
            $table->integer('registration_id')->autoIncrement();
            $table->integer('clinic_id');
            $table->string('name', 120);
            $table->string('email', 255);
            $table->string('directory_url', 500)->default('');
            $table->text('help_topic')->nullable();
            $table->string('registrant_timezone', 64)->default('');
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->dateTime('registered_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('clinic_id', 'idx_clinic_id');
            $table->index(['clinic_id', 'status'], 'idx_clinic_status');
            $table->index('email', 'idx_registrant_email');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE bdgs_zoom_clinics ENGINE = MyISAM');
            DB::statement('ALTER TABLE bdgs_zoom_clinic_registrations ENGINE = MyISAM');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bdgs_zoom_clinic_registrations');
        Schema::dropIfExists('bdgs_zoom_clinics');
    }
};
