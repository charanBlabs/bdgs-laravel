@extends('layouts.bdgs')

@php
  $pageTitle = $post->seo?->meta_title
    ?: ($post->title.' | '.($type === 'tools' ? 'Brilliant Directories Tools' : 'Brilliant Directories Tips'));
  $metaDescription = $post->seo?->meta_description
    ?: \Illuminate\Support\Str::limit(strip_tags((string) ($post->excerpt ?: $post->content)), 155, '');
  if ($metaDescription === '') {
    $metaDescription = 'Read '.$post->title.' from BD Growth Suite — Brilliant Directories experts.';
  }
  $canonicalUrl = $post->seo?->canonical_url ?: url('/'.$type.'/'.$post->slug);
@endphp

@section('title')
<title>{{ $pageTitle }} — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="{{ $metaDescription }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $pageTitle }} — BD Growth Suite">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }} — BD Growth Suite">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
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
      @php($media = $post->featuredMedia)
      @php($imgDims = $media->dimensions('large') ?: $media->dimensions('medium'))
      <img
        src="{{ $media->url('large') }}"
        srcset="{{ $media->srcset(['medium', 'large']) }}"
        sizes="(max-width: 960px) 100vw, 960px"
        alt="{{ $media->alt_text ?? $post->title }}"
        loading="eager"
        decoding="async"
        width="{{ $imgDims[0] ?? 1200 }}"
        height="{{ $imgDims[1] ?? 800 }}"
        style="width:100%;max-height:420px;aspect-ratio:3/2;object-fit:contain;object-position:center;background:#fff;border-radius:12px;"
      >
    @endif
    <h1>{{ $post->title }}</h1>
    <p style="color:#64748b;">Published {{ optional($post->published_at)->format('F j, Y') }}</p>
    <div>{!! $post->content !!}</div>
  </div>
</article>
@endsection
