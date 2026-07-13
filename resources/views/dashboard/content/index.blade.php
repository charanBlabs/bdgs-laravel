@extends('layouts.account')

@section('account-heading')
  <span class="bdgs-content-heading">
    @include('partials.bdgs.post-type-icon', ['slug' => $type])
    {{ $dataType->pluralLabel() }}
    <span class="bdgs-content-heading__badge">{{ $totalCount }}</span>
  </span>
@endsection

@section('account-content')
@if ($totalCount === 0 && ! request()->hasAny(['q', 'status']))
  {{-- Empty state --}}
  <div class="bdgs-content-empty">
    <div class="bdgs-content-empty__icon">
      <svg viewBox="0 0 64 64" fill="none"><path d="M12 16a4 4 0 014-4h32a4 4 0 014 4v32a4 4 0 01-4 4H16a4 4 0 01-4-4V16z" stroke="url(#ce-g)" stroke-width="2.5"/><path d="M24 24h16M24 32h16M24 40h8" stroke="url(#ce-g)" stroke-width="2.5" stroke-linecap="round"/><defs><linearGradient id="ce-g" x1="12" y1="12" x2="52" y2="52"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    </div>
    <h2 class="bdgs-content-empty__title">Publish Your First {{ $dataType->name }}</h2>
    <a href="{{ route('dashboard.content.create', $type) }}" class="bdgs-content-empty__btn">
      + New {{ $dataType->name }}
    </a>
  </div>
@else
  <div data-ajax-list="dashboard-content-{{ $type }}">
    {{-- Toolbar: filters + create button --}}
    <div class="bdgs-content-toolbar">
      <form method="GET" action="{{ route('dashboard.content.index', $type) }}" class="bdgs-content-filters">
        <select name="per_page" onchange="this.form.requestSubmit()">
          @foreach ([5, 10, 25, 50] as $pp)
            <option value="{{ $pp }}" @selected($perPage === $pp)>{{ $pp }} entries</option>
          @endforeach
        </select>
        <select name="sort" onchange="this.form.requestSubmit()">
          <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
          <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
          <option value="updated_first" @selected(request('sort') === 'updated_first')>Updated (First)</option>
          <option value="updated_last" @selected(request('sort') === 'updated_last')>Updated (Last)</option>
          <option value="published_first" @selected(request('sort') === 'published_first')>Published (First)</option>
          <option value="published_last" @selected(request('sort') === 'published_last')>Published (Last)</option>
        </select>
        <select name="status" onchange="this.form.requestSubmit()">
          <option value="">Status</option>
          <option value="published" @selected(request('status') === 'published')>Published</option>
          <option value="draft" @selected(request('status') === 'draft')>Draft</option>
          <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
          <option value="archived" @selected(request('status') === 'archived')>Archived</option>
        </select>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Filter by Keyword" class="bdgs-content-filters__search">
      </form>
      <a href="{{ route('dashboard.content.create', $type) }}" class="bdgs-content-toolbar__new-btn">+ New {{ $dataType->name }}</a>
    </div>

    {{-- Post listing --}}
    <div class="bdgs-content-list">
      @forelse ($posts as $post)
        <div class="bdgs-content-item">
          <div class="bdgs-content-item__thumb">
            @php($statusClass = match($post->status) { 'published' => 'published', 'draft' => 'draft', 'scheduled' => 'scheduled', default => 'archived' })
            <span class="bdgs-content-item__status bdgs-content-item__status--{{ $statusClass }}">{{ ucfirst($post->status) }}</span>
            @if ($post->featuredMedia)
              <img src="{{ $post->featuredMedia->url('thumb') }}" alt="{{ $post->title }}" class="bdgs-content-item__img">
            @else
              <span class="bdgs-content-item__no-img">No Image</span>
            @endif
          </div>
          <div class="bdgs-content-item__body">
            <h3 class="bdgs-content-item__title">
              <a href="{{ route('dashboard.content.edit', [$type, $post]) }}">{{ $post->title }}</a>
            </h3>
            @if ($post->excerpt)
              <p class="bdgs-content-item__excerpt">{{ Str::limit($post->excerpt, 160) }}</p>
            @endif
          </div>
          <div class="bdgs-content-item__meta">
            <div class="bdgs-content-item__actions-wrap">
              <button type="button" class="bdgs-content-item__actions-btn" aria-haspopup="true">Actions <svg viewBox="0 0 12 12" fill="none" width="12" height="12"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></button>
              <div class="bdgs-content-item__actions-menu">
                @if ($post->status === 'published')
                  <a href="{{ url($dataType->publicBasePath() . '/' . $post->slug) }}" target="_blank">View Post</a>
                @endif
                <a href="{{ route('dashboard.content.edit', [$type, $post]) }}">Edit Post</a>
                <form method="POST" action="{{ route('dashboard.content.clone', [$type, $post]) }}">@csrf<button type="submit">Clone Post</button></form>
                <form method="POST" action="{{ route('dashboard.content.destroy', [$type, $post]) }}" onsubmit="return confirm('Delete this {{ strtolower($dataType->name) }}?')">@csrf @method('DELETE')<button type="submit" class="bdgs-content-item__actions-danger">Delete</button></form>
              </div>
            </div>
            <p class="bdgs-content-item__date">Created: {{ $post->created_at->format('m/d/Y') }}</p>
            @if ($post->published_at)
              <p class="bdgs-content-item__date">Published: {{ $post->published_at->format('m/d/Y') }}</p>
            @endif
          </div>
        </div>
      @empty
        <div class="bdgs-content-empty bdgs-content-empty--inline">
          <p>No posts match your filters.</p>
          <a href="{{ route('dashboard.content.index', $type) }}" class="bdgs-content-empty__btn bdgs-content-empty__btn--small">Clear Filters</a>
        </div>
      @endforelse
    </div>

    @if ($posts->hasPages())
      <div class="bdgs-content-pagination">
        {{ $posts->links() }}
      </div>
    @endif
  </div>
@endif
@endsection
