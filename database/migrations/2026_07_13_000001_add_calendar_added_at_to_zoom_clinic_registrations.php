<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bdgs_zoom_clinic_registrations', function (Blueprint $table) {
            $table->timestamp('calendar_added_at')->nullable()->after('calendar_uid');
        });
    }

    public function down(): void
    {
        Schema::table('bdgs_zoom_clinic_registrations', function (Blueprint $table) {
            $table->dropColumn('calendar_added_at');
        });
    }
};
