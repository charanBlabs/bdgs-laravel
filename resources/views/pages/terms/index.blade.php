@extends('layouts.bdgs')

@section('title')
<title>Terms of Use - BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Read BD Growth Suite terms of use.">
<meta name="keywords" content="business directory,">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://bdgrowthsuite.com/about/terms">
<meta property="og:type" content="website">
<meta property="og:url" content="https://bdgrowthsuite.com/about/terms">
<meta property="og:site_name" content="BD Growth Suite">
<meta property="og:title" content="Terms of Use - BD Growth Suite">
<meta property="og:description" content="Read BD Growth Suite terms of use.">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/business-growth.jpg">
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
