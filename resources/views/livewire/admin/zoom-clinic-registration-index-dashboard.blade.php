<div data-bdgs-scroll-on-page>
  <div class="bdgs-account-form" style="margin-bottom:20px;max-width:480px;">
    <label>Clinic
      <select wire:model.live="clinicId">
        <option value="">All clinics</option>
        @foreach ($clinics as $clinic)
          <option value="{{ $clinic->clinic_id }}">
            {{ $clinic->title }} — {{ $clinic->session_starts_at?->format('M j, Y') }}
          </option>
        @endforeach
      </select>
    </label>
  </div>

  @if ($filteredClinic)
    <p style="margin:0 0 12px;font-size:0.9375rem;">Showing registrations for <strong>{{ $filteredClinic->title }}</strong></p>
  @endif

  <div class="bdgs-account-table-wrap" wire:loading.class="opacity-60">
    <table class="bdgs-account-table">
      <thead>
        <tr>
          <th>Registered</th>
          <th>Name</th>
          <th>Email</th>
          <th>Directory</th>
          <th>Help with</th>
          <th>Clinic</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($registrations as $registration)
          <tr wire:key="reg-{{ $registration->registration_id }}">
            <td>{{ $registration->registered_at?->format('M j, Y g:i A') }}</td>
            <td>{{ $registration->name }}</td>
            <td>{{ $registration->email }}</td>
            <td>
              @if ($registration->directory_url)
                <a href="{{ $registration->directory_url }}" target="_blank" rel="noopener" title="{{ $registration->directory_url }}">{{ \Illuminate\Support\Str::limit($registration->directory_url, 32) }}</a>
              @else
                —
              @endif
            </td>
            <td>{{ $registration->help_topic ? \Illuminate\Support\Str::limit($registration->help_topic, 40) : '—' }}</td>
            <td>{{ $registration->clinic?->title ?? '—' }}</td>
            <td>{{ ucfirst($registration->status) }}</td>
            <td><a href="{{ route('dashboard.zoom-clinics.registrations.show', $registration) }}">View</a></td>
          </tr>
        @empty
          <tr><td colspan="8">No registrations yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px;">{{ $registrations->links() }}</div>
</div>
