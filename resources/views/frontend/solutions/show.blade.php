@extends('layouts.bdgs')

@php
  $activeNav = 'solutions';
  $category = $post->categories->first();
  $pageTitle = $post->seo?->meta_title ?: ($post->title.' | Brilliant Directories Solution');
  $metaDescription = $post->seo?->meta_description
    ?: \Illuminate\Support\Str::limit(strip_tags((string) ($post->excerpt ?: $post->content)), 155, '');
  if ($metaDescription === '') {
    $metaDescription = 'Get '.$post->title.' — a done-for-you Brilliant Directories solution from BD Growth Suite.';
  }
  $canonicalUrl = $post->seo?->canonical_url ?: url('/solutions/'.$post->slug);
@endphp

@section('title')
<title>{{ $pageTitle }} — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="{{ $metaDescription }}">
<meta property="og:title" content="{{ $pageTitle }} — BD Growth Suite">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:type" content="website">
@if ($post->featuredMedia)
<meta property="og:image" content="{{ url($post->featuredMedia->url('large')) }}">
@else
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
@endif
<link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@if ($post->seo?->robots)
@section('robots-content'){{ $post->seo->robots }}@endsection
@endif

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-solutions.css?v={{ time() }}">
@endpush

@push('page-scripts')
<script src="/js/bdgs-solutions.js" defer></script>
@endpush

@section('content')
<article class="bdgs-sol-detail">
  <div class="container">
    {{-- Breadcrumb --}}
    <nav class="bdgs-sol-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ url('/') }}">Home</a> <span aria-hidden="true">›</span>
      <a href="{{ url('/solutions') }}">Solutions</a>
      @if ($category)
        <span aria-hidden="true">›</span> <a href="{{ url('/solutions/'.$category->slug) }}">{{ $category->name }}</a>
      @endif
      <span aria-hidden="true">›</span> <span>{{ $post->title }}</span>
    </nav>

    <div class="bdgs-sol-detail__layout">
      {{-- Main content --}}
      <div class="bdgs-sol-detail__main">
        {{-- Posted on bar --}}
        <div class="bdgs-sol-detail__posted-bar">
          <div class="bdgs-sol-detail__posted-info">
            @if ($post->published_at)
              <span>Posted on {{ $post->published_at->format('m/d/Y') }}</span>
            @endif
            @if ($post->categories->isNotEmpty())
              <span>in @foreach ($post->categories as $cat)<a href="{{ url('/solutions/'.$cat->slug) }}">{{ $cat->name }}</a>@if (!$loop->last), @endif @endforeach</span>
            @endif
          </div>
          <button type="button" class="bdgs-sol-detail__bookmark" aria-label="Bookmark this solution">
            <span class="bdgs-sol-detail__bookmark-heart">&#x2764;</span> Bookmark
          </button>
        </div>

        {{-- Title --}}
        <h1 class="bdgs-sol-detail__title">{{ $post->title }}</h1>

        {{-- Featured image --}}
        @if ($post->featuredMedia)
          @php($hero = $post->featuredMedia)
          @php($heroDims = $hero->dimensions('large') ?: $hero->dimensions())
          @php($heroSrcset = $hero->srcset(['medium', 'large']))
          <img
            class="bdgs-sol-detail__hero-img"
            src="{{ $hero->url('large') }}"
            srcset="{{ $heroSrcset }}"
            sizes="(max-width: 900px) 100vw, 720px"
            alt="{{ $hero->alt_text ?? $post->title }}"
            loading="eager"
            decoding="async"
            fetchpriority="high"
            width="{{ $heroDims[0] ?? '' }}"
            height="{{ $heroDims[1] ?? '' }}"
          >
        @endif

        {{-- Order / Demo buttons --}}
        <div class="bdgs-sol-detail__action-bar {{ $post->demo_video_url ? 'bdgs-sol-detail__action-bar--split' : '' }}">
          <button type="button" class="bdgs-sol-detail__action-btn bdgs-sol-detail__action-btn--order" onclick="bdgsOpenInquiryModal()">Order Now</button>
          @if ($post->demo_video_url)
            <template id="bdgs-demo-template">{!! $post->demoVideoEmbedHtml() !!}</template>
            <button type="button" class="bdgs-sol-detail__action-btn bdgs-sol-detail__action-btn--demo" data-bdgs-demo-open data-demo-template="bdgs-demo-template">View Demo</button>
          @endif
        </div>

        {{-- Content --}}
        <div class="bdgs-sol-detail__content-wrap" data-bdgs-content-wrap>
          <div class="bdgs-sol-detail__content-clip" data-bdgs-content-clip>
            <div class="bdgs-sol-detail__content bdgs-sol-prose" data-bdgs-content-body>
              {!! $post->content !!}
            </div>
            <div class="bdgs-sol-detail__content-fade" data-bdgs-content-fade aria-hidden="true"></div>
          </div>
          <button type="button" class="bdgs-sol-detail__show-more" data-bdgs-show-more hidden>Show More</button>
        </div>

        @if ($post->wysiwyg_cta)
          <section class="bdgs-sol-wysiwyg-cta">
            <h2>What You See Is What You Get</h2>
            <p>Every screenshot and demo on this page reflects the actual implementation we deliver — no mockups, no surprises after purchase.</p>
            <button type="button" class="bdgs-sol-btn bdgs-sol-btn--primary" onclick="bdgsOpenInquiryModal()">Get This Solution</button>
          </section>
        @endif

        {{-- Bottom Order button --}}
        <div class="bdgs-sol-detail__action-bar {{ $post->demo_video_url ? 'bdgs-sol-detail__action-bar--split' : '' }}">
          <button type="button" class="bdgs-sol-detail__action-btn bdgs-sol-detail__action-btn--order" onclick="bdgsOpenInquiryModal()">Order Now</button>
          @if ($post->demo_video_url)
            <button type="button" class="bdgs-sol-detail__action-btn bdgs-sol-detail__action-btn--demo" data-bdgs-demo-open data-demo-template="bdgs-demo-template">View Demo</button>
          @endif
        </div>
      </div>

      {{-- Sidebar --}}
      <aside class="bdgs-sol-sidebar" aria-label="Solution details">
        {{-- Pricing card --}}
        <div class="bdgs-sol-sidebar__card">
          <p class="bdgs-sol-sidebar__title">{{ $post->short_title ?: $post->title }}</p>
          <hr class="bdgs-sol-sidebar__divider">

          {{-- Pricing row --}}
          @php($monthlyPrice = $post->price !== null ? '$' . number_format((float) $post->price, 2) : null)
          @php($annualPrice = $post->annual_price ? '$' . number_format((float) $post->annual_price, 2) : null)
          @php($savings = ($post->price && $post->annual_price) ? (int) round((float) $post->price * 12 - (float) $post->annual_price) : 0)
          @php($saveBadge = $savings > 0 ? '$' . $savings : '')
          <div class="bdgs-sol-sidebar__spec-row">
            <span class="bdgs-sol-sidebar__spec-label">{{ $post->pricingLabel() }}</span>
            <span class="bdgs-sol-sidebar__price-block">
              @if ($post->pricing_type === 'subscription')
                @if ($monthlyPrice)
                  <span class="bdgs-sol-sidebar__price-line"><span class="bdgs-sol-sidebar__price">{{ $monthlyPrice }}</span> <span class="bdgs-sol-sidebar__price-period">/ Monthly</span></span>
                @endif
                @if ($annualPrice)
                  <span class="bdgs-sol-sidebar__price-or">or</span>
                  @if ($saveBadge)
                    <span class="bdgs-sol-sidebar__price-line"><span class="bdgs-sol-sidebar__price">{{ $annualPrice }}</span> <span class="bdgs-sol-sidebar__price-period">/ Annually</span> <span class="bdgs-sol-sidebar__save-badge">Save {{ $saveBadge }}!</span></span>
                  @else
                    <span class="bdgs-sol-sidebar__price-line"><span class="bdgs-sol-sidebar__price">{{ $annualPrice }}</span> <span class="bdgs-sol-sidebar__price-period">/ Annually</span></span>
                  @endif
                @endif
              @else
                <span class="bdgs-sol-sidebar__price">{{ $post->formattedPrice() ?? 'Contact us' }}</span>
              @endif
            </span>
          </div>

          @if ($post->implementationLabel())
            <div class="bdgs-sol-sidebar__spec-row">
              <span class="bdgs-sol-sidebar__spec-label">Implementation:</span>
              <span class="bdgs-sol-sidebar__spec-value">{{ $post->implementationLabel() }}</span>
            </div>
          @endif

          @if ($post->deliveryLabel())
            <div class="bdgs-sol-sidebar__spec-row">
              <span class="bdgs-sol-sidebar__spec-label">Delivery:</span>
              <span class="bdgs-sol-sidebar__spec-value">{{ $post->deliveryLabel() }}</span>
            </div>
          @endif

          @if ($post->warranty)
            <div class="bdgs-sol-sidebar__spec-row">
              <span class="bdgs-sol-sidebar__spec-label">Warranty:</span>
              <span class="bdgs-sol-sidebar__spec-value">{{ $post->warranty }}</span>
            </div>
          @endif

          <button type="button" class="bdgs-sol-sidebar__order-btn" onclick="bdgsOpenInquiryModal()">Order Now</button>
        </div>

        {{-- License & Usage card --}}
        <div class="bdgs-sol-sidebar__card">
          <h3 class="bdgs-sol-sidebar__section-heading">License &amp; Usage</h3>
          <p class="bdgs-sol-sidebar__text">All BD Growth Suite tools and themes are covered by our Single Domain-Locked Commercial License (SDCL v1.0).</p>
          <ul class="bdgs-sol-sidebar__license-list">
            <li><strong>One License, One Domain:</strong> Use it on one BD site only.</li>
            <li><strong>Non-Transferable:</strong> You cannot duplicate, resell, or reuse it elsewhere.</li>
            <li><strong>Our Team Implements:</strong> Not a generic download — our team sets it up for you.</li>
            <li><strong>Adjustments:</strong> Minor or major tweaks if listed on the tool page; any extras follow what's stated there.</li>
            <li><strong>License Terms:</strong> <a href="{{ url('/license') }}">View Full License Terms</a></li>
          </ul>
        </div>

        {{-- Need Help card --}}
        <div class="bdgs-sol-sidebar__card">
          <h3 class="bdgs-sol-sidebar__section-heading">Need Help?</h3>
          <div class="bdgs-sol-sidebar__contact-row">
            <strong>WhatsApp:</strong> <a href="https://wa.me/917799285123">+91 77992 85123</a>
          </div>
          <div class="bdgs-sol-sidebar__contact-row">
            <strong>Email:</strong> <a href="mailto:support@bdgrowthsuite.com">support@bdgrowthsuite.com</a>
          </div>
          <p class="bdgs-sol-sidebar__text">Replies within 1 business day (usually much faster).</p>
          <p class="bdgs-sol-sidebar__text">We're here to help with setup, warranty, or license questions — just ask!</p>
        </div>

        {{-- Customers Also Used --}}
        @if ($relatedPosts->isNotEmpty())
          <div class="bdgs-sol-sidebar__card bdgs-sol-sidebar__card--related">
            <h3 class="bdgs-sol-sidebar__section-heading">Customers Also Used</h3>
            <div class="bdgs-sol-sidebar__related-list">
              @foreach ($relatedPosts as $related)
                <a href="{{ url('/solutions/'.$related->slug) }}" class="bdgs-sol-sidebar__related-item">
                  <div class="bdgs-sol-sidebar__related-thumb">
                    @if ($related->featuredMedia)
                      @php($relMedia = $related->featuredMedia)
                      @php($relDims = $relMedia->dimensions('large') ?: $relMedia->dimensions('medium'))
                      @php($relSrcset = $relMedia->srcset(['medium', 'large']))
                      <img
                        src="{{ $relMedia->url('large') }}"
                        srcset="{{ $relSrcset }}"
                        sizes="280px"
                        alt="{{ $related->short_title ?: $related->title }}"
                        loading="lazy"
                        decoding="async"
                        width="{{ $relDims[0] ?? '' }}"
                        height="{{ $relDims[1] ?? '' }}"
                      >
                    @else
                      <span class="bdgs-sol-sidebar__related-placeholder"></span>
                    @endif
                  </div>
                  <span class="bdgs-sol-sidebar__related-name">{{ $related->short_title ?: $related->title }}</span>
                </a>
              @endforeach
            </div>
          </div>
        @endif
      </aside>
    </div>
  </div>
</article>

{{-- Demo video modal --}}
<div class="bdgs-sol-demo-modal" id="bdgs-sol-demo-modal" hidden aria-hidden="true">
  <div class="bdgs-sol-demo-modal__backdrop" data-bdgs-demo-close></div>
  <div class="bdgs-sol-demo-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="bdgs-sol-demo-title">
    <button type="button" class="bdgs-sol-demo-modal__close" data-bdgs-demo-close aria-label="Close demo video">&times;</button>
    <h2 id="bdgs-sol-demo-title" class="bdgs-sr-only">Demo video</h2>
    <div class="bdgs-sol-demo-modal__embed" data-bdgs-demo-embed></div>
  </div>
</div>
@endsection
