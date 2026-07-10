@extends('layouts.bdgs')

@php($activeNav = 'services')

@section('title')
<title>Brilliant Directories Services — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Setup, dedicated developers, AI, custom projects, solutions, themes, maintenance, and Founder's Track. Every service we offer for Brilliant Directories site owners.">
<meta name="keywords" content="Brilliant Directories services, brilliant directories developers, bd growth suite, directory website expert, bd automation, dedicated bd developer">
<link rel="canonical" href="https://bdgrowthsuite.com/services/">
<meta property="og:type" content="website">
<meta property="og:title" content="Brilliant Directories Services — BD Growth Suite">
<meta property="og:description" content="Setup, dedicated developers, AI, custom projects, solutions, themes, maintenance, and Founder's Track. Every service we offer for Brilliant Directories site owners.">
<meta property="og:url" content="https://bdgrowthsuite.com/services/">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Brilliant Directories Services — BD Growth Suite">
<meta name="twitter:description" content="Setup, dedicated developers, AI, custom projects, solutions, themes, maintenance, and Founder's Track. Every service we offer for Brilliant Directories site owners.">
<meta name="twitter:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-services.css">
@endpush

@section('content')
@include('pages.services.content')
@endsection

@push('page-schema')
@include('partials.bdgs.schema-services')
@endpush
