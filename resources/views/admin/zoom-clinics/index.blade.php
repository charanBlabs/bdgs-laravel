@extends('layouts.admin')

@section('page-title', 'Zoom Clinics')

@section('content')
<p><a class="bdgs-btn" href="{{ route('admin.zoom-clinics.create') }}">New Zoom Clinic</a></p>

<div data-ajax-list="admin-zoom-clinics">
  <table class="bdgs-panel-table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Date & Time</th>
        <th>Access</th>
        <th>Status</th>
        <th>Registrations</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($clinics as $clinic)
        <tr>
          <td>{{ $clinic->title }}</td>
          <td>{{ $clinic->session_starts_at->format('M j, Y g:i A') }}</td>
          <td>{{ $clinic->access_type === 'public' ? 'Public' : 'Registered Only' }}</td>
          <td>{{ ucfirst($clinic->status) }}</td>
          <td>
            <a href="{{ route('admin.zoom-clinic-registrations.index', ['clinic_id' => $clinic->clinic_id]) }}">
              {{ $clinic->confirmed_registrations_count }}
            </a>
          </td>
          <td><a href="{{ route('admin.zoom-clinics.edit', $clinic->clinic_id) }}">Edit</a></td>
        </tr>
      @empty
        <tr><td colspan="6">No zoom clinics yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  {{ $clinics->links() }}
</div>
@endsection
