@extends('layouts.auth-bdgs')

@php($wide = true)

@section('title')
<title>Create Account — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="robots" content="noindex, nofollow">
@endsection

@section('auth-content')
<h1>Create Your Account</h1>
<p class="auth-lead">Register as a BD Growth Suite customer to manage your Brilliant Directories projects and orders.</p>

<hr class="auth-divider" aria-hidden="true">

<form method="POST" action="{{ route('register') }}" class="bdgs-auth-form">
  @csrf

  <div class="bdgs-auth-row bdgs-auth-row--2">
    <div class="bdgs-auth-field">
      <label for="first_name">First Name<span class="bdgs-auth-required">*</span></label>
      <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name">
      @error('first_name')<span class="bdgs-auth-error">{{ $message }}</span>@enderror
    </div>
    <div class="bdgs-auth-field">
      <label for="last_name">Last Name<span class="bdgs-auth-required">*</span></label>
      <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">
      @error('last_name')<span class="bdgs-auth-error">{{ $message }}</span>@enderror
    </div>
  </div>

  <div class="bdgs-auth-field">
    <label for="email">Email Address<span class="bdgs-auth-required">*</span></label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
    @error('email')<span class="bdgs-auth-error">{{ $message }}</span>@enderror
  </div>

  <div class="bdgs-auth-field">
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
    @error('phone')<span class="bdgs-auth-error">{{ $message }}</span>@enderror
  </div>

  <div class="bdgs-auth-field">
    <label for="password">Create Password<span class="bdgs-auth-required">*</span></label>
    <div class="bdgs-auth-pw-wrap">
      <input type="password" id="password" name="password" required autocomplete="new-password">
      <button type="button" class="bdgs-auth-pw-toggle" aria-label="Show password" onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password';this.classList.toggle('bdgs-auth-pw-toggle--visible');this.setAttribute('aria-label',this.previousElementSibling.type==='password'?'Show password':'Hide password')">
        <svg class="bdgs-auth-pw-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
        <svg class="bdgs-auth-pw-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
      </button>
    </div>
    @error('password')<span class="bdgs-auth-error">{{ $message }}</span>@enderror
  </div>

  <div class="bdgs-auth-field">
    <label for="password_confirmation">Confirm Password<span class="bdgs-auth-required">*</span></label>
    <div class="bdgs-auth-pw-wrap">
      <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
      <button type="button" class="bdgs-auth-pw-toggle" aria-label="Show password" onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password';this.classList.toggle('bdgs-auth-pw-toggle--visible');this.setAttribute('aria-label',this.previousElementSibling.type==='password'?'Show password':'Hide password')">
        <svg class="bdgs-auth-pw-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
        <svg class="bdgs-auth-pw-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
      </button>
    </div>
  </div>

  <button type="submit" class="bdgsownv2-btn-primary bdgs-auth-submit">Create Account</button>
</form>

<p class="auth-alt-action">
  Already have an account?
  <a href="{{ route('login') }}">Log in</a>
</p>
@endsection
