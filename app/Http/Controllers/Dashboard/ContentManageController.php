<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Concerns\ManagesSolutionPosts;
use App\Http\Controllers\Controller;
use App\Models\BdgsDataMeta;
use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use App\Models\BdgsListSeo;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentManageController extends Controller
{
    use ManagesSolutionPosts;

    public function __construct()
    {
        Gate::authorize('manage-content');
    }

    public function index(string $type): View
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        $totalCount = BdgsDataPost::query()->where('post_type_id', $dataType->id)->count();

        return view('dashboard.content.index', compact('dataType', 'type', 'totalCount'));
    }

    public function create(string $type): View
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        $categories = $dataType->categories()->orderBy('sort_order')->get();

        return view('dashboard.content.form', [
            'dataType' => $dataType,
            'type' => $type,
            'post' => new BdgsDataPost(['status' => 'draft', 'visibility' => 'public']),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        $validated = $this->withPublishTimestamp($this->validatePost($request, $dataType, null));

        $post = BdgsDataPost::query()->create(array_merge($this->postFillAttributes($validated), [
            'post_type_id' => $dataType->id,
            'author_id' => Auth::id(),
            'slug' => ($validated['slug'] ?? null) ?: Str::slug($validated['title']),
        ]));

        if ($dataType->slug === 'solution') {
            $this->applySolutionFields($post, $validated, $request);
            $post->save();
        } else {
            $this->syncRelations($post, $request);
        }

        $this->syncSeo($post, $request);

        return redirect()->route('dashboard.content.edit', [$type, $post])->with('status', "{$dataType->name} created.");
    }

    public function edit(string $type, BdgsDataPost $post): View
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        abort_unless($post->post_type_id === $dataType->id, 404);

        $post->load('categories', 'tags', 'meta', 'seo', 'featuredMedia');
        $categories = $dataType->categories()->orderBy('sort_order')->get();

        return view('dashboard.content.form', compact('dataType', 'type', 'post', 'categories'));
    }

    public function update(Request $request, string $type, BdgsDataPost $post): RedirectResponse
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        abort_unless($post->post_type_id === $dataType->id, 404);

        $validated = $this->withPublishTimestamp($this->validatePost($request, $dataType, $post->id), $post);
        $post->update(array_merge($this->postFillAttributes($validated), [
            'slug' => ($validated['slug'] ?? null) ?: Str::slug($validated['title']),
        ]));

        if ($dataType->slug === 'solution') {
            $this->applySolutionFields($post, $validated, $request);
            $post->save();
        } else {
            $this->syncRelations($post, $request);
        }

        $this->syncSeo($post, $request);

        return back()->with('status', "{$dataType->name} updated.");
    }

    public function clone(string $type, BdgsDataPost $post): RedirectResponse
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        abort_unless($post->post_type_id === $dataType->id, 404);

        $post->load('categories', 'meta', 'seo');

        $clone = $post->replicate(['uuid', 'slug', 'view_count', 'published_at']);
        $clone->title = $post->title.' (Copy)';
        $clone->slug = Str::slug($clone->title).'-'.Str::random(4);
        $clone->status = 'draft';
        $clone->author_id = Auth::id();
        $clone->featured_media_id = $post->featured_media_id;
        $clone->save();

        $clone->categories()->sync($post->categories->pluck('id'));

        foreach ($post->meta as $meta) {
            BdgsDataMeta::query()->create([
                'post_id' => $clone->id,
                'key' => $meta->key,
                'value' => $meta->value,
            ]);
        }

        if ($post->seo) {
            BdgsListSeo::query()->create([
                'seoable_id' => $clone->id,
                'seoable_type' => BdgsDataPost::class,
                'meta_title' => $post->seo->meta_title,
                'meta_description' => $post->seo->meta_description,
                'og_title' => $post->seo->og_title,
                'og_description' => $post->seo->og_description,
                'robots' => $post->seo->robots,
            ]);
        }

        return redirect()->route('dashboard.content.edit', [$type, $clone])->with('status', "{$dataType->name} cloned as draft.");
    }

    public function thumbnail(string $type, BdgsDataPost $post): View
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        abort_unless($post->post_type_id === $dataType->id, 404);
        $post->load('featuredMedia.variants');

        return view('dashboard.content.thumbnail', compact('dataType', 'type', 'post'));
    }

    public function uploadThumbnail(Request $request, string $type, BdgsDataPost $post, MediaService $mediaService): RedirectResponse
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        abort_unless($post->post_type_id === $dataType->id, 404);

        $request->validate([
            'thumbnail' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);

        $media = $mediaService->uploadAsWebp($request->file('thumbnail'), $post->title);
        $this->replaceFeaturedMedia($post, $media, $mediaService);

        return back()->with('status', 'Thumbnail uploaded and converted to WebP.');
    }

    public function removeThumbnail(string $type, BdgsDataPost $post, MediaService $mediaService): RedirectResponse
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        abort_unless($post->post_type_id === $dataType->id, 404);

        $this->clearFeaturedMedia($post, $mediaService);

        return back()->with('status', 'Thumbnail removed.');
    }

    public function destroy(string $type, BdgsDataPost $post): RedirectResponse
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->where('is_active', true)->firstOrFail();
        abort_unless($post->post_type_id === $dataType->id, 404);

        $post->delete();

        return redirect()->route('dashboard.content.index', $type)->with('status', "{$dataType->name} deleted.");
    }

    /** @return array<string, mixed> */
    private function validatePost(Request $request, BdgsDataType $dataType, ?int $postId = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:500', 'unique:bdgs_data_posts,slug,'.($postId ?? 'NULL').',id,post_type_id,'.$dataType->id],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'visibility' => ['required', 'in:public,private,members_only'],
            'featured_media_id' => ['nullable', 'exists:bdgs_media,id'],
            'published_at' => ['nullable', 'date'],
            'pinned' => ['nullable', 'boolean'],
        ];

        if ($dataType->slug === 'solution') {
            $rules['status'] = ['nullable', 'in:draft,published,scheduled,archived'];
            $rules = array_merge($rules, $this->solutionFieldRules());
        } else {
            $rules['status'] = ['required', 'in:draft,published,scheduled,archived'];
        }

        $validated = $request->validate($rules);

        if ($dataType->slug === 'solution') {
            $validated['status'] = $request->input('live') === 'yes' ? 'published' : 'draft';
        }

        return $validated;
    }

    private function syncRelations(BdgsDataPost $post, Request $request): void
    {
        $post->categories()->sync($request->input('categories', []));
        $post->tags()->sync($request->input('tags', []));
    }

    private function syncSeo(BdgsDataPost $post, Request $request): void
    {
        if (
            ! $request->filled('meta_title')
            && ! $request->filled('meta_description')
            && ! $request->filled('robots')
            && ! $request->filled('canonical_url')
        ) {
            return;
        }

        BdgsListSeo::query()->updateOrCreate(
            ['seoable_id' => $post->id, 'seoable_type' => BdgsDataPost::class],
            [
                'meta_title' => $request->input('meta_title'),
                'meta_description' => $request->input('meta_description'),
                'og_title' => $request->input('og_title'),
                'og_description' => $request->input('og_description'),
                'canonical_url' => $request->input('canonical_url'),
                'robots' => $request->input('robots'),
            ]
        );
    }
}
