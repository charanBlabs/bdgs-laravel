<?php

namespace App\Http\Controllers;

use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use Illuminate\Http\Response;

class SitemapPostsController extends Controller
{
    public function index(): Response
    {
        $types = BdgsDataType::query()->where('is_active', true)->pluck('slug', 'id');

        $posts = BdgsDataPost::query()
            ->whereIn('post_type_id', $types->keys())
            ->published()
            ->where('visibility', 'public')
            ->get(['slug', 'post_type_id', 'updated_at']);

        $entries = $posts->map(function (BdgsDataPost $post) use ($types): string {
            $type = $types[$post->post_type_id];
            $loc = e(url("/{$type}/{$post->slug}"));
            $lastmod = $post->updated_at?->toAtomString();
            $lastmodTag = $lastmod ? '<lastmod>'.e($lastmod).'</lastmod>' : '';

            return <<<XML
            <url>
              <loc>{$loc}</loc>
              {$lastmodTag}
            </url>
            XML;
        })->implode("\n");

        $xml = <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        {$entries}
        </urlset>
        XML;

        return response(trim($xml), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
