<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bdgs_data_posts', function (Blueprint $table) {
            $table->string('pricing_type', 30)->nullable()->after('additional_fields');
            $table->decimal('price', 10, 2)->nullable()->after('pricing_type');
            $table->decimal('annual_price', 10, 2)->nullable()->after('price');
            $table->decimal('commitment_price', 10, 2)->nullable()->after('annual_price');
            $table->string('short_title', 255)->nullable()->after('commitment_price');
            $table->string('implementation_type', 50)->nullable()->after('short_title');
            $table->string('delivery_time', 50)->nullable()->after('implementation_type');
            $table->string('warranty', 100)->nullable()->after('delivery_time');
            $table->text('demo_video_url')->nullable()->after('warranty');
            $table->boolean('wysiwyg_cta')->default(false)->after('demo_video_url');
        });
    }

    public function down(): void
    {
        Schema::table('bdgs_data_posts', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_type',
                'price',
                'annual_price',
                'commitment_price',
                'short_title',
                'implementation_type',
                'delivery_time',
                'warranty',
                'demo_video_url',
                'wysiwyg_cta',
            ]);
        });
    }
};
