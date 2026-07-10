<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@yield('title')
@yield('meta')
@include('partials.bdgs.head-assets')
<link rel="stylesheet" href="/css/bdgs-shell.css">
@stack('page-styles')
@if ($showFab ?? true)
<link rel="preload" as="style" href="/css/cpb-fab.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/css/cpb-fab.css"></noscript>
@endif
</head>
<body>

@if (View::hasSection('ai-summary'))
@yield('ai-summary')
@else
@include('partials.bdgs.ai-summary')
@endif
@include('partials.bdgs.header')

@yield('content')

@include('partials.bdgs.footer')
@stack('page-modals')
@include('partials.bdgs.inquiry-modal')
@include('partials.bdgs.inquiry-scripts')
@include('partials.bdgs.shell-scripts')
@include('partials.bdgs.auth-user-data')
@include('partials.bdgs.mobile-nav')
@include('partials.bdgs.mobile-nav-scripts')
@stack('page-schema')
@if ($showFab ?? true)
@include('partials.bdgs.copy-page-fab')
@include('partials.bdgs.copy-page-fab-scripts')
@endif
@stack('page-scripts')
</body>
</html>
