@extends('layouts.admin')

@section('page-title', 'Zoom Clinic Registrations')

@section('content')
<livewire:admin.zoom-clinic-registration-index
  surface="admin"
  :clinic-id="request()->integer('clinic_id') ?: null"
/>
@endsection
