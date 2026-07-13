@php
  use App\Support\ZoomClinicQuotes;
  use Illuminate\Support\Str;

  /** @var \Illuminate\Support\Collection<int, \App\Models\BdgsZoomClinicRegistration> $registrations */
  $regCount = $registrations->count();
  $showParticipants = ZoomClinicQuotes::shouldShowParticipants($regCount);
  $variant = $variant ?? 'card';
  $quotes = $variant === 'hero'
    ? ZoomClinicQuotes::heroQuotesFor()
    : ZoomClinicQuotes::quotesFor($clinic);
  $intervalMs = (int) config('zoom-clinic.hero_quote_interval_ms', 5500);
@endphp

@if ($showParticipants)
  <div class="zc-avatar-stack" aria-label="{{ $regCount }} registered participants">
    @foreach ($registrations->take($variant === 'hero' ? 5 : 6) as $reg)
    <span class="zc-avatar" title="{{ $reg->name }}">{{ $reg->initials() }}</span>
    @endforeach
    @php $avatarLimit = $variant === 'hero' ? 5 : 6; @endphp
    @if ($regCount > $avatarLimit)
    <span class="zc-avatar zc-avatar-more">+{{ $regCount - $avatarLimit }}</span>
    @endif
  </div>
  <span class="zc-participant-count">{{ $regCount }} {{ Str::plural('participant', $regCount) }} registered</span>
@elseif ($variant === 'hero')
  <div class="zc-quote-rotator"
    data-quotes='@json($quotes)'
    data-interval="{{ $intervalMs }}"
    aria-live="polite">
    <p class="zc-clinic-hook zc-clinic-hook--hero">&ldquo;{{ $quotes[0] }}&rdquo;</p>
  </div>
@else
  <span class="zc-clinic-hook">&ldquo;{{ ZoomClinicQuotes::primaryQuote($clinic) }}&rdquo;</span>
@endif
