@extends('layouts.bdgs')

@php($showFab = false)
@php($activeNav = $activeNav ?? 'dashboard')

@section('title')
<title>@yield('account-title', 'Dashboard') — BD Growth Suite</title>
@endsection

@section('robots-content', 'noindex, nofollow')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-account.css">
@stack('account-styles')
@endpush

@section('content')
<section class="bdgs-account" aria-label="Account dashboard">
  <div class="bdgs-account__wrap">
    <div class="bdgs-account__layout">
      @include('partials.bdgs.account-sidebar')

      <div class="bdgs-account__main">
        <div class="bdgs-account__page-header">
          <h1 class="bdgs-account__page-title">@yield('account-heading', 'Dashboard')</h1>
          @hasSection('account-lead')
            <p class="bdgs-account__page-lead">@yield('account-lead')</p>
          @endif
        </div>

        @if (session('status'))
          <div class="bdgs-account__flash" role="status">{{ session('status') }}</div>
        @endif

        @yield('account-content')
      </div>
    </div>
  </div>
</section>
@endsection

@push('page-scripts')
<script>
(function() {
  document.querySelectorAll('.bdgs-account__nav-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!expanded));
    });
  });
})();
</script>
@stack('account-scripts')
@endpush
