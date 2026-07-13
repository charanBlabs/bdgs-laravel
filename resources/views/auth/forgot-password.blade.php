@extends('layouts.auth-bdgs')

@section('title')
<title>Reset Password — BD Growth Suite</title>
@endsection

@section('auth-content')
<h1>Reset Password</h1>
<p class="auth-lead">Enter your email and we will send a reset link.</p>

@if (session('status'))
  <div class="bdgs-auth-banner bdgs-auth-banner--success bdgs-auth-banner--visible" role="status">{{ session('status') }}</div>
@endif

@if ($errors->any())
  <div class="bdgs-auth-banner bdgs-auth-banner--error bdgs-auth-banner--visible" role="alert">
    {{ $errors->first() }}
  </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="bdgs-auth-form">
  @csrf
  <div class="bdgs-auth-field">
    <label for="email">Email Address<span class="bdgs-auth-required">*</span></label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" @class(['bdgs-auth-input--error' => $errors->has('email')])>
    @error('email')<span class="bdgs-auth-error" role="alert">{{ $message }}</span>@enderror
  </div>
  <button type="submit" class="bdgsownv2-btn-primary bdgs-auth-submit">Send Reset Link</button>
</form>

<p class="auth-alt-action"><a href="{{ route('login') }}">Back to login</a></p>
@endsection
