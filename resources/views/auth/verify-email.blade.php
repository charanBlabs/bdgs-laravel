@extends('layouts.auth-bdgs')

@section('title')
<title>Verify Email — BD Growth Suite</title>
@endsection

@section('robots-content', 'noindex, nofollow')

@section('meta')
@endsection

@section('auth-content')
<h1>Verify your email</h1>
<p class="auth-lead">
  Thanks for signing up. Please confirm your email by clicking the link we sent to
  <strong>{{ auth()->user()->email }}</strong>. You can keep using your account while you verify.
</p>

@if (session('status') == 'verification-link-sent')
  <div class="bdgs-auth-banner bdgs-auth-banner--success bdgs-auth-banner--visible" role="status">
    A new verification link has been sent to your email address.
  </div>
@endif

<hr class="auth-divider" aria-hidden="true">

<div class="bdgs-auth-thanks-actions">
  <form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="bdgsownv2-btn-primary bdgs-auth-submit">Resend verification email</button>
  </form>

  <a href="{{ route('dashboard') }}" class="bdgs-auth-btn-secondary">Go to dashboard</a>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="bdgs-auth-link-btn">Log out</button>
  </form>
</div>
@endsection
