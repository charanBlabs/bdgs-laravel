<div data-bdgs-scroll-on-page>
  <div class="bdgs-panel-form">
    <label>Action
      <input type="text" wire:model.live.debounce.300ms="action" placeholder="created, updated, login">
    </label>
  </div>
  <table class="bdgs-panel-table" style="margin-top:16px;" wire:loading.class="opacity-60">
    <thead><tr><th>When</th><th>User</th><th>Action</th><th>Subject</th></tr></thead>
    <tbody>
      @forelse ($logs as $log)
        <tr wire:key="log-{{ $log->id }}">
          <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
          <td>{{ $log->user?->fullName() ?? '—' }}</td>
          <td>{{ $log->action }}</td>
          <td>{{ $log->subject_type ? class_basename($log->subject_type).' #'.$log->subject_id : '—' }}</td>
        </tr>
      @empty
        <tr><td colspan="4">No activity matches this filter.</td></tr>
      @endforelse
    </tbody>
  </table>
  {{ $logs->links() }}
</div>
