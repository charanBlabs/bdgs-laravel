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
        Schema::create('bdgs_blabs_reviews', function (Blueprint $table) {
            $table->integer('review_id')->autoIncrement();
            $table->string('marketplace_review_id', 64)->default('');
            $table->tinyInteger('overall_rating')->default(5);
            $table->tinyInteger('service_rating')->default(5);
            $table->tinyInteger('responsiveness_rating')->default(5);
            $table->tinyInteger('expertise_rating')->default(5);
            $table->tinyInteger('results_rating')->default(5);
            $table->tinyInteger('communication_rating')->default(5);
            $table->string('title', 500)->default('');
            $table->text('review_text');
            $table->date('review_date');
            $table->string('verify_link', 1000)->default('');
            $table->string('submitter_label', 120)->default('Verified Client');
            $table->integer('pmp_pid')->nullable();
            $table->tinyInteger('is_published')->default(1);
            $table->integer('sort_order')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('marketplace_review_id', 'uk_marketplace_review_id');
            $table->index('review_date', 'idx_review_date');
            $table->index('pmp_pid', 'idx_pmp_pid');
            $table->index(['is_published', 'sort_order', 'review_date'], 'idx_published_sort');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE bdgs_blabs_reviews ENGINE = MyISAM');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bdgs_blabs_reviews');
    }
};
