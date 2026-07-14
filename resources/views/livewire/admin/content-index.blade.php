<div data-bdgs-scroll-on-page>
  <div class="bdgs-content-toolbar">
    <div class="bdgs-content-filters">
      <select wire:model.live="perPage">
        @foreach ([5, 10, 25, 50] as $pp)
          <option value="{{ $pp }}">{{ $pp }} entries</option>
        @endforeach
      </select>
      <select wire:model.live="sort">
        <option value="newest">Newest</option>
        <option value="oldest">Oldest</option>
        <option value="updated_first">Updated (First)</option>
        <option value="updated_last">Updated (Last)</option>
        <option value="published_first">Published (First)</option>
        <option value="published_last">Published (Last)</option>
      </select>
      <select wire:model.live="status">
        <option value="">Status</option>
        <option value="published">Published</option>
        <option value="draft">Draft</option>
        <option value="scheduled">Scheduled</option>
        <option value="archived">Archived</option>
      </select>
      <input
        type="text"
        wire:key="content-q-{{ $filterEpoch }}"
        wire:model.live.debounce.300ms="q"
        placeholder="Filter by Keyword"
        class="bdgs-content-filters__search"
      >
    </div>
    <a href="{{ route('dashboard.content.create', $type) }}" class="bdgs-content-toolbar__new-btn">+ New {{ $dataType->name }}</a>
  </div>

  <div class="bdgs-content-list" wire:loading.class="opacity-60">
    @forelse ($posts as $post)
      <div class="bdgs-content-item" wire:key="post-{{ $post->id }}">
        <div class="bdgs-content-item__thumb">
          @php($statusClass = match($post->status) { 'published' => 'published', 'draft' => 'draft', 'scheduled' => 'scheduled', default => 'archived' })
          <span class="bdgs-content-item__status bdgs-content-item__status--{{ $statusClass }}">{{ ucfirst($post->status) }}</span>
          @if ($post->featuredMedia)
            @php($media = $post->featuredMedia)
            <img
              src="{{ $media->url('medium') }}"
              srcset="{{ $media->srcset(['medium', 'large']) }}"
              sizes="160px"
              alt="{{ $post->title }}"
              class="bdgs-content-item__img"
              loading="lazy"
              decoding="async"
              width="{{ $media->dimensions('medium')[0] ?? '' }}"
              height="{{ $media->dimensions('medium')[1] ?? '' }}"
            >
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
      <div class="bdgs-content-empty bdgs-content-empty--inline" wire:key="content-empty-filters">
        <p>No posts match your filters.</p>
        <button
          type="button"
          class="bdgs-content-empty__btn bdgs-content-empty__btn--small"
          wire:click="clearFilters"
        >Clear Filters</button>
      </div>
    @endforelse
  </div>

  @if ($posts->hasPages())
    <div class="bdgs-content-pagination">
      {{ $posts->links() }}
    </div>
  @endif
</div>
