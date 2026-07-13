@extends('layouts.admin')

@section('page-title', 'Media Library')

@section('content')
<form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="bdgs-panel-form">
  @csrf
  <label>Upload File<input type="file" name="file" required></label>
  <label>Alt Text<input type="text" name="alt_text"></label>
  <button type="submit" class="bdgs-btn">Upload</button>
</form>

<div data-ajax-list="admin-media">
  <form method="GET" class="bdgs-panel-form" style="margin-top:16px;">
    <label>Search<input type="search" name="q" value="{{ $search }}"></label>
    <button type="submit" class="bdgs-btn bdgs-btn--secondary">Search</button>
  </form>

  <div class="bdgs-grid-cards" style="margin-top:24px;">
    @foreach ($media as $item)
      <div class="bdgs-card">
        @if (str_starts_with($item->mime_type, 'image/'))
          <img src="{{ $item->url('thumb') }}" alt="{{ $item->alt_text }}" style="width:100%;border-radius:8px;">
        @endif
        <p><strong>#{{ $item->id }}</strong> {{ $item->filename }}</p>
        <p style="font-size:12px;color:#64748b;">{{ $item->mime_type }}</p>
        <form method="POST" action="{{ route('admin.media.destroy', $item) }}" onsubmit="return confirm('Delete this file?')">
          @csrf @method('DELETE')
          <button type="submit" class="bdgs-btn bdgs-btn--secondary">Delete</button>
        </form>
      </div>
    @endforeach
  </div>
  {{ $media->links() }}
</div>
@endsection
