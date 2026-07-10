@extends('layouts.account')

@section('account-heading', 'Inquiry #'.$inquiry->inquiry_id)
@section('account-lead', 'Review details and send a reply to the customer.')

@section('account-content')
<div class="bdgs-account-card">
  <p><strong>{{ $inquiry->name }}</strong> — {{ $inquiry->email }} — {{ $inquiry->phone }}</p>
  <p><strong>Need:</strong> {{ $inquiry->need }}</p>
  <p>{{ $inquiry->message }}</p>
</div>

<form method="POST" action="{{ route('dashboard.admin.inquiries.reply', $inquiry) }}" class="bdgs-account-form" style="margin-top:20px;">
  @csrf
  <label>Reply<textarea name="admin_reply" required>{{ old('admin_reply', $inquiry->admin_reply) }}</textarea></label>
  <button type="submit" class="bdgs-account-btn">Send Reply</button>
</form>
@endsection
