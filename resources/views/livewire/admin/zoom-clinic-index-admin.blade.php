<div data-bdgs-scroll-on-page>
  <div class="bdgs-panel-form" style="margin-bottom:16px;max-width:280px;">
    <label>Status
      <select wire:model.live="status">
        <option value="active">Active (open)</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
        <option value="all">All clinics</option>
      </select>
    </label>
  </div>

  <table class="bdgs-panel-table" wire:loading.class="opacity-60">
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
        <tr wire:key="clinic-{{ $clinic->clinic_id }}">
          <td>{{ $clinic->title }}</td>
          <td>{{ $clinic->session_starts_at->format('M j, Y g:i A') }}</td>
          <td>{{ $clinic->access_type === 'public' ? 'Public' : 'Registered Only' }}</td>
          <td>{{ $clinic->displayLifecycleLabel() }}</td>
          <td>
            <a href="{{ route('admin.zoom-clinic-registrations.index', ['clinic_id' => $clinic->clinic_id]) }}">
              {{ $clinic->confirmed_registrations_count }}
            </a>
          </td>
          <td><a href="{{ route('admin.zoom-clinics.edit', $clinic->clinic_id) }}">Edit</a></td>
        </tr>
      @empty
        <tr><td colspan="6">No zoom clinics match this filter.</td></tr>
      @endforelse
    </tbody>
  </table>
  {{ $clinics->links() }}
</div>
