@extends('layouts.bdgs')

@php($showFab = false)

@section('title')
<title>Create Your BD Growth Suite Account</title>
@endsection

@section('robots-content', 'noindex, nofollow')

@section('meta')
<meta name="description" content="Create a BD Growth Suite customer account to access your Brilliant Directories projects, orders, and dashboard.">
<link rel="canonical" href="https://bdgrowthsuite.com/signup/">
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/bdgs-auth.css">
@endpush

@section('content')
@include('pages.signup.content')
@endsection

@push('page-scripts')
<script src="/js/bdgs-auth.js" defer></script>
@endpush
