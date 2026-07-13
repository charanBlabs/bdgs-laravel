@extends('layouts.bdgs')
@php($showFab = false)

@section('title')
<title>Page Not Found — BD Growth Suite</title>
@endsection

@section('robots-content', 'noindex, nofollow')

@section('meta')
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-404.css">
@endpush

@section('content')
<section class="bdgs-404">
  {{-- Animated background layers --}}
  <div class="bdgs-404__pattern"></div>
  <div class="bdgs-404__grid"></div>
  <div class="bdgs-404__glow bdgs-404__glow--purple"></div>
  <div class="bdgs-404__glow bdgs-404__glow--indigo"></div>

  {{-- Floating particles --}}
  <div class="bdgs-404__particles">
    <span class="bdgs-404__particle"></span>
    <span class="bdgs-404__particle"></span>
    <span class="bdgs-404__particle"></span>
    <span class="bdgs-404__particle"></span>
    <span class="bdgs-404__particle"></span>
    <span class="bdgs-404__particle"></span>
    <span class="bdgs-404__particle"></span>
    <span class="bdgs-404__particle"></span>
  </div>

  {{-- Main content --}}
  <div class="bdgs-404__content">
    <div class="bdgs-404__logo-wrap">
      <img
        class="bdgs-404__logo"
        src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png"
        alt="BD Growth Suite"
        width="160"
        height="37"
      >
    </div>

    <h1 class="bdgs-404__code">404</h1>
    <p class="bdgs-404__title">Oops! This page doesn't exist.</p>
    <p class="bdgs-404__desc">
      The page you're looking for may have been moved, deleted, or never existed.
      Let's get you back on track.
    </p>

    <div class="bdgs-404__actions">
      <a href="{{ url('/') }}" class="bdgs-404__btn bdgs-404__btn--primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Go Home
      </a>
      <a href="{{ url('/services') }}" class="bdgs-404__btn bdgs-404__btn--secondary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Browse Services
      </a>
    </div>
  </div>
</section>
@endsection
