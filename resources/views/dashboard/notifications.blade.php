@extends('layouts.account')

@section('account-heading', 'Notifications')
@section('account-lead', 'Updates about your account and project inquiries.')

@section('account-content')
<div class="bdgs-account-card">
  @forelse ($notifications as $notification)
    <div style="padding:12px 0;border-bottom:1px solid var(--bdgs-border);">
      <p style="margin:0 0 4px;font-weight:700;">{{ $notification->title }}</p>
      @if ($notification->body)
        <p style="margin:0 0 4px;">{{ $notification->body }}</p>
      @endif
      <p style="margin:0;font-size:12px;color:var(--bdgs-text-muted);">{{ $notification->created_at->format('M j, Y g:i A') }}</p>
    </div>
  @empty
    <p>No notifications yet.</p>
  @endforelse
</div>
@endsection
