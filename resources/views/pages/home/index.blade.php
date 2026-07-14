@extends('layouts.bdgs')

@section('ai-summary')
@include('pages.home.ai-summary')
@endsection

@section('title')
<title>Brilliant Directories Developers — BD Growth Suite</title>
@endsection

@section('meta')
<meta name="description" content="As brilliant directories developers, BusinessLabs assigns a dedicated BD developer for BD automation with directory website expert rigor—BD Growth Suite.">
<!-- Google largely ignores meta keywords; client requested for legacy/other engines. -->
<meta name="keywords" content="brilliant directories developers, business directories developers, dedicated bd developer, bd automation, brilliant directories, directory website expert, BD Growth Suite, BusinessLabs, BusinessLabs HQ, directory software, Brilliant Directories customization">
<link rel="canonical" href="https://bdgrowthsuite.com/">
<meta property="og:type" content="website">
<meta property="og:title" content="Brilliant Directories Developers — BD Growth Suite">
<meta property="og:description" content="Brilliant Directories developers who think like co-founders. Dedicated BD developer team for setup, customization, AI automation, and growth.">
<meta property="og:url" content="https://bdgrowthsuite.com/">
<meta property="og:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Brilliant Directories Developers — BD Growth Suite">
<meta name="twitter:description" content="Brilliant Directories developers who think like co-founders. Dedicated BD developer team for setup, customization, AI automation, and growth.">
<meta name="twitter:image" content="https://bdgrowthsuite.com/images/brand/logo.png">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-home.css">
<link rel="stylesheet" href="/css/bdgs-webinars.css">
@endpush

@section('content')
@include('pages.home.content')
@endsection

@push('page-modals')
@include('pages.home.zoom-modal')
@endpush

@push('page-schema')
@include('partials.bdgs.schema-home')
@endpush

@push('page-scripts')
@include('pages.zoom-clinics.clinic-data')
@include('pages.home.carousel-data')
@include('pages.home.page-scripts')
<script src="/snippets/bdgs-webinar-videos.js?v=20260708a"></script>
<script src="/snippets/bdgs-youtube-player.js?v=20260708a"></script>
@endpush
