@extends('layouts.account')

@section('account-heading', 'Contact Us')
@section('account-lead', 'Send a message to the BD Growth Suite team about your directory project.')

@section('account-content')
<form method="POST" action="{{ route('dashboard.contact') }}" class="bdgs-account-form">
  @csrf
  <label>Name<input type="text" name="name" value="{{ old('name', auth()->user()->fullName()) }}" required></label>
  <label>Email<input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required></label>
  <label>Phone<input type="text" name="phone" value="{{ old('phone', auth()->user()->profile?->phone) }}" required></label>
  <label>What do you need?
    <select name="need" required>
      @foreach ($needOptions as $option)
        <option value="{{ $option }}" @selected(old('need') === $option)>{{ $option }}</option>
      @endforeach
    </select>
  </label>
  <label>Message<textarea name="message">{{ old('message') }}</textarea></label>
  <button type="submit" class="bdgs-account-btn">Send Message</button>
</form>
@endsection
