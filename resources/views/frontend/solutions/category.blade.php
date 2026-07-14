@extends('layouts.bdgs')

@php
  $activeNav = 'solutions';
@endphp

@section('title')
<title>{{ $category->name }} Solutions | BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="{{ $catDesc }}">
<meta property="og:title" content="{{ $category->name }} Solutions | BD Growth Suite">
<meta property="og:description" content="{{ $catDesc }}">
<meta property="og:type" content="website">
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
<link rel="canonical" href="{{ url('/solutions/'.$category->slug) }}">
@endsection

@if ($isEmpty)
@section('robots-content', 'noindex, follow')
@endif

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-solutions.css">
@endpush

@push('page-scripts')
<script src="/js/bdgs-solutions.js" defer></script>
@endpush

@section('content')
<section class="bdgsownv2-section bdgs-sol-hero">
  <div class="container">
    <nav class="bdgs-sol-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ url('/') }}">Home</a> <span aria-hidden="true">›</span>
      <a href="{{ url('/solutions') }}">Solutions</a> <span aria-hidden="true">›</span>
      <span>{{ $category->name }}</span>
    </nav>
    <h1>{{ $category->name }}</h1>
    @if ($category->description)
      <p class="bdgs-sol-lead">{{ $category->description }}</p>
    @endif
  </div>
</section>

<section class="bdgsownv2-section bdgs-sol-listing">
  <div class="container">
    <div data-ajax-list="public-solutions-{{ $category->slug }}">
      <div class="bdgs-sol-grid">
        @forelse ($posts as $post)
          @include('frontend.solutions.partials.card', ['post' => $post])
        @empty
          <p>No published solutions in this category yet. Browse <a href="{{ url('/solutions') }}">all Brilliant Directories solutions</a> or check back soon.</p>
        @endforelse
      </div>
      {{ $posts->links() }}
    </div>
  </div>
</section>
@endsection
