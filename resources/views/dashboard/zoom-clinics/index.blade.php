@extends('layouts.account')

@section('account-heading')
  <span class="bdgs-content-heading">
    <svg viewBox="0 0 20 20" fill="none" width="20" height="20"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    Zoom Clinics
    <span class="bdgs-content-heading__badge">{{ $clinicCount }}</span>
  </span>
@endsection

@section('account-content')
@include('dashboard.zoom-clinics.partials.tabs', ['activeTab' => 'clinics'])

@if ($clinicCount === 0)
  <div class="bdgs-content-empty">
    <div class="bdgs-content-empty__icon">
      <svg viewBox="0 0 64 64" fill="none"><path d="M12 16a4 4 0 014-4h32a4 4 0 014 4v32a4 4 0 01-4 4H16a4 4 0 01-4-4V16z" stroke="url(#zc-g)" stroke-width="2.5"/><path d="M24 28h16M32 24v8" stroke="url(#zc-g)" stroke-width="2.5" stroke-linecap="round"/><defs><linearGradient id="zc-g" x1="12" y1="12" x2="52" y2="52"><stop stop-color="var(--bdgs-coral)"/><stop offset="1" stop-color="var(--bdgs-purple)"/></linearGradient></defs></svg>
    </div>
    <h2 class="bdgs-content-empty__title">Schedule Your First Zoom Clinic</h2>
    <a href="{{ route('dashboard.zoom-clinics.create') }}" class="bdgs-content-empty__btn">+ New Zoom Clinic</a>
  </div>
@else
  <livewire:admin.zoom-clinic-index surface="dashboard" />
@endif
@endsection

@push('account-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
