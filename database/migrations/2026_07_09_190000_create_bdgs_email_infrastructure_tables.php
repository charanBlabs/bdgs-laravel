<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Logs every email sent (transactional + scheduled + automation)
        Schema::create('bdgs_email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('template_slug', 100)->nullable()->index();
            $table->string('to_email', 255);
            $table->string('to_name', 200)->nullable();
            $table->string('from_email', 255);
            $table->string('from_name', 200)->nullable();
            $table->string('subject', 500);
            $table->longText('body_html')->nullable();
            $table->text('body_text')->nullable();
            $table->string('status', 30)->default('queued')->index();
            $table->string('channel', 50)->default('smtp');
            $table->string('message_id', 255)->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['to_email', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        // Subscriber / mailing lists
        Schema::create('bdgs_email_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('type', 50)->default('manual');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Subscribers (pivot between users/emails and lists)
        Schema::create('bdgs_email_list_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->constrained('bdgs_email_lists')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('email', 255);
            $table->string('name', 200)->nullable();
            $table->string('status', 30)->default('subscribed');
            $table->string('source', 100)->nullable();
            $table->string('unsubscribe_token', 64)->nullable()->unique();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();

            $table->unique(['list_id', 'email']);
            $table->index(['email', 'status']);
        });

        // Scheduled email campaigns / broadcasts
        Schema::create('bdgs_email_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('template_slug', 100)->nullable();
            $table->string('subject', 500)->nullable();
            $table->longText('body_html')->nullable();
            $table->string('from_email', 255)->nullable();
            $table->string('from_name', 200)->nullable();
            $table->json('list_ids')->nullable();
            $table->json('exclude_list_ids')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('total_sent')->default(0);
            $table->unsignedInteger('total_failed')->default(0);
            $table->unsignedInteger('total_opened')->default(0);
            $table->unsignedInteger('total_clicked')->default(0);
            $table->string('repeat_interval', 50)->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Outbox (queued emails waiting to be dispatched)
        Schema::create('bdgs_email_outbox', function (Blueprint $table) {
            $table->id();
            $table->string('to_email', 255);
            $table->string('to_name', 200)->nullable();
            $table->string('from_email', 255);
            $table->string('from_name', 200)->nullable();
            $table->string('subject', 500);
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->string('template_slug', 100)->nullable();
            $table->json('variables')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->string('priority', 20)->default('normal');
            $table->unsignedSmallInteger('max_attempts')->default(3);
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->foreignId('schedule_id')->nullable()->constrained('bdgs_email_schedules')->nullOnDelete();
            $table->foreignId('automation_rule_id')->nullable();
            $table->timestamp('send_after')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'send_after']);
            $table->index(['status', 'priority', 'created_at']);
        });

        // Automation rules (triggered emails based on events)
        Schema::create('bdgs_email_automation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('trigger_event', 100)->index();
            $table->string('template_slug', 100);
            $table->unsignedSmallInteger('delay_days')->default(0);
            $table->unsignedSmallInteger('delay_hours')->default(0);
            $table->boolean('send_to_user')->default(true);
            $table->boolean('send_to_admin')->default(false);
            $table->boolean('copy_admin')->default(false);
            $table->json('conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Log of automation rule executions
        Schema::create('bdgs_email_automation_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained('bdgs_email_automation_rules')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email', 255);
            $table->string('trigger_event', 100);
            $table->string('status', 30)->default('sent');
            $table->text('response')->nullable();
            $table->foreignId('email_log_id')->nullable()->constrained('bdgs_email_logs')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['rule_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdgs_email_automation_log');
        Schema::dropIfExists('bdgs_email_automation_rules');
        Schema::dropIfExists('bdgs_email_outbox');
        Schema::dropIfExists('bdgs_email_schedules');
        Schema::dropIfExists('bdgs_email_list_subscribers');
        Schema::dropIfExists('bdgs_email_lists');
        Schema::dropIfExists('bdgs_email_logs');
    }
};
