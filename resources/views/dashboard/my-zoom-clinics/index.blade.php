@extends('layouts.account')

@section('account-heading', 'My Zoom Clinics')
@section('account-lead', 'Clinics you registered for — add them to your calendar and track when they complete.')

@section('account-content')
<div class="bdgs-account-card bdgs-my-clinics" data-ajax-list="my-zoom-clinics">
  @forelse ($registrations as $registration)
    @php
      $clinic = $registration->clinic;
      $displayStatus = $clinic?->displayLifecycleStatus() ?? 'upcoming';
      $schedule = $clinic ? \App\Support\ZoomClinicTimes::clinicRange($clinic) : 'Schedule TBA';
      $calendarUrl = $clinic
        ? \App\Support\ZoomClinicCalendar::googleCalendarUrl($clinic, $registration)
        : null;
      $isCompleted = $displayStatus === 'completed';
      $isCancelled = $displayStatus === 'cancelled';
      $canAddCalendar = $calendarUrl && ! $isCompleted && ! $isCancelled;
    @endphp
    <article class="bdgs-my-clinic" data-registration-id="{{ $registration->registration_id }}">
      <div class="bdgs-my-clinic__body">
        <div class="bdgs-my-clinic__top">
          <h2 class="bdgs-my-clinic__title">{{ $clinic?->title ?? 'Zoom Clinic' }}</h2>
          <span class="bdgs-my-clinic__status bdgs-my-clinic__status--{{ $displayStatus }}">
            @if ($displayStatus === 'live') Live now
            @elseif ($displayStatus === 'completed') Completed
            @elseif ($displayStatus === 'cancelled') Cancelled
            @else Upcoming
            @endif
          </span>
        </div>
        <p class="bdgs-my-clinic__meta">{{ $schedule }}</p>
        <p class="bdgs-my-clinic__meta bdgs-my-clinic__meta--muted">
          Registered {{ $registration->registered_at?->format('M j, Y') ?? '—' }}
          @if ($registration->registrant_timezone)
            · {{ $registration->registrant_timezone }}
          @endif
        </p>
      </div>
      <div class="bdgs-my-clinic__actions">
        @if ($canAddCalendar)
          @if ($registration->calendar_added_at)
            <span class="bdgs-my-clinic__added" data-calendar-state="added">
              <svg viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><circle cx="10" cy="10" r="10" fill="currentColor"/><path d="M5.8 10.2l2.7 2.7 5.7-6" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              Added to calendar
            </span>
            <a
              href="{{ $calendarUrl }}"
              target="_blank"
              rel="noopener noreferrer"
              class="bdgs-account-card__detail-link"
              data-calendar-link
              data-registration-id="{{ $registration->registration_id }}"
            >Open again</a>
          @else
            <a
              href="{{ $calendarUrl }}"
              target="_blank"
              rel="noopener noreferrer"
              class="bdgs-account-card__action bdgs-my-clinic__cal-btn"
              data-calendar-link
              data-registration-id="{{ $registration->registration_id }}"
            >Add to Google Calendar</a>
          @endif
        @elseif ($isCompleted)
          <span class="bdgs-my-clinic__added">Session completed</span>
        @elseif ($isCancelled)
          <span class="bdgs-my-clinic__added" style="color:var(--bdgs-coral-dark, #c0392b);">Clinic cancelled</span>
        @endif
        @if ($clinic?->zoom_meeting_url && in_array($displayStatus, ['upcoming', 'live'], true))
          <a href="{{ $clinic->zoom_meeting_url }}" target="_blank" rel="noopener noreferrer" class="bdgs-account-card__detail-link">Join Zoom</a>
        @endif
      </div>
    </article>
  @empty
    <p style="margin:0;">You haven’t registered for any Zoom Clinics yet.</p>
    <div class="bdgs-account-card__links" style="margin-top:16px;">
      <a href="{{ url('/zoom-clinics') }}" class="bdgs-account-card__action">Browse Zoom Clinics</a>
    </div>
  @endforelse
</div>

@if ($registrations->hasPages())
  <div style="margin-top:16px;" data-ajax-list-pagination>{{ $registrations->links() }}</div>
@endif
@endsection

@push('account-scripts')
<script type="application/json" id="bdgs-my-zoom-boot">{!! json_encode([
  'markUrlBase' => url('/dashboard/my-zoom-clinics'),
  'loginUrl' => url('/login'),
], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<script>
(function() {
  var boot = JSON.parse(document.getElementById("bdgs-my-zoom-boot").textContent);
  var markUrlBase = boot.markUrlBase;
  var loginUrl = boot.loginUrl;
  var csrf = document.querySelector('meta[name="csrf-token"]');
  var token = csrf ? csrf.getAttribute('content') : '';

  function markCalendarAdded(link) {
    var id = link.getAttribute('data-registration-id');
    if (!id) return;

    fetch(markUrlBase + '/' + id + '/calendar-added', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    }).then(function(res) {
      if (res.status === 401 || res.status === 419) {
        if (window.confirm('Your session expired. Sign in again to keep calendar status in sync?')) {
          window.location.href = markUrlBase;
        }
        return null;
      }
      if (res.status === 429) {
        window.alert('Too many requests. Please wait a moment and try again.');
        return null;
      }
      return res.ok ? res.json() : null;
    }).then(function(data) {
      if (!data || !data.ok) return;
      var row = link.closest('.bdgs-my-clinic');
      if (!row) return;
      var actions = row.querySelector('.bdgs-my-clinic__actions');
      if (!actions) return;
      if (actions.querySelector('[data-calendar-state="added"]')) return;

      var btn = actions.querySelector('.bdgs-my-clinic__cal-btn');
      if (btn) {
        var label = document.createElement('span');
        label.className = 'bdgs-my-clinic__added';
        label.setAttribute('data-calendar-state', 'added');
        label.innerHTML = '<svg viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><circle cx="10" cy="10" r="10" fill="currentColor"/><path d="M5.8 10.2l2.7 2.7 5.7-6" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Added to calendar';
        var again = document.createElement('a');
        again.href = btn.href;
        again.target = '_blank';
        again.rel = 'noopener noreferrer';
        again.className = 'bdgs-account-card__detail-link';
        again.setAttribute('data-calendar-link', '');
        again.setAttribute('data-registration-id', id);
        again.textContent = 'Open again';
        btn.replaceWith(label);
        label.insertAdjacentElement('afterend', again);
      }
    }).catch(function() {});
  }

  document.querySelectorAll('[data-calendar-link]').forEach(function(link) {
    link.addEventListener('click', function() {
      markCalendarAdded(link);
    });
  });
})();
</script>
@endpush
