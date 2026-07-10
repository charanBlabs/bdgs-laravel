<?php

namespace Database\Seeders;

use App\Models\BdgsCategory;
use App\Models\BdgsDataMeta;
use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use Database\Seeders\Support\SolutionSqlParser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SolutionDataSeeder extends Seeder
{
    public function run(): void
    {
        $solutionType = BdgsDataType::query()->where('slug', 'solution')->firstOrFail();
        $sqlPath = database_path('seeders/data/solutions_data.sql');
        /** @var array<string, string> $hubMap */
        $hubMap = require database_path('seeders/data/solutions_hub_map.php');

        $parser = new SolutionSqlParser;
        $records = $parser->parseFile($sqlPath);

        $categories = BdgsCategory::query()
            ->where('post_type_id', $solutionType->id)
            ->pluck('id', 'slug');

        $adminId = \App\Models\User::query()->whereHas('roles', fn ($q) => $q->where('name', 'admin'))->value('id')
            ?? \App\Models\User::query()->value('id');

        foreach ($records as $record) {
            $excerpt = $record['excerpt'];
            if (! $excerpt && ! empty($record['content'])) {
                $excerpt = Str::limit(strip_tags($record['content']), 160);
            }

            $post = BdgsDataPost::query()->updateOrCreate(
                [
                    'post_type_id' => $solutionType->id,
                    'slug' => $record['slug'],
                ],
                [
                    'author_id' => $adminId,
                    'title' => $record['title'],
                    'excerpt' => $excerpt,
                    'content' => $record['content'],
                    'status' => $record['status'],
                    'visibility' => 'public',
                    'published_at' => $record['published_at'] ? now()->parse($record['published_at']) : now(),
                    'sort_order' => $record['group_order'],
                    'pinned' => $record['pinned'],
                    'pricing_type' => $record['pricing_type'],
                    'price' => $record['price'],
                    'annual_price' => $record['annual_price'],
                    'commitment_price' => $record['commitment_price'],
                    'short_title' => $record['short_title'],
                    'implementation_type' => $record['implementation_type'],
                    'delivery_time' => $record['delivery_time'],
                    'warranty' => $record['warranty'],
                    'demo_video_url' => $record['demo_video_url'],
                    'wysiwyg_cta' => false,
                ]
            );

            $this->syncMeta($post, $record);

            $hubSlug = $hubMap[$record['title']] ?? '';
            if ($hubSlug && isset($categories[$hubSlug])) {
                $post->categories()->sync([$categories[$hubSlug]]);
            } else {
                $post->categories()->detach();
            }
        }
    }

    /** @param array<string, mixed> $record */
    private function syncMeta(BdgsDataPost $post, array $record): void
    {
        $meta = [
            'old_group_id' => (string) $record['old_group_id'],
            'old_categories' => $record['old_categories'] ?? '',
            'product_type' => $record['product_type'] ?? '',
            'subscription_type' => $record['subscription_type'] ?? '',
            'starts_from_type' => $record['starts_from_type'] ?? '',
            'short_title_results' => $record['short_title_results'] ?? '',
        ];

        foreach ($meta as $key => $value) {
            if ($value === null || $value === '') {
                BdgsDataMeta::query()->where('post_id', $post->id)->where('key', $key)->delete();

                continue;
            }

            BdgsDataMeta::query()->updateOrCreate(
                ['post_id' => $post->id, 'key' => $key],
                ['value' => $value]
            );
        }
    }
}
