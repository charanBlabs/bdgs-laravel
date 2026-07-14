@extends('layouts.admin')

@section('page-title', 'Zoom Clinics')

@section('content')
<p><a class="bdgs-btn" href="{{ route('admin.zoom-clinics.create') }}">New Zoom Clinic</a></p>

<livewire:admin.zoom-clinic-index surface="admin" />
@endsection
