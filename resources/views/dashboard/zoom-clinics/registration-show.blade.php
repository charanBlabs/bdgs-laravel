@extends('layouts.account')

@section('account-heading')
  <span class="bdgs-content-heading">
    <svg viewBox="0 0 20 20" fill="none" width="20" height="20"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    Registration #{{ $registration->registration_id }}
  </span>
@endsection

@section('account-content')
@include('dashboard.zoom-clinics.partials.tabs', ['activeTab' => 'registrations'])

<div class="bdgs-account-card">
  <p><strong>{{ $registration->name }}</strong> — {{ $registration->email }}</p>
  <p><strong>Clinic:</strong>
    @if ($registration->clinic)
      <a href="{{ route('dashboard.zoom-clinics.edit', $registration->clinic->clinic_id) }}">{{ $registration->clinic->title }}</a>
      ({{ $registration->clinic->session_starts_at?->format('M j, Y g:i A') }})
    @else
      —
    @endif
  </p>
  <p><strong>Status:</strong> {{ ucfirst($registration->status) }}</p>
  <p><strong>Registered:</strong> {{ $registration->registered_at?->format('M j, Y g:i A') ?? '—' }}</p>
  <p><strong>Timezone:</strong> {{ $registration->registrant_timezone ?: '—' }}</p>
  <p><strong>Directory URL:</strong>
    @if ($registration->directory_url)
      <a href="{{ $registration->directory_url }}" target="_blank" rel="noopener">{{ $registration->directory_url }}</a>
    @else
      —
    @endif
  </p>
  <p><strong>What do you need help with?</strong> {{ $registration->help_topic ?: '—' }}</p>
</div>

<div class="bdgs-account-card" style="margin-top:16px;">
  <p><strong>Confirmation sent:</strong> {{ $registration->confirmation_sent_at?->format('M j, Y g:i A') ?? 'Not sent' }}</p>
  <p><strong>24h reminder:</strong> {{ $registration->reminder_24h_sent_at?->format('M j, Y g:i A') ?? 'Not sent' }}</p>
  <p><strong>1h reminder:</strong> {{ $registration->reminder_1h_sent_at?->format('M j, Y g:i A') ?? 'Not sent' }}</p>
</div>

<p style="margin-top:20px;">
  <a href="{{ route('dashboard.zoom-clinics.registrations.index', array_filter(['clinic_id' => $registration->clinic_id])) }}" class="bdgs-account-btn bdgs-account-btn--ghost">Back to registrations</a>
</p>
@endsection

@push('account-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
