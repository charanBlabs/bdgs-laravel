@extends('layouts.auth-bdgs')

@section('title')
<title>Login — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="robots" content="noindex, nofollow">
@endsection

@section('auth-content')
<h1>Customer Login</h1>

@if (session('status'))
  <div class="bdgs-auth-banner bdgs-auth-banner--success" role="status">{{ session('status') }}</div>
@endif

<hr class="auth-divider" aria-hidden="true">

<form method="POST" action="{{ route('login') }}" class="bdgs-auth-form">
  @csrf

  <div class="bdgs-auth-field">
    <label for="email">Email Address<span class="bdgs-auth-required">*</span></label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
    @error('email')<span class="bdgs-auth-error" role="alert">{{ $message }}</span>@enderror
  </div>

  <div class="bdgs-auth-field">
    <label for="password">Password<span class="bdgs-auth-required">*</span></label>
    <div class="bdgs-auth-pw-wrap">
      <input type="password" id="password" name="password" required autocomplete="current-password">
      <button type="button" class="bdgs-auth-pw-toggle" aria-label="Show password" onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password';this.classList.toggle('bdgs-auth-pw-toggle--visible');this.setAttribute('aria-label',this.previousElementSibling.type==='password'?'Show password':'Hide password')">
        <svg class="bdgs-auth-pw-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
        <svg class="bdgs-auth-pw-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
      </button>
    </div>
    @error('password')<span class="bdgs-auth-error" role="alert">{{ $message }}</span>@enderror
  </div>

  <label class="bdgs-auth-checkbox">
    <input type="checkbox" name="remember"> Remember me
  </label>

  <p class="bdgs-auth-forgot">
    Did you forget your password?
    <a href="{{ route('password.request') }}">Reset your password</a>
  </p>

  <button type="submit" class="bdgsownv2-btn-primary bdgs-auth-submit">Log In</button>
</form>

<p class="auth-alt-action">
  Not a registered customer?
  <a href="{{ route('register') }}">Create an account</a>
</p>
@endsection
