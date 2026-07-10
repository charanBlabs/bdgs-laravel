@php($activeNav = 'solutions')

@extends('layouts.bdgs')

@section('title')
<title>{{ $category->name }} Solutions | BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="{{ $category->description ?? 'Browse '.$category->name.' solutions from BD Growth Suite.' }}">
@endsection

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
    <p class="bdgs-sol-count">Showing {{ $posts->firstItem() ?? 0 }}–{{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results</p>
    @if ($category->description)
      <p class="bdgs-sol-lead">{{ $category->description }}</p>
    @endif
  </div>
</section>

@if ($categories->isNotEmpty())
<section class="bdgs-sol-filter-bar" aria-label="Solution categories">
  <div class="container">
    <div class="bdgs-sol-filter-bar__inner">
      <a href="{{ url('/solutions') }}" class="bdgs-sol-filter-pill">All</a>
      @foreach ($categories as $hub)
        <a href="{{ url('/solutions/'.$hub->slug) }}" @class(['bdgs-sol-filter-pill', 'is-active' => $hub->id === $category->id])>{{ $hub->name }}</a>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="bdgsownv2-section bdgs-sol-listing">
  <div class="container">
    <div class="bdgs-sol-grid">
      @forelse ($posts as $post)
        @include('frontend.solutions.partials.card', ['post' => $post])
      @empty
        <p>No published solutions in this category yet.</p>
      @endforelse
    </div>
    {{ $posts->links() }}
  </div>
</section>
@endsection
