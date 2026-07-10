@extends('layouts.bdgs')

@section('ai-summary')
@include('pages.webinars.ai-summary')
@endsection

@section('title')
<title>Brilliant Directories CEO Webinars — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="Watch 3 official Brilliant Directories webinars with Jason, CEO of Brilliant Directories, and Yakin Shah. Your First 100 Members series, member dashboard checklist, and join-page strategies.">
<meta name="keywords" content="Brilliant Directories webinars, BD Growth Suite webinar, Yakin Shah Brilliant Directories, Brilliant Directories CEO endorsement, first 100 members webinar">
<link rel="canonical" href="https://bdgrowthsuite.com/webinars/">
<meta property="og:type" content="website">
<meta property="og:title" content="Brilliant Directories CEO Webinars — BD Growth Suite">
<meta property="og:description" content="3 official webinars featuring Jason, CEO of Brilliant Directories, and Yakin Shah of BD Growth Suite — including the Your First 100 Members series.">
<meta property="og:url" content="https://bdgrowthsuite.com/webinars/">
<meta property="og:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Brilliant Directories CEO Webinars — BD Growth Suite">
<meta name="twitter:description" content="3 official webinars featuring Jason, CEO of Brilliant Directories, and Yakin Shah of BD Growth Suite — including the Your First 100 Members series.">
<meta name="twitter:image" content="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-webinars.css">
@endpush

@section('content')
@include('pages.webinars.content')
@endsection

@push('page-schema')
@include('partials.bdgs.schema-webinars')
@endpush

@push('page-scripts')
@include('pages.webinars.page-scripts')
@endpush
