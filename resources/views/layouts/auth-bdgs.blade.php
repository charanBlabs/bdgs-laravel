@extends('layouts.bdgs')

@php($showFab = false)

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-auth.css">
@endpush

@section('content')
<div class="auth-page">
  <main class="auth-main">
    <div class="auth-container{{ ($wide ?? false) ? ' auth-container--wide' : '' }}">
      <div class="auth-card">
        @yield('auth-content')
      </div>
    </div>
  </main>
</div>
@endsection
