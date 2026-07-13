@extends('layouts.bdgs')

@php($activeNav = 'solutions')

@section('ai-summary')
@include('frontend.solutions.ai-summary')
@endsection

@section('title')
<title>{{ $totalSolutions }}+ Brilliant Directories Solutions (Done-For-You) — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Browse {{ $totalSolutions }}+ done-for-you Brilliant Directories solutions — SEO, lead gen, profiles, search, design, content, integrations, and member management.">
<link rel="canonical" href="https://bdgrowthsuite.com/solutions/">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $totalSolutions }}+ Brilliant Directories Solutions (Done-For-You) — BD Growth Suite">
<meta property="og:description" content="Browse {{ $totalSolutions }}+ done-for-you Brilliant Directories solutions — SEO, lead gen, profiles, search, design, content, integrations, and member management.">
<meta property="og:url" content="https://bdgrowthsuite.com/solutions/">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $totalSolutions }}+ Brilliant Directories Solutions (Done-For-You) — BD Growth Suite">
<meta name="twitter:description" content="Browse {{ $totalSolutions }}+ done-for-you Brilliant Directories solutions — SEO, lead gen, profiles, search, design, content, integrations, and member management.">
<meta name="twitter:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-customization.css">
<link rel="stylesheet" href="/css/bdgs-solutions.css">
@endpush

@section('content')
<section class="bdgsownv2-section bdgs-sol-hero">
  <div class="container">
    <nav class="bdgs-sol-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ url('/') }}">Home</a> <span aria-hidden="true">›</span> <span>Solutions</span>
    </nav>
    <h1>Brilliant Directories Solutions — Done-For-You</h1>
    <p class="bdgs-sol-lead">{{ $totalSolutions }}+ Brilliant Directories solutions we ship as scoped, done-for-you implementations — browse by category, then order the exact fix your site needs.</p>
  </div>
</section>

<section class="bdgsownv2-section bdgsownv2-cust-cats bdgs-sol-hub-cats">
  <div class="container">
    <div class="bdgsownv2-section-head">
      <h2>Browse by category</h2>
      <p>Eight categories cover SEO, lead gen, member profiles, search, page design, content, integrations, and member management. Prefer a full service path first? See <a href="/services">all services</a>.</p>
    </div>
    <div class="bdgsownv2-cat-grid">
      @foreach ($categories as $category)
        <a class="bdgsownv2-cat-card" href="{{ url('/solutions/'.$category->slug) }}">
          @include('frontend.solutions.partials.cat-icon', ['slug' => $category->slug])
          <div class="bdgsownv2-cat-title">{{ $category->name }}</div>
          <div class="bdgsownv2-cat-desc">{{ $category->description }}</div>
          <div class="bdgsownv2-cat-link">Browse {{ $category->posts_count }} Solutions →</div>
        </a>
      @endforeach
    </div>
  </div>
</section>

<section class="bdgsownv2-section bdgsownv2-mid-cta">
  <div class="container">
    <h2>Get a custom Brilliant Directories quote</h2>
    <p>Tell us what you're building. We'll match you with the right solution and quote you within 24 hours — or start from <a href="/customization">custom projects</a> if you need something outside this catalog.</p>
    <div class="bdgsownv2-hero-ctas">
      <button type="button" onclick="bdgsOpenInquiryModal()" class="bdgsownv2-btn-primary">Get a Custom Quote →</button>
      <a href="/customization" class="bdgsownv2-btn-secondary bdgsownv2-btn-ghost-on-dark">Learn About Custom Projects →</a>
    </div>
  </div>
</section>
@endsection

@push('page-schema')
@include('frontend.solutions.schema-hub')
@endpush
