@extends('layouts.bdgs')

@section('title')
<title>License Terms for Single Domain Use | BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Read BD Growth Suite license terms for single domain use, including usage rights, restrictions, transfers, and domain change policies.">
<meta name="keywords" content="BD Growth Suite license, single domain license, SDCL">
<link rel="canonical" href="https://bdgrowthsuite.com/license/sdcl-v1">
<meta property="og:type" content="website">
<meta property="og:url" content="https://bdgrowthsuite.com/license/sdcl-v1">
<meta property="og:site_name" content="BD Growth Suite">
<meta property="og:title" content="License Terms for Single Domain Use | BD Growth Suite">
<meta property="og:description" content="Read BD Growth Suite license terms for single domain use, including usage rights, restrictions, transfers, and domain change policies.">
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/business-growth.jpg">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-license.css">
@endpush

@section('content')
@include('pages.license.sdcl-v1.content')
@endsection

@push('page-schema')
@include('partials.bdgs.schema-privacy')
@endpush
