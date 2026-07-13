@extends('layouts.bdgs')

@section('title')
<title>{{ $dataType->name }} — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="{{ $dataType->description ?? 'Browse '.$dataType->name.' from BD Growth Suite.' }}">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-services.css">
@endpush

@section('content')
<section class="bdgs-section">
  <div class="bdgs-container">
    <h1>{{ $dataType->name }}</h1>
    <div data-ajax-list="public-{{ $type }}-listing">
      <div class="bdgs-grid-cards">
        @forelse ($posts as $post)
          <article class="bdgs-card">
            @if ($post->featuredMedia)
              <img src="{{ $post->featuredMedia->url('medium') }}" alt="{{ $post->featuredMedia->alt_text ?? $post->title }}" loading="lazy" style="width:100%;border-radius:8px;">
            @endif
            <h2><a href="{{ url('/'.$type.'/'.$post->slug) }}">{{ $post->title }}</a></h2>
            <p>{{ $post->excerpt }}</p>
          </article>
        @empty
          <p>No published content yet.</p>
        @endforelse
      </div>
      {{ $posts->links() }}
    </div>
  </div>
</section>
@endsection
