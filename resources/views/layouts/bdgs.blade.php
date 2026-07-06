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
@include('partials.bdgs.inquiry-modal')
@include('partials.bdgs.shell-scripts')
@include('partials.bdgs.mobile-nav')
@include('partials.bdgs.mobile-nav-scripts')
@stack('page-schema')
@if ($showFab ?? true)
<link rel="stylesheet" href="/css/cpb-fab.css">
@include('partials.bdgs.copy-page-fab')
@include('partials.bdgs.copy-page-fab-scripts')
@endif
<script src="/snippets/bdgs-review-count.js"></script>
@stack('page-scripts')
</body>
</html>
