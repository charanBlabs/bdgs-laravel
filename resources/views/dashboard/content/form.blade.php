@extends('layouts.account')

@section('account-heading', '')

@section('account-content')
@if ($post->exists)
  <div class="bdgs-sf__tabs">
    <a href="{{ route('dashboard.content.edit', [$type, $post]) }}" class="bdgs-sf__tab bdgs-sf__tab--active">Edit {{ $dataType->name }}</a>
    <a href="{{ route('dashboard.content.thumbnail', [$type, $post]) }}" class="bdgs-sf__tab">Upload Thumbnail</a>
  </div>
@endif

<form method="POST"
      action="{{ $post->exists ? route('dashboard.content.update', [$type, $post]) : route('dashboard.content.store', $type) }}"
      class="bdgs-profile-form @if($type === 'solution') bdgs-panel-form--solution @endif">
  @csrf
  @if ($post->exists) @method('PUT') @endif

  <div class="bdgs-sf__page-header">
    <h2 class="bdgs-sf__page-title">{{ $post->exists ? 'Edit Details Below' : 'Enter Details Below' }}</h2>
    @if ($post->exists && $post->status === 'published')
      <a href="{{ url(($type === 'blog' ? '/blog/' : '/' . $dataType->slug . 's/') . $post->slug) }}" target="_blank" class="bdgs-sf__view-link">View post</a>
    @endif
  </div>
  <hr class="bdgs-sf__divider">

  @if ($errors->any())
    <div class="bdgs-profile-form__section">
      <div class="bdgs-profile-form__alert bdgs-profile-form__alert--error">
        <p>Please fix the errors below.</p>
      </div>
    </div>
  @endif

  @if ($type === 'solution')
    <div class="bdgs-profile-form__section">
      @include('admin.posts.partials.solution-fields')
    </div>
  @else
    <div class="bdgs-profile-form__section">
      <h2 class="bdgs-profile-form__section-title">{{ $dataType->name }} Details</h2>

      <div class="bdgs-profile-form__row">
        <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_title">Title</label>
        <div class="bdgs-profile-form__field">
          <input type="text" id="pf_title" name="title" value="{{ old('title', $post->title) }}" required>
          @error('title') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="bdgs-profile-form__row">
        <label class="bdgs-profile-form__label" for="pf_slug">Slug</label>
        <div class="bdgs-profile-form__field">
          <input type="text" id="pf_slug" name="slug" value="{{ old('slug', $post->slug) }}" placeholder="Auto-generated from title">
        </div>
      </div>

      <div class="bdgs-profile-form__row">
        <label class="bdgs-profile-form__label" for="pf_excerpt">Excerpt</label>
        <div class="bdgs-profile-form__field">
          <textarea id="pf_excerpt" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>
      </div>

      <div class="bdgs-profile-form__row">
        <label class="bdgs-profile-form__label" for="pf_content">Content</label>
        <div class="bdgs-profile-form__field">
          <textarea id="bdgs-content-editor" name="content" rows="12">{{ old('content', $post->content) }}</textarea>
        </div>
      </div>
    </div>

    <div class="bdgs-profile-form__section">
      <h2 class="bdgs-profile-form__section-title">Publishing</h2>
      <div class="bdgs-profile-form__row">
        <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_status">Status</label>
        <div class="bdgs-profile-form__field">
          <select id="pf_status" name="status">
            @foreach (['draft','published','scheduled','archived'] as $status)
              <option value="{{ $status }}" @selected(old('status', $post->status) === $status)>{{ ucfirst($status) }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    @push('account-scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="/js/bdgs-admin-editor.js"></script>
    @endpush
  @endif

  <input type="hidden" name="visibility" value="{{ old('visibility', $post->visibility ?? 'public') }}">

  <div class="bdgs-profile-form__section">
    <h2 class="bdgs-profile-form__section-title">SEO</h2>
    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_meta_title">Meta Title</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_meta_title" name="meta_title" value="{{ old('meta_title', $post->seo?->meta_title) }}">
      </div>
    </div>
    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_meta_desc">Meta Description</label>
      <div class="bdgs-profile-form__field">
        <textarea id="pf_meta_desc" name="meta_description" rows="3">{{ old('meta_description', $post->seo?->meta_description) }}</textarea>
      </div>
    </div>
  </div>

  <div class="bdgs-profile-form__actions" style="display:flex;gap:12px;justify-content:center;">
    <button type="submit" class="bdgs-account-btn">{{ $post->exists ? 'Update' : 'Create' }} {{ $dataType->name }}</button>
    <a href="{{ route('dashboard.content.index', $type) }}" class="bdgs-account-btn bdgs-account-btn--ghost">Back to {{ $dataType->pluralLabel() }}</a>
  </div>
</form>
@endsection

@push('account-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush

@if ($type === 'solution')
@push('account-scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/js/bdgs-admin-editor.js"></script>
<script>
(function () {
  var select = document.getElementById('bdgs-pricing-type');
  if (select && window.bdgsInitPricingFields) {
    window.bdgsInitPricingFields(select);
  }
})();
</script>
@endpush
@endif
