<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bdgs_zoom_clinic_registrations', function (Blueprint $table) {
            $table->timestamp('confirmation_sent_at')->nullable()->after('registered_at');
            $table->timestamp('reminder_24h_sent_at')->nullable()->after('confirmation_sent_at');
            $table->timestamp('reminder_1h_sent_at')->nullable()->after('reminder_24h_sent_at');
            $table->string('calendar_uid', 120)->nullable()->after('reminder_1h_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('bdgs_zoom_clinic_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'confirmation_sent_at',
                'reminder_24h_sent_at',
                'reminder_1h_sent_at',
                'calendar_uid',
            ]);
        });
    }
};
