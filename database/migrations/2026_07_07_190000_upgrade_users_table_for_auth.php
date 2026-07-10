<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('id');
            }
            if (! Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('password');
            }
            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
            if (! Schema::hasColumn('users', 'failed_login_attempts')) {
                $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('last_login_ip');
            }
            if (! Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            }
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        if (Schema::hasColumn('users', 'name')) {
            foreach (DB::table('users')->get() as $user) {
                $parts = preg_split('/\s+/', trim($user->name ?? 'User'), 2);
                DB::table('users')->where('id', $user->id)->update([
                    'first_name' => $parts[0] ?? 'User',
                    'last_name' => $parts[1] ?? '',
                ]);
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        DB::table('users')->whereNull('first_name')->update(['first_name' => 'User']);
        DB::table('users')->whereNull('last_name')->update(['last_name' => '']);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });

            foreach (DB::table('users')->get() as $user) {
                DB::table('users')->where('id', $user->id)->update([
                    'name' => trim(($user->first_name ?? '').' '.($user->last_name ?? '')),
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            foreach (['first_name', 'last_name', 'is_active', 'last_login_at', 'last_login_ip', 'failed_login_attempts', 'locked_until'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
