@extends('layouts.bdgs')

@section('title')
<title>{{ $post->seo?->meta_title ?? $post->title }} — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="{{ $post->seo?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? ''), 160) }}">
@endsection

@if ($post->seo?->robots)
@section('robots-content'){{ $post->seo->robots }}@endsection
@endif

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-services.css">
@endpush

@section('content')
<article class="bdgs-section">
  <div class="bdgs-container">
    @if ($post->featuredMedia)
      <img src="{{ $post->featuredMedia->url('large') }}" alt="{{ $post->featuredMedia->alt_text ?? $post->title }}" style="width:100%;max-height:420px;object-fit:cover;border-radius:12px;">
    @endif
    <h1>{{ $post->title }}</h1>
    <p style="color:#64748b;">Published {{ optional($post->published_at)->format('F j, Y') }}</p>
    <div>{!! $post->content !!}</div>
  </div>
</article>
@endsection
