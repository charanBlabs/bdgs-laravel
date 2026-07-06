@extends('layouts.bdgs')

@php($activeNav = 'services')

@section('ai-summary')
@include('pages.customization.ai-summary')
@endsection

@section('title')
<title>Brilliant Directories Customization — Custom Projects — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Brilliant Directories customization: fixed-scope custom projects, quote in 24 hours, 1-year warranty. 1000+ builds across 500+ directories.">
<meta name="keywords" content="brilliant directories customization, brilliant directories custom development, custom projects brilliant directories, brilliant directories developers custom builds">
<link rel="canonical" href="https://bdgrowthsuite.com/customization/">
<meta property="og:type" content="website">
<meta property="og:title" content="Brilliant Directories Customization — Custom Projects — BD Growth Suite">
<meta property="og:description" content="Brilliant Directories customization: fixed-scope custom projects, quote in 24 hours, 1-year warranty. 1000+ builds across 500+ directories.">
<meta property="og:url" content="https://bdgrowthsuite.com/customization/">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Brilliant Directories Customization — Custom Projects — BD Growth Suite">
<meta name="twitter:description" content="Brilliant Directories customization: fixed-scope custom projects, quote in 24 hours, 1-year warranty. 1000+ builds across 500+ directories.">
<meta name="twitter:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
<link rel="icon" type="image/png" href="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/favicon.png">
<link rel="shortcut icon" href="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/favicon.png">
<link rel="apple-touch-icon" href="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-customization.css">
@endpush

@section('content')
@include('pages.customization.content')
@endsection

@push('page-schema')
@include('partials.bdgs.schema-customization')
@endpush
