@php
  $reviewsMetaDescription = "Read {$reviewCount} verified reviews from Brilliant Directories site owners. Gold Certified partner with 5-star ratings for setup, customization, and growth.";
  $reviewsPageTitle = "{$reviewCount} Verified Client Reviews — BD Growth Suite";
@endphp
@extends('layouts.bdgs')

@section('ai-summary')
@include('pages.blabs-review.ai-summary')
@endsection

@section('title')
<title>{{ $reviewsPageTitle }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ $reviewsMetaDescription }}">
<meta name="keywords" content="bd growth suite reviews, brilliant directories reviews, brilliant directories developers, directory website expert, verified client reviews">
<link rel="canonical" href="https://bdgrowthsuite.com/blabs-review/">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $reviewsPageTitle }}">
<meta property="og:description" content="{{ $reviewsMetaDescription }}">
<meta property="og:url" content="https://bdgrowthsuite.com/blabs-review/">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $reviewsPageTitle }}">
<meta name="twitter:description" content="{{ $reviewsMetaDescription }}">
<meta name="twitter:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-blabs-review.css">
@endpush

@section('content')
@include('pages.blabs-review.content')
@endsection

@push('page-schema')
@include('partials.bdgs.schema-blabs-review', ['schemaGraph' => $schemaGraph])
@endpush

@push('page-scripts')
@include('pages.blabs-review.page-scripts')
@endpush
