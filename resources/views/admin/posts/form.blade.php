@extends('layouts.admin')

@section('page-title', '')

@section('content')
@if ($post->exists)
  <div class="bdgs-sf__tabs">
    <a href="{{ route('admin.posts.edit', [$type, $post]) }}" class="bdgs-sf__tab bdgs-sf__tab--active">Edit {{ $dataType->name }}</a>
    <a href="{{ route('admin.posts.thumbnail', [$type, $post]) }}" class="bdgs-sf__tab">Upload Thumbnail</a>
  </div>
@endif

<form method="POST" action="{{ $post->exists ? route('admin.posts.update', [$type, $post]) : route('admin.posts.store', $type) }}" class="bdgs-panel-form bdgs-panel-form--solution">
  @csrf
  @if ($post->exists) @method('PUT') @endif

  <div class="bdgs-sf__page-header">
    <h2 class="bdgs-sf__page-title">{{ $post->exists ? 'Edit Details Below' : 'Enter Details Below' }}</h2>
    @if ($post->exists && $post->status === 'published')
      <a href="{{ url(($type === 'blog' ? '/blog/' : '/solutions/') . $post->slug) }}" target="_blank" class="bdgs-sf__view-link">View post</a>
    @endif
  </div>
  <hr class="bdgs-sf__divider">

  @if ($type === 'solution')
    @include('admin.posts.partials.solution-fields')

    <input type="hidden" name="visibility" value="{{ old('visibility', $post->visibility ?? 'public') }}">

    <div class="bdgs-sf">
      <div class="bdgs-sf__section-divider">
        <h3 class="bdgs-sf__section-title">SEO</h3>
      </div>

      <div class="bdgs-sf__field">
        <label class="bdgs-sf__label" for="sf-meta-title">Meta Title</label>
        <input type="text" id="sf-meta-title" name="meta_title" class="bdgs-sf__input" value="{{ old('meta_title', $post->seo?->meta_title) }}">
      </div>

      <div class="bdgs-sf__field">
        <label class="bdgs-sf__label" for="sf-meta-desc">Meta Description</label>
        <textarea id="sf-meta-desc" name="meta_description" class="bdgs-sf__textarea" rows="2">{{ old('meta_description', $post->seo?->meta_description) }}</textarea>
      </div>

      <div class="bdgs-sf__field">
        <label class="bdgs-sf__label" for="sf-robots">Robots</label>
        <input type="text" id="sf-robots" name="robots" class="bdgs-sf__input" value="{{ old('robots', $post->seo?->robots) }}" placeholder="index,follow">
      </div>

      <div class="bdgs-sf__actions">
        <button type="submit" class="bdgs-sf__save-btn">{{ $post->exists ? 'Update Solution' : 'Save' }}</button>
        <a href="{{ route('admin.posts.index', 'solution') }}" class="bdgs-sf__back-link">Back to Solutions</a>
      </div>
    </div>
  @else
    <label>Title<input type="text" name="title" value="{{ old('title', $post->title) }}" required></label>
    <label>Slug<input type="text" name="slug" value="{{ old('slug', $post->slug) }}"></label>
    <label>Excerpt<textarea name="excerpt">{{ old('excerpt', $post->excerpt) }}</textarea></label>
    <label>Content<textarea id="bdgs-content-editor" name="content" rows="12">{{ old('content', $post->content) }}</textarea></label>
    <label>Status
      <select name="status">
        @foreach (['draft','published','scheduled','archived'] as $status)
          <option value="{{ $status }}" @selected(old('status', $post->status) === $status)>{{ ucfirst($status) }}</option>
        @endforeach
      </select>
    </label>
    <label>Visibility
      <select name="visibility">
        @foreach (['public','private','members_only'] as $visibility)
          <option value="{{ $visibility }}" @selected(old('visibility', $post->visibility) === $visibility)>{{ $visibility }}</option>
        @endforeach
      </select>
    </label>
    <label>Featured Media ID<input type="number" name="featured_media_id" value="{{ old('featured_media_id', $post->featured_media_id) }}"></label>
    <label>Published At<input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}"></label>
    <label><input type="checkbox" name="pinned" value="1" @checked(old('pinned', $post->pinned))> Pinned</label>
    @if ($categories->isNotEmpty())
      <fieldset>
        <legend>Categories</legend>
        @foreach ($categories as $category)
          <label><input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, old('categories', $post->categories?->pluck('id')->all() ?? [])))> {{ $category->name }}</label>
        @endforeach
      </fieldset>
    @endif

    <input type="hidden" name="visibility" value="{{ old('visibility', $post->visibility ?? 'public') }}">

    <h3>SEO</h3>
    <label>Meta Title<input type="text" name="meta_title" value="{{ old('meta_title', $post->seo?->meta_title) }}"></label>
    <label>Meta Description<textarea name="meta_description">{{ old('meta_description', $post->seo?->meta_description) }}</textarea></label>
    <label>Robots<input type="text" name="robots" value="{{ old('robots', $post->seo?->robots) }}" placeholder="index,follow"></label>

    <button type="submit" class="bdgs-btn">Save</button>
  @endif
</form>

@push('panel-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
@push('panel-scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/js/bdgs-admin-editor.js"></script>
@if ($type === 'solution')
<script>
(function () {
  var select = document.getElementById('bdgs-pricing-type');
  if (select && window.bdgsInitPricingFields) {
    window.bdgsInitPricingFields(select);
  }
})();
</script>
@endif
@endpush
@endsection
