@extends('layouts.admin')

@section('page-title', '')

@section('content')
<div class="bdgs-sf__tabs">
  <a href="{{ route('admin.posts.edit', [$type, $post]) }}" class="bdgs-sf__tab">Edit {{ $dataType->name }}</a>
  <a href="{{ route('admin.posts.thumbnail', [$type, $post]) }}" class="bdgs-sf__tab bdgs-sf__tab--active">Upload Thumbnail</a>
</div>

<div class="bdgs-thumb">
  {{-- Current thumbnail --}}
  @if ($post->featuredMedia)
    <div class="bdgs-thumb__current">
      <h3 class="bdgs-thumb__heading">Current Thumbnail</h3>
      <div class="bdgs-thumb__preview">
        <img src="{{ $post->featuredMedia->url('medium') }}" alt="{{ $post->featuredMedia->alt_text ?? $post->title }}">
      </div>
      <div class="bdgs-thumb__meta">
        <span>{{ $post->featuredMedia->filename }}</span>
        <span>{{ $post->featuredMedia->width }}×{{ $post->featuredMedia->height }}px</span>
        <span>{{ number_format($post->featuredMedia->size_bytes / 1024, 1) }} KB</span>
        <span>{{ strtoupper(str_replace('image/', '', $post->featuredMedia->mime_type)) }}</span>
      </div>
      <form method="POST" action="{{ route('admin.posts.thumbnail.remove', [$type, $post]) }}" class="bdgs-thumb__remove-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="bdgs-thumb__remove-btn" onclick="return confirm('Remove this thumbnail?')">Remove Thumbnail</button>
      </form>
    </div>
  @endif

  {{-- Upload form --}}
  <div class="bdgs-thumb__upload-section">
    <h3 class="bdgs-thumb__heading">Upload Photos From Your Device</h3>

    <form method="POST" action="{{ route('admin.posts.thumbnail.upload', [$type, $post]) }}" enctype="multipart/form-data" class="bdgs-thumb__upload-form">
      @csrf

      <div class="bdgs-thumb__upload-area" id="bdgs-thumb-drop">
        <div class="bdgs-thumb__upload-icon">📷</div>
        <p class="bdgs-thumb__upload-label">Choose Images</p>
        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/gif,image/webp" class="bdgs-thumb__file-input" id="bdgs-thumb-file">
        <p class="bdgs-thumb__upload-hint" id="bdgs-thumb-filename">Select Images Then Confirm Upload</p>
      </div>

      <div class="bdgs-thumb__upload-notes">
        <ol>
          <li><strong>SELECT</strong> photos from your computer.</li>
          <li><strong>UPLOAD</strong> — images auto-convert to WebP.</li>
          <li><strong>SAVE</strong> your changes below.</li>
          <li><strong>NOTE</strong> max. of 10 MB at a time per upload.</li>
          <li><strong>SIZE</strong> 1200 by 640 pixels recommended.</li>
        </ol>
      </div>

      @error('thumbnail')
        <p class="bdgs-thumb__error">{{ $message }}</p>
      @enderror

      <button type="submit" class="bdgs-sf__save-btn" id="bdgs-thumb-submit">Upload &amp; Set as Thumbnail</button>
    </form>
  </div>
</div>

@push('panel-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
@push('panel-scripts')
<script>
(function() {
  var input = document.getElementById('bdgs-thumb-file');
  var label = document.getElementById('bdgs-thumb-filename');
  var drop = document.getElementById('bdgs-thumb-drop');
  if (!input) return;

  input.addEventListener('change', function() {
    label.textContent = input.files.length ? input.files[0].name : 'Select Images Then Confirm Upload';
  });

  ['dragenter','dragover'].forEach(function(e) {
    drop.addEventListener(e, function(ev) { ev.preventDefault(); drop.classList.add('bdgs-thumb__upload-area--dragover'); });
  });
  ['dragleave','drop'].forEach(function(e) {
    drop.addEventListener(e, function(ev) { ev.preventDefault(); drop.classList.remove('bdgs-thumb__upload-area--dragover'); });
  });
  drop.addEventListener('drop', function(ev) {
    if (ev.dataTransfer.files.length) {
      input.files = ev.dataTransfer.files;
      label.textContent = ev.dataTransfer.files[0].name;
    }
  });
})();
</script>
@endpush
@endsection
