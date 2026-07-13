<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize emails so uniqueness is case-stable.
        $ids = DB::table('bdgs_zoom_clinic_registrations')->pluck('email', 'registration_id');
        foreach ($ids as $registrationId => $email) {
            $normalized = strtolower(trim((string) $email));
            if ($normalized !== (string) $email) {
                DB::table('bdgs_zoom_clinic_registrations')
                    ->where('registration_id', $registrationId)
                    ->update(['email' => $normalized]);
            }
        }

        // Keep one row per (clinic_id, email): prefer confirmed, then oldest id.
        $duplicates = DB::table('bdgs_zoom_clinic_registrations')
            ->select('clinic_id', 'email', DB::raw('COUNT(*) as total'))
            ->groupBy('clinic_id', 'email')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $rows = DB::table('bdgs_zoom_clinic_registrations')
                ->where('clinic_id', $dup->clinic_id)
                ->where('email', $dup->email)
                ->orderByRaw("CASE WHEN status = 'confirmed' THEN 0 ELSE 1 END")
                ->orderBy('registration_id')
                ->get();

            $keepId = $rows->first()?->registration_id;
            if (! $keepId) {
                continue;
            }

            DB::table('bdgs_zoom_clinic_registrations')
                ->where('clinic_id', $dup->clinic_id)
                ->where('email', $dup->email)
                ->where('registration_id', '!=', $keepId)
                ->delete();
        }

        Schema::table('bdgs_zoom_clinic_registrations', function (Blueprint $table) {
            $table->unique(['clinic_id', 'email'], 'uk_zoom_reg_clinic_email');
        });
    }

    public function down(): void
    {
        Schema::table('bdgs_zoom_clinic_registrations', function (Blueprint $table) {
            $table->dropUnique('uk_zoom_reg_clinic_email');
        });
    }
};
