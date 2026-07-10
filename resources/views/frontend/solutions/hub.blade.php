@extends('layouts.bdgs')

@php($activeNav = 'solutions')

@section('title')
<title>Solutions — Done-For-You Brilliant Directories Services | BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Browse {{ $totalSolutions }}+ done-for-you Brilliant Directories solutions — SEO, lead gen, profiles, search, design, content, integrations, and member management.">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-customization.css">
<link rel="stylesheet" href="/css/bdgs-solutions.css?v={{ time() }}">
@endpush

@section('content')
<section class="bdgsownv2-section bdgs-sol-hero">
  <div class="container">
    <nav class="bdgs-sol-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ url('/') }}">Home</a> <span aria-hidden="true">›</span> <span>Solutions</span>
    </nav>
    <h1>Done-For-You Solutions for Your Directory</h1>
    <p class="bdgs-sol-lead">{{ $totalSolutions }}+ scoped implementations we ship for Brilliant Directories site owners — browse by category, then order the exact solution you need.</p>
  </div>
</section>

<section class="bdgsownv2-section bdgsownv2-cust-cats bdgs-sol-hub-cats">
  <div class="container">
    <div class="bdgsownv2-section-head">
      <h2>What kind of solution do you need?</h2>
      <p>Eight categories cover every dimension of your directory — SEO, lead gen, member profiles, search, page design, content, integrations, and member management.</p>
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
    <h3>Not sure which category fits your needs?</h3>
    <p>Tell us what you're trying to build. We'll match you with the right solution and quote you within 24 hours.</p>
    <div class="bdgsownv2-hero-ctas">
      <button type="button" onclick="bdgsOpenInquiryModal()" class="bdgsownv2-btn-primary">Get a Custom Quote →</button>
      <a href="/customization" class="bdgsownv2-btn-secondary bdgsownv2-btn-ghost-on-dark">Learn About Custom Projects →</a>
    </div>
  </div>
</section>
@endsection
