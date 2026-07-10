@extends('layouts.bdgs')
@php($showFab = false)
@section('title')<title>Error — BD Growth Suite</title>@endsection
@section('content')
<section class="bdgs-section"><div class="bdgs-container"><h1>Error {{ $status ?? '' }}</h1><p>{{ $message ?? 'An error occurred.' }}</p></div></section>
@endsection
