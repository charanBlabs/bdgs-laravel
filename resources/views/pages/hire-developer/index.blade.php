@extends('layouts.bdgs')

@php($activeNav = 'services')

@section('ai-summary')
@include('pages.hire-developer.ai-summary')
@endsection

@section('title')
<title>Hire Dedicated Brilliant Directories Developers — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Hire a dedicated Brilliant Directories developer on demand. From custom builds to full-time support — expert developers ready when you are.">
<meta name="keywords" content="hire dedicated developers, dedicated brilliant directories developer, hire brilliant directories developer, brilliant directories developers, hire directory website developer">
<link rel="canonical" href="https://bdgrowthsuite.com/hire-developer/">
<meta property="og:type" content="website">
<meta property="og:title" content="Hire Dedicated Brilliant Directories Developers — BD Growth Suite">
<meta property="og:description" content="Hire a dedicated Brilliant Directories developer on demand. From custom builds to full-time support — expert developers ready when you are.">
<meta property="og:url" content="https://bdgrowthsuite.com/hire-developer/">
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Hire Dedicated Brilliant Directories Developers — BD Growth Suite">
<meta name="twitter:description" content="Hire a dedicated Brilliant Directories developer on demand. From custom builds to full-time support — expert developers ready when you are.">
<meta name="twitter:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-hire-developer.css">
@endpush

@section('content')
@include('pages.hire-developer.content')
@endsection

@push('page-modals')
@php($zoomModalSkipDetails = true)
@php($zoomModalClinicsLink = '/zoom-clinics#upcoming-clinics')
@include('pages.home.zoom-modal')
@endpush

@push('page-schema')
@include('partials.bdgs.schema-hire-developer')
@endpush

@push('page-scripts')
@include('pages.zoom-clinics.clinic-data')
@include('partials.bdgs.zoom-clinic-scripts')
@include('pages.hire-developer.page-scripts')
@endpush
