<?php

namespace App\Http\Controllers;

use App\Models\BdgsCategory;
use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use Illuminate\Http\Response;

class SitemapPostsController extends Controller
{
    public function index(): Response
    {
        $types = BdgsDataType::query()->where('is_active', true)->get()->keyBy('id');

        $posts = BdgsDataPost::query()
            ->whereIn('post_type_id', $types->keys())
            ->published()
            ->where('visibility', 'public')
            ->get(['slug', 'post_type_id', 'updated_at']);

        $entries = [];

        foreach ($types as $type) {
            if ($type->slug !== 'solution') {
                continue;
            }

            $categories = BdgsCategory::query()
                ->where('post_type_id', $type->id)
                ->withCount(['posts' => fn ($q) => $q->published()->where('visibility', 'public')])
                ->orderBy('sort_order')
                ->get(['id', 'slug', 'updated_at']);

            foreach ($categories as $category) {
                if ((int) $category->posts_count === 0) {
                    continue;
                }

                $loc = e(url($type->publicBasePath().'/'.$category->slug));
                $lastmod = $category->updated_at?->toAtomString();
                $lastmodTag = $lastmod ? '<lastmod>'.e($lastmod).'</lastmod>' : '';
                $entries[] = <<<XML
            <url>
              <loc>{$loc}</loc>
              {$lastmodTag}
            </url>
            XML;
            }
        }

        foreach ($posts as $post) {
            /** @var BdgsDataType|null $type */
            $type = $types->get($post->post_type_id);
            if (! $type) {
                continue;
            }

            $loc = e(url($type->publicBasePath().'/'.$post->slug));
            $lastmod = $post->updated_at?->toAtomString();
            $lastmodTag = $lastmod ? '<lastmod>'.e($lastmod).'</lastmod>' : '';

            $entries[] = <<<XML
            <url>
              <loc>{$loc}</loc>
              {$lastmodTag}
            </url>
            XML;
        }

        $body = implode("\n", $entries);

        $xml = <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        {$body}
        </urlset>
        XML;

        return response(trim($xml), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
