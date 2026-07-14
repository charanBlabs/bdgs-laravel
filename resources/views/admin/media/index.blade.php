@extends('layouts.admin')

@section('page-title', 'Media Library')

@section('content')
<form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="bdgs-panel-form">
  @csrf
  <label>Upload File<input type="file" name="file" required></label>
  <label>Alt Text<input type="text" name="alt_text"></label>
  <button type="submit" class="bdgs-btn">Upload</button>
</form>

<livewire:admin.media-index />
@endsection
