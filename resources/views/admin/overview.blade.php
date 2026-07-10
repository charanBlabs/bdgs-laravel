@extends('layouts.admin')

@section('page-title', 'Admin Overview')

@section('content')
<div class="bdgs-stats">
  <div class="bdgs-stat-card"><strong>{{ $stats['users'] }}</strong><span>Users</span></div>
  <div class="bdgs-stat-card"><strong>{{ $stats['posts'] }}</strong><span>Total Posts</span></div>
  <div class="bdgs-stat-card"><strong>{{ $stats['published'] }}</strong><span>Published</span></div>
  <div class="bdgs-stat-card"><strong>{{ $stats['inquiries'] }}</strong><span>Inquiries</span></div>
  <div class="bdgs-stat-card"><strong>{{ $stats['new_inquiries'] }}</strong><span>New Inquiries</span></div>
</div>
@endsection
