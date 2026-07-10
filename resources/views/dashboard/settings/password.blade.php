@extends('layouts.account')

@section('account-heading', 'Change Password')
@section('account-lead', 'Use a strong password you do not reuse on other sites.')

@section('account-content')
<form method="POST" action="{{ route('dashboard.settings.password.update') }}" class="bdgs-account-form">
  @csrf
  @method('PUT')
  <label>Current Password<input type="password" name="current_password" required autocomplete="current-password"></label>
  @error('current_password')<span class="bdgs-auth-error">{{ $message }}</span>@enderror
  <label>New Password<input type="password" name="password" required autocomplete="new-password"></label>
  @error('password')<span class="bdgs-auth-error">{{ $message }}</span>@enderror
  <label>Confirm Password<input type="password" name="password_confirmation" required autocomplete="new-password"></label>
  <button type="submit" class="bdgs-account-btn">Update Password</button>
</form>
@endsection
