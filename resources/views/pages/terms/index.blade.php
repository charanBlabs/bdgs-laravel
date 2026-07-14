@extends('layouts.bdgs')

@section('title')
<title>Terms of Use for BD Growth Suite Services</title>
@endsection

@section('meta')
<meta name="description" content="Terms of use for BD Growth Suite services — account rules, acceptable use, IP, liability limits, and governing law for Brilliant Directories clients.">
<meta name="keywords" content="business directory, BD Growth Suite terms of use">
<link rel="canonical" href="https://bdgrowthsuite.com/about/terms">
<meta property="og:type" content="website">
<meta property="og:url" content="https://bdgrowthsuite.com/about/terms">
<meta property="og:site_name" content="BD Growth Suite">
<meta property="og:title" content="Terms of Use for BD Growth Suite Services">
<meta property="og:description" content="Terms of use for BD Growth Suite services — account rules, acceptable use, IP, liability limits, and governing law for Brilliant Directories clients.">
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/business-growth.jpg">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-legal.css">
@endpush

@section('content')
@include('pages.terms.content')
@endsection

@push('page-schema')
@include('partials.bdgs.schema-privacy')
@endpush
