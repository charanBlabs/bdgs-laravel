<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BdgsCategory;
use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use Database\Seeders\SolutionCategorySeeder;
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
            ->with('featuredMedia', 'seo')
            ->where('post_type_id', $dataType->id)
            ->published()
            ->where('visibility', 'public')
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('frontend.content.listing', compact('dataType', 'posts', 'type'));
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
            ->with('featuredMedia', 'seo', 'categories')
            ->where('post_type_id', $dataType->id)
            ->whereHas('categories', fn ($q) => $q->where('bdgs_categories.id', $category->id))
            ->published()
            ->where('visibility', 'public')
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->paginate(20);

        $categories = BdgsCategory::query()
            ->where('post_type_id', $dataType->id)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.solutions.category', compact('dataType', 'category', 'categories', 'posts', 'type'));
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
            ->with('featuredMedia', 'categories', 'tags', 'seo', 'author', 'meta')
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
                    ->with('featuredMedia')
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
