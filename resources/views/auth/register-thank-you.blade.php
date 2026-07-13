@extends('layouts.auth-bdgs')

@section('title')
<title>Welcome — BD Growth Suite</title>
@endsection

@section('robots-content', 'noindex, nofollow')

@section('meta')
@endsection

@section('auth-content')
<div class="bdgs-auth-thanks">
  <h1>Thank you for signing up</h1>
  <p class="auth-lead">
    @if ($user)
      Your BD Growth Suite account is ready{{ $user->first_name ? ', '.$user->first_name : '' }}. Choose where to go next.
    @else
      Your account was created. Log in to open your dashboard.
    @endif
  </p>

  @if ($needsEmailVerification ?? false)
    <div class="bdgs-auth-banner bdgs-auth-banner--info bdgs-auth-banner--visible" role="status">
      Please verify your email. We sent a link to <strong>{{ $user->email }}</strong>. You can still use your account while you verify.
    </div>

    @if (session('status') == 'verification-link-sent')
      <div class="bdgs-auth-banner bdgs-auth-banner--success bdgs-auth-banner--visible" role="status">
        A new verification link has been sent.
      </div>
    @endif
  @endif

  <div class="bdgs-auth-thanks-actions">
    @if ($user)
      <a href="{{ route('dashboard.settings.profile') }}" class="bdgsownv2-btn-primary bdgs-auth-submit">Complete profile</a>
      <a href="{{ route('dashboard') }}" class="bdgs-auth-btn-secondary">Go to dashboard</a>

      @if ($needsEmailVerification ?? false)
        <form method="POST" action="{{ route('verification.send') }}" class="bdgs-auth-thanks-resend">
          @csrf
          <button type="submit" class="bdgs-auth-link-btn">Resend verification email</button>
        </form>
      @endif
    @else
      <a href="{{ route('login') }}" class="bdgsownv2-btn-primary bdgs-auth-submit">Log in</a>
    @endif
  </div>
</div>
@endsection
