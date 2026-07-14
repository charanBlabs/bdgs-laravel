<?php

/**
 * Export live solution posts (+ hub categories) to scripts/_solutions_list.json
 * for docs/seo-keyword-strategy-planner.xlsx.
 */

use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use Database\Seeders\Support\SolutionSqlParser;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$type = BdgsDataType::query()->where('slug', 'solution')->first();
$hubMap = require database_path('seeders/data/solutions_hub_map.php');

$fromDb = [];
if ($type) {
    $posts = BdgsDataPost::query()
        ->where('post_type_id', $type->id)
        ->with(['categories:id,slug,name'])
        ->orderBy('title')
        ->get(['id', 'slug', 'title', 'status', 'post_type_id']);

    foreach ($posts as $p) {
        $fromDb[] = [
            'slug' => $p->slug,
            'title' => $p->title,
            'status' => $p->status,
            'category' => $p->categories->pluck('slug')->first() ?: '',
            'source' => 'db',
        ];
    }
}

$parser = new SolutionSqlParser;
$records = $parser->parseFile(database_path('seeders/data/solutions_data.sql'));
$fromSql = [];
foreach ($records as $r) {
    $fromSql[] = [
        'slug' => $r['slug'],
        'title' => $r['title'],
        'status' => $r['status'],
        'category' => $hubMap[$r['title']] ?? '',
        'source' => 'sql+hub_map',
    ];
}

$list = count($fromDb) > 0 ? $fromDb : $fromSql;
if (count($fromDb) > 0 && count($fromDb) < count($fromSql)) {
    $bySlug = collect($fromDb)->keyBy('slug');
    foreach ($fromSql as $r) {
        if (! $bySlug->has($r['slug'])) {
            $bySlug[$r['slug']] = $r;
        }
    }
    $list = $bySlug->values()->all();
}

usort($list, fn ($a, $b) => [$a['category'], $a['title']] <=> [$b['category'], $b['title']]);

$path = __DIR__.'/_solutions_list.json';
file_put_contents($path, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
echo 'WROTE '.$path.' COUNT='.count($list)."\n";
