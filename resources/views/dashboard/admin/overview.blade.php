@extends('layouts.account')

@section('account-heading', 'Site Admin')
@section('account-lead', 'Quick admin tools inside the website. Use the full admin panel for CMS, media, and system settings.')

@section('account-content')
<div class="bdgs-account-stats">
  <div class="bdgs-account-stat"><strong>{{ $stats['users'] }}</strong><span>Users</span></div>
  <div class="bdgs-account-stat"><strong>{{ $stats['posts'] }}</strong><span>Total Posts</span></div>
  <div class="bdgs-account-stat"><strong>{{ $stats['published'] }}</strong><span>Published</span></div>
  <div class="bdgs-account-stat"><strong>{{ $stats['inquiries'] }}</strong><span>Inquiries</span></div>
  <div class="bdgs-account-stat"><strong>{{ $stats['new_inquiries'] }}</strong><span>New Inquiries</span></div>
</div>
<p style="margin-top:20px;"><a href="{{ route('dashboard.admin.inquiries.index') }}" class="bdgs-account-btn">View Inquiries</a>
  <a href="{{ route('admin.dashboard') }}" class="bdgs-account-btn bdgs-account-btn--ghost" style="margin-left:8px;">Full Admin Panel</a></p>
@endsection
