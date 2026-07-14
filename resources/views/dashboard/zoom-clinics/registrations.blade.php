@extends('layouts.account')

@section('account-heading')
  <span class="bdgs-content-heading">
    <svg viewBox="0 0 20 20" fill="none" width="20" height="20"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    Zoom Clinics
    <span class="bdgs-content-heading__badge">{{ $registrationCount }}</span>
  </span>
@endsection

@section('account-content')
@include('dashboard.zoom-clinics.partials.tabs', ['activeTab' => 'registrations'])

<livewire:admin.zoom-clinic-registration-index
  surface="dashboard"
  :clinic-id="request()->integer('clinic_id') ?: null"
/>
@endsection

@push('account-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
