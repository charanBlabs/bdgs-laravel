<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bdgs_data_types', function (Blueprint $table) {
            $table->string('name_plural', 120)->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('bdgs_data_types', function (Blueprint $table) {
            $table->dropColumn('name_plural');
        });
    }
};
