@extends('layouts.admin')

@section('page-title', $filteredClinic ? 'Registrations — '.$filteredClinic->title : 'Zoom Clinic Registrations')

@section('content')
<div data-ajax-list="admin-zc-registrations">
  <form method="GET" action="{{ route('admin.zoom-clinic-registrations.index') }}" class="bdgs-panel-form" style="margin-bottom:20px;max-width:480px;">
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

  <table class="bdgs-panel-table">
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
          <td><a href="{{ route('admin.zoom-clinic-registrations.show', $registration) }}">View</a></td>
        </tr>
      @empty
        <tr><td colspan="6">No registrations yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  {{ $registrations->links() }}
</div>
@endsection
