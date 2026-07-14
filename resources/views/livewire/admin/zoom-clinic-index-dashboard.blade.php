<div data-bdgs-scroll-on-page>
  <div class="bdgs-content-toolbar">
    <div class="bdgs-content-filters">
      <select wire:model.live="status">
        <option value="active">Active (open)</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
        <option value="all">All clinics</option>
      </select>
    </div>
    <a href="{{ route('dashboard.zoom-clinics.create') }}" class="bdgs-content-toolbar__new-btn">+ New Zoom Clinic</a>
  </div>

  <div class="bdgs-content-list" wire:loading.class="opacity-60">
    @forelse ($clinics as $clinic)
      @php
        $lifecycle = $clinic->displayLifecycleStatus();
        $statusClass = match ($lifecycle) {
          'upcoming', 'live' => 'published',
          'completed' => 'archived',
          default => 'draft',
        };
      @endphp
      <div class="bdgs-content-item" wire:key="clinic-{{ $clinic->clinic_id }}">
        <div class="bdgs-content-item__thumb">
          <span class="bdgs-content-item__status bdgs-content-item__status--{{ $statusClass }}">{{ $clinic->displayLifecycleLabel() }}</span>
          <span class="bdgs-content-item__no-img" style="font-size: 1.5rem;">
            <svg viewBox="0 0 24 24" fill="none" width="32" height="32"><rect x="2" y="4" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 22h8M12 18v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </span>
        </div>
        <div class="bdgs-content-item__body">
          <h3 class="bdgs-content-item__title">
            <a href="{{ route('dashboard.zoom-clinics.edit', $clinic->clinic_id) }}">{{ $clinic->title }}</a>
          </h3>
          @if ($clinic->agenda)
            <p class="bdgs-content-item__excerpt">{{ Str::limit($clinic->agenda, 120) }}</p>
          @endif
          <p class="bdgs-content-item__excerpt" style="margin-top:4px;">
            <strong>Access:</strong> {{ $clinic->access_type === 'public' ? 'Public' : 'Registered Only' }}
            &nbsp;&bull;&nbsp;
            <strong>Registrations:</strong>
            <a href="{{ route('dashboard.zoom-clinics.registrations.index', ['clinic_id' => $clinic->clinic_id]) }}">{{ $clinic->confirmed_registrations_count }}</a>
          </p>
        </div>
        <div class="bdgs-content-item__meta">
          <div class="bdgs-content-item__actions-wrap">
            <button type="button" class="bdgs-content-item__actions-btn" aria-haspopup="true">Actions <svg viewBox="0 0 12 12" fill="none" width="12" height="12"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></button>
            <div class="bdgs-content-item__actions-menu">
              <a href="{{ route('dashboard.zoom-clinics.edit', $clinic->clinic_id) }}">Edit</a>
              <form method="POST" action="{{ route('dashboard.zoom-clinics.destroy', $clinic->clinic_id) }}" onsubmit="return confirm('Delete this zoom clinic and all registrations?')">@csrf @method('DELETE')<button type="submit" class="bdgs-content-item__actions-danger">Delete</button></form>
            </div>
          </div>
          <p class="bdgs-content-item__date">Session: {{ $clinic->session_starts_at->format('M j, Y g:i A') }}</p>
          <p class="bdgs-content-item__date">Created: {{ $clinic->created_at->format('m/d/Y') }}</p>
        </div>
      </div>
    @empty
      <p class="bdgs-content-item__excerpt" style="padding:24px 8px;">
        No clinics match this filter.
        <button type="button" wire:click="$set('status', 'completed')" style="background:none;border:none;color:inherit;text-decoration:underline;cursor:pointer;padding:0;">Completed</button>
        or
        <button type="button" wire:click="$set('status', 'all')" style="background:none;border:none;color:inherit;text-decoration:underline;cursor:pointer;padding:0;">All clinics</button>.
      </p>
    @endforelse
  </div>

  @if ($clinics->hasPages())
    <div class="bdgs-content-pagination">
      {{ $clinics->links() }}
    </div>
  @endif
</div>
