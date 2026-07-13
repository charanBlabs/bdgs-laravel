@extends('layouts.bdgs')

@section('title')
<title>Privacy Policy - BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Read BD Growth Suite privacy policy.">
<link rel="canonical" href="https://bdgrowthsuite.com/about/privacy">
<meta property="og:type" content="website">
<meta property="og:url" content="https://bdgrowthsuite.com/about/privacy">
<meta property="og:site_name" content="BD Growth Suite">
<meta property="og:title" content="Privacy Policy - BD Growth Suite">
<meta property="og:description" content="Read BD Growth Suite privacy policy.">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/business-growth.jpg">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-legal.css">
@endpush

@section('content')
@include('pages.privacy.content')
@endsection

@push('page-schema')
@include('partials.bdgs.schema-privacy')
@endpush
