@extends('layouts.bdgs')

@php($showFab = false)

@section('title')
<title>Login to Your Brilliant Directories Dashboard — BD Growth Suite</title>
@endsection

@section('robots-content', 'noindex, nofollow')

@section('meta')
<meta name="description" content="Customer login for BD Growth Suite clients. Access your Brilliant Directories dashboard, orders, and account settings.">
<link rel="canonical" href="https://bdgrowthsuite.com/login/">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-auth.css">
@endpush

@section('content')
@include('pages.login.content')
@endsection

@push('page-scripts')
<script src="/js/bdgs-auth.js" defer></script>
@endpush
