@extends('layouts.account')

@section('account-heading')
  <span class="bdgs-content-heading">
    <svg viewBox="0 0 20 20" fill="none" width="20" height="20"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    Zoom Clinics
    <span class="bdgs-content-heading__badge">{{ $registrations->total() }}</span>
  </span>
@endsection

@section('account-content')
@include('dashboard.zoom-clinics.partials.tabs', ['activeTab' => 'registrations'])

<div data-ajax-list="dashboard-zc-registrations">
  <form method="GET" action="{{ route('dashboard.zoom-clinics.registrations.index') }}" class="bdgs-account-form" style="margin-bottom:20px;max-width:480px;">
    <label>Clinic
      <select name="clinic_id" onchange="this.form.requestSubmit()">
        <option value="">All clinics</option>
        @foreach ($clinics as $clinic)
          <option value="{{ $clinic->clinic_id }}" @selected($clinicId === $clinic->clinic_id)>
            {{ $clinic->title }} — {{ $clinic->session_starts_at?->format('M j, Y') }}
          </option>
        @endforeach
      </select>
    </label>
  </form>

  @if ($filteredClinic)
    <p style="margin:0 0 12px;font-size:0.9375rem;">Showing registrations for <strong>{{ $filteredClinic->title }}</strong></p>
  @endif

  <div class="bdgs-account-table-wrap">
    <table class="bdgs-account-table">
      <thead>
        <tr>
          <th>Registered</th>
          <th>Name</th>
          <th>Email</th>
          <th>Clinic</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($registrations as $registration)
          <tr>
            <td>{{ $registration->registered_at?->format('M j, Y g:i A') }}</td>
            <td>{{ $registration->name }}</td>
            <td>{{ $registration->email }}</td>
            <td>{{ $registration->clinic?->title ?? '—' }}</td>
            <td>{{ ucfirst($registration->status) }}</td>
            <td><a href="{{ route('dashboard.zoom-clinics.registrations.show', $registration) }}">View</a></td>
          </tr>
        @empty
          <tr><td colspan="6">No registrations yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px;">{{ $registrations->links() }}</div>
</div>
@endsection

@push('account-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
