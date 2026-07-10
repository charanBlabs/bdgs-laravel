<?php

namespace App\Http\Controllers;

use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class FeedController extends Controller
{
    public function blog(): Response
    {
        $type = BdgsDataType::query()->where('slug', 'blog')->firstOrFail();

        $posts = BdgsDataPost::query()
            ->where('post_type_id', $type->id)
            ->published()
            ->where('visibility', 'public')
            ->latest('published_at')
            ->limit(20)
            ->get();

        $items = $posts->map(function (BdgsDataPost $post): string {
            $link = e(url('/blog/'.$post->slug));
            $title = e($post->title);
            $description = $post->excerpt ?? Str::limit(strip_tags($post->content ?? ''), 300);
            $pubDate = e(optional($post->published_at)->toRfc2822String());

            return <<<XML
            <item>
            <title>{$title}</title>
            <link>{$link}</link>
            <guid>{$link}</guid>
            <pubDate>{$pubDate}</pubDate>
            <description>{$this->cdata($description)}</description>
            </item>
            XML;
        })->implode("\n");

        $blogUrl = e(url('/blog'));
        $xml = <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <rss version="2.0">
        <channel>
        <title>BD Growth Suite Blog</title>
        <link>{$blogUrl}</link>
        <description>Latest articles from BD Growth Suite</description>
        {$items}
        </channel>
        </rss>
        XML;

        return response(trim($xml), 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }

    private function cdata(string $value): string
    {
        return '<![CDATA['.str_replace(']]>', ']]]]><![CDATA[>', $value).']]>';
    }
}
