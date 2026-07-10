@extends('layouts.account')

@section('account-heading', $featureLabel ?? 'Coming Soon')

@section('account-content')
<div class="bdgs-coming-soon">
  <div class="bdgs-coming-soon__icon">
    @if (($featureIcon ?? '') === 'services')
      <svg viewBox="0 0 64 64" fill="none"><path d="M12 16a4 4 0 014-4h32a4 4 0 014 4v32a4 4 0 01-4 4H16a4 4 0 01-4-4V16z" stroke="url(#cs-g)" stroke-width="2.5"/><path d="M24 24h16M24 32h16M24 40h8" stroke="url(#cs-g)" stroke-width="2.5" stroke-linecap="round"/><defs><linearGradient id="cs-g" x1="12" y1="12" x2="52" y2="52"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    @elseif (($featureIcon ?? '') === 'orders')
      <svg viewBox="0 0 64 64" fill="none"><path d="M10 20a4 4 0 014-4h28a4 4 0 014 4v24a4 4 0 01-4 4H14a4 4 0 01-4-4V20z" stroke="url(#cs-g)" stroke-width="2.5"/><path d="M10 28h36" stroke="url(#cs-g)" stroke-width="2.5"/><circle cx="22" cy="40" r="2.5" stroke="url(#cs-g)" stroke-width="2.5"/><circle cx="32" cy="40" r="2.5" stroke="url(#cs-g)" stroke-width="2.5"/><defs><linearGradient id="cs-g" x1="10" y1="16" x2="50" y2="48"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    @elseif (($featureIcon ?? '') === 'tickets')
      <svg viewBox="0 0 64 64" fill="none"><path d="M10 18A3 3 0 0113 15h38a3 3 0 013 3v22a3 3 0 01-3 3H20l-10 8V18z" stroke="url(#cs-g)" stroke-width="2.5"/><path d="M22 26h20M22 34h10" stroke="url(#cs-g)" stroke-width="2.5" stroke-linecap="round"/><defs><linearGradient id="cs-g" x1="10" y1="15" x2="54" y2="48"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    @else
      <svg viewBox="0 0 64 64" fill="none"><circle cx="32" cy="32" r="22" stroke="url(#cs-g)" stroke-width="2.5"/><path d="M32 22v14l8 6" stroke="url(#cs-g)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><defs><linearGradient id="cs-g" x1="10" y1="10" x2="54" y2="54"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    @endif
  </div>

  <h2 class="bdgs-coming-soon__title">Coming Soon</h2>
  <p class="bdgs-coming-soon__text">
    We're building <strong>{{ $featureLabel ?? 'this feature' }}</strong> to give you more control
    from your dashboard. Stay tuned — it'll be here before you know it.
  </p>

  <div class="bdgs-coming-soon__badge">
    <span class="bdgs-coming-soon__dot"></span>
    Under Development
  </div>
</div>
@endsection
