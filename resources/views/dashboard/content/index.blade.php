@extends('layouts.account')

@section('account-heading')
  <span class="bdgs-content-heading">
    @include('partials.bdgs.post-type-icon', ['slug' => $type])
    {{ $dataType->pluralLabel() }}
    <span class="bdgs-content-heading__badge">{{ $totalCount }}</span>
  </span>
@endsection

@section('account-content')
@if ($totalCount === 0)
  {{-- Empty state --}}
  <div class="bdgs-content-empty">
    <div class="bdgs-content-empty__icon">
      <svg viewBox="0 0 64 64" fill="none"><path d="M12 16a4 4 0 014-4h32a4 4 0 014 4v32a4 4 0 01-4 4H16a4 4 0 01-4-4V16z" stroke="url(#ce-g)" stroke-width="2.5"/><path d="M24 24h16M24 32h16M24 40h8" stroke="url(#ce-g)" stroke-width="2.5" stroke-linecap="round"/><defs><linearGradient id="ce-g" x1="12" y1="12" x2="52" y2="52"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    </div>
    <h2 class="bdgs-content-empty__title">Publish Your First {{ $dataType->name }}</h2>
    <a href="{{ route('dashboard.content.create', $type) }}" class="bdgs-content-empty__btn">
      + New {{ $dataType->name }}
    </a>
  </div>
@else
  <livewire:admin.content-index :type="$type" />
@endif
@endsection
