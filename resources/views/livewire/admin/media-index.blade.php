<div data-bdgs-scroll-on-page>
  <div class="bdgs-panel-form" style="margin-top:16px;">
    <label>Search
      <input type="search" wire:model.live.debounce.300ms="q" placeholder="Search by filename or title">
    </label>
  </div>

  <div class="bdgs-grid-cards" style="margin-top:24px;" wire:loading.class="opacity-60">
    @forelse ($media as $item)
      <div class="bdgs-card" wire:key="media-{{ $item->id }}">
        @if (str_starts_with($item->mime_type, 'image/'))
          <div class="bdgs-panel-thumb bdgs-panel-thumb--card">
            <img
              src="{{ $item->url('medium') }}"
              srcset="{{ $item->srcset(['medium', 'large']) }}"
              sizes="(max-width: 640px) 100vw, 260px"
              alt="{{ $item->alt_text }}"
              loading="lazy"
              decoding="async"
              width="{{ $item->dimensions('medium')[0] ?? '' }}"
              height="{{ $item->dimensions('medium')[1] ?? '' }}"
            >
          </div>
        @endif
        <p><strong>#{{ $item->id }}</strong> {{ $item->filename }}</p>
        <p style="font-size:12px;color:#64748b;">{{ $item->mime_type }} · {{ $item->width }}×{{ $item->height }}px</p>
        <form method="POST" action="{{ route('admin.media.destroy', $item) }}" onsubmit="return confirm('Delete this file?')">
          @csrf @method('DELETE')
          <button type="submit" class="bdgs-btn bdgs-btn--secondary">Delete</button>
        </form>
      </div>
    @empty
      <p style="grid-column:1/-1;">No media matches your search.</p>
    @endforelse
  </div>
  {{ $media->links() }}
</div>
