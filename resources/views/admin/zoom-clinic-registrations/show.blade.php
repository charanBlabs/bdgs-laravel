@extends('layouts.admin')

@section('page-title', 'Registration #'.$registration->registration_id)

@section('content')
<div class="bdgs-card">
  <p><strong>{{ $registration->name }}</strong> — {{ $registration->email }}</p>
  <p><strong>Clinic:</strong>
    @if ($registration->clinic)
      <a href="{{ route('admin.zoom-clinics.edit', $registration->clinic->clinic_id) }}">{{ $registration->clinic->title }}</a>
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

<div class="bdgs-card" style="margin-top:16px;">
  <p><strong>Confirmation sent:</strong> {{ $registration->confirmation_sent_at?->format('M j, Y g:i A') ?? 'Not sent' }}</p>
  <p><strong>24h reminder:</strong> {{ $registration->reminder_24h_sent_at?->format('M j, Y g:i A') ?? 'Not sent' }}</p>
  <p><strong>1h reminder:</strong> {{ $registration->reminder_1h_sent_at?->format('M j, Y g:i A') ?? 'Not sent' }}</p>
</div>

<p style="margin-top:20px;">
  <a href="{{ route('admin.zoom-clinic-registrations.index', array_filter(['clinic_id' => $registration->clinic_id])) }}">Back to registrations</a>
</p>
@endsection
