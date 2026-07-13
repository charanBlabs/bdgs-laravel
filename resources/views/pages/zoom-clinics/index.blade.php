@extends('layouts.bdgs')

@section('ai-summary')
@include('pages.zoom-clinics.ai-summary')
@endsection

@section('title')
<title>Free Brilliant Directories Zoom Clinics — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Free Brilliant Directories Zoom Clinics every Tue &amp; Thu. BD Growth Suite developers answer live — widgets, CSS, search, email. Drop in, $0.">
<meta name="keywords" content="brilliant directories zoom clinics, bd growth suite zoom clinics, free zoom clinic, brilliant directories developers, live q&amp;a brilliant directories, brilliant directories website help">
<link rel="canonical" href="https://bdgrowthsuite.com/zoom-clinics/">
<meta property="og:type" content="website">
<meta property="og:title" content="Free Brilliant Directories Zoom Clinics — BD Growth Suite">
<meta property="og:description" content="Free Brilliant Directories Zoom Clinics every Tue &amp; Thu. Live Q&amp;A with BD Growth Suite developers — widgets, CSS, search, email. $0.">
<meta property="og:url" content="https://bdgrowthsuite.com/zoom-clinics/">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Free Brilliant Directories Zoom Clinics — BD Growth Suite">
<meta name="twitter:description" content="Free Brilliant Directories Zoom Clinics every Tue &amp; Thu. Live Q&amp;A with BD Growth Suite developers — widgets, CSS, search, email. $0.">
<meta name="twitter:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-zoom-clinics.css">
@endpush

@section('content')
@include('pages.zoom-clinics.content')
@endsection

@push('page-modals')
@php($zoomModalSkipDetails = true)
@php($zoomModalClinicsLink = '#upcoming-clinics')
@include('pages.home.zoom-modal')
@endpush

@push('page-schema')
@include('pages.zoom-clinics.schema')
@endpush

@push('page-scripts')
@include('pages.zoom-clinics.clinic-data')
@include('partials.bdgs.zoom-clinic-scripts')
@include('pages.zoom-clinics.page-scripts')
@endpush
