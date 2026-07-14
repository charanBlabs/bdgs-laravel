<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BdgsCategory;
use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use Database\Seeders\SolutionCategorySeeder;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function listing(string $type): View
    {
        $type = request()->route()->defaults['type'] ?? $type;

        if ($type === 'solution') {
            return $this->solutionsHub();
        }

        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();

        $posts = BdgsDataPost::query()
            ->with('featuredMedia.variants', 'seo')
            ->where('post_type_id', $dataType->id)
            ->published()
            ->where('visibility', 'public')
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->paginate(12);

        $listingDescription = $dataType->description
            ?: ('Browse '.$dataType->name.' from BD Growth Suite — practical Brilliant Directories guidance from expert developers.');
        $listingDescription = Str::limit(strip_tags($listingDescription), 155, '');
        $canonicalUrl = url('/'.$type);
        $isEmpty = $posts->total() === 0;

        return view('frontend.content.listing', compact(
            'dataType',
            'posts',
            'type',
            'listingDescription',
            'canonicalUrl',
            'isEmpty',
        ));
    }

    public function solutionsHub(): View
    {
        $dataType = BdgsDataType::query()->where('slug', 'solution')->where('is_active', true)->firstOrFail();
        $type = 'solution';

        $categories = BdgsCategory::query()
            ->where('post_type_id', $dataType->id)
            ->withCount(['posts' => fn ($q) => $q->published()->where('visibility', 'public')])
            ->orderBy('sort_order')
            ->get();

        $totalSolutions = BdgsDataPost::query()
            ->where('post_type_id', $dataType->id)
            ->published()
            ->where('visibility', 'public')
            ->count();

        return view('frontend.solutions.hub', compact('dataType', 'categories', 'type', 'totalSolutions'));
    }

    public function solutionsCategory(string $categorySlug): View
    {
        $dataType = BdgsDataType::query()->where('slug', 'solution')->where('is_active', true)->firstOrFail();
        $type = 'solution';

        $category = BdgsCategory::query()
            ->where('post_type_id', $dataType->id)
            ->where('slug', $categorySlug)
            ->firstOrFail();

        $posts = BdgsDataPost::query()
            ->with('featuredMedia.variants', 'seo', 'categories')
            ->where('post_type_id', $dataType->id)
            ->whereHas('categories', fn ($q) => $q->where('bdgs_categories.id', $category->id))
            ->published()
            ->where('visibility', 'public')
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->paginate(20);

        $catDesc = $category->description
            ?: ('Browse '.$category->name.' Brilliant Directories solutions from BD Growth Suite.');
        $catDesc = Str::limit(strip_tags($catDesc), 155, '');
        $isEmpty = $posts->total() === 0;

        return view('frontend.solutions.category', compact(
            'dataType',
            'category',
            'posts',
            'type',
            'catDesc',
            'isEmpty',
        ));
    }

    public function show(string $type, string $slug): View
    {
        $routeType = request()->route()->defaults['type'] ?? $type;
        $routeSlug = request()->route('slug') ?? $slug;

        if ($routeType === 'solution' && in_array($routeSlug, SolutionCategorySeeder::reservedSlugs(), true)) {
            return $this->solutionsCategory($routeSlug);
        }

        $dataType = BdgsDataType::query()->where('slug', $routeType)->firstOrFail();

        $post = BdgsDataPost::query()
            ->with('featuredMedia.variants', 'categories', 'tags', 'seo', 'author', 'meta')
            ->where('post_type_id', $dataType->id)
            ->where('slug', $routeSlug)
            ->published()
            ->firstOrFail();

        $post->increment('view_count');

        $relatedPosts = collect();
        if ($routeType === 'solution') {
            $categoryId = $post->categories->first()?->id;
            if ($categoryId) {
                $relatedPosts = BdgsDataPost::query()
                    ->with('featuredMedia.variants')
                    ->where('post_type_id', $dataType->id)
                    ->where('id', '!=', $post->id)
                    ->whereHas('categories', fn ($q) => $q->where('bdgs_categories.id', $categoryId))
                    ->published()
                    ->where('visibility', 'public')
                    ->limit(4)
                    ->get();
            }
        }

        $type = $routeType;
        $view = $type === 'solution' ? 'frontend.solutions.show' : 'frontend.content.show';

        return view($view, compact('dataType', 'post', 'type', 'relatedPosts'));
    }
}
