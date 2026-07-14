@extends('layouts.bdgs')

@section('title')
<title>{{ $dataType->name }} — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="{{ $listingDescription }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $dataType->name }} — BD Growth Suite">
<meta property="og:description" content="{{ $listingDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $dataType->name }} — BD Growth Suite">
<meta name="twitter:description" content="{{ $listingDescription }}">
<meta name="twitter:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
@endsection

@if ($isEmpty)
@section('robots-content', 'noindex, follow')
@endif

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
              @php
                $media = $post->featuredMedia;
                $imgDims = $media->dimensions('large') ?: $media->dimensions('medium');
              @endphp
              <img
                src="{{ $media->url('large') }}"
                srcset="{{ $media->srcset(['medium', 'large']) }}"
                sizes="(max-width: 640px) 100vw, (max-width: 960px) 50vw, 25vw"
                alt="{{ $media->alt_text ?? $post->title }}"
                loading="lazy"
                decoding="async"
                width="{{ $imgDims[0] ?? 1200 }}"
                height="{{ $imgDims[1] ?? 800 }}"
                style="width:100%;aspect-ratio:3/2;object-fit:contain;object-position:center;background:#fff;border-radius:8px;display:block;"
              >
            @endif
            <h2><a href="{{ url('/'.$type.'/'.$post->slug) }}">{{ $post->title }}</a></h2>
            <p>{{ $post->excerpt }}</p>
          </article>
        @empty
          <p>No published content yet. Check back soon for new Brilliant Directories articles and resources.</p>
        @endforelse
      </div>
      {{ $posts->links() }}
    </div>
  </div>
</section>
@endsection
