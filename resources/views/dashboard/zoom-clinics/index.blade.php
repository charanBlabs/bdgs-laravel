@extends('layouts.account')

@section('account-heading')
  <span class="bdgs-content-heading">
    <svg viewBox="0 0 20 20" fill="none" width="20" height="20"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    Zoom Clinics
    <span class="bdgs-content-heading__badge">{{ $clinics->total() }}</span>
  </span>
@endsection

@section('account-content')
@if ($clinics->isEmpty())
  <div class="bdgs-content-empty">
    <div class="bdgs-content-empty__icon">
      <svg viewBox="0 0 64 64" fill="none"><path d="M12 16a4 4 0 014-4h32a4 4 0 014 4v32a4 4 0 01-4 4H16a4 4 0 01-4-4V16z" stroke="url(#zc-g)" stroke-width="2.5"/><path d="M24 28h16M32 24v8" stroke="url(#zc-g)" stroke-width="2.5" stroke-linecap="round"/><defs><linearGradient id="zc-g" x1="12" y1="12" x2="52" y2="52"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    </div>
    <h2 class="bdgs-content-empty__title">Schedule Your First Zoom Clinic</h2>
    <a href="{{ route('dashboard.zoom-clinics.create') }}" class="bdgs-content-empty__btn">+ New Zoom Clinic</a>
  </div>
@else
  <div class="bdgs-content-toolbar">
    <div></div>
    <a href="{{ route('dashboard.zoom-clinics.create') }}" class="bdgs-content-toolbar__new-btn">+ New Zoom Clinic</a>
  </div>

  <div class="bdgs-content-list">
    @foreach ($clinics as $clinic)
      <div class="bdgs-content-item">
        <div class="bdgs-content-item__thumb">
          @php($statusClass = match($clinic->status) { 'scheduled' => 'published', 'live' => 'published', 'completed' => 'archived', 'cancelled' => 'draft', default => 'draft' })
          <span class="bdgs-content-item__status bdgs-content-item__status--{{ $statusClass }}">{{ ucfirst($clinic->status) }}</span>
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
            <strong>Registrations:</strong> {{ $clinic->confirmed_registrations_count }}
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
    @endforeach
  </div>

  @if ($clinics->hasPages())
    <div class="bdgs-content-pagination">
      {{ $clinics->links() }}
    </div>
  @endif
@endif
@endsection

@push('account-scripts')
<script>
(function () {
  document.querySelectorAll('.bdgs-content-item__actions-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var menu = btn.nextElementSibling;
      var isOpen = menu.classList.contains('is-open');
      document.querySelectorAll('.bdgs-content-item__actions-menu.is-open').forEach(function (m) { m.classList.remove('is-open'); });
      if (!isOpen) menu.classList.add('is-open');
    });
  });
  document.addEventListener('click', function () {
    document.querySelectorAll('.bdgs-content-item__actions-menu.is-open').forEach(function (m) { m.classList.remove('is-open'); });
  });
})();
</script>
@endpush
