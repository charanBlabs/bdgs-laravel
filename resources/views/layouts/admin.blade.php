@extends('layouts.dashboard')

@section('panel-title', 'Full Admin')
@php($panelType = 'Full Admin')

@section('sidebar')
@php($adminPostTypes = \App\Models\BdgsDataType::query()->where('is_active', true)->orderBy('sort_order')->get())
<nav class="bdgs-panel__nav">
  <a href="{{ route('admin.dashboard') }}" @class(['is-active' => request()->routeIs('admin.dashboard')])>Overview</a>
  @foreach ($adminPostTypes as $pt)
    <a href="{{ route('admin.posts.index', $pt->slug) }}" @class(['is-active' => request()->is("admin/posts/{$pt->slug}*")])>{{ $pt->pluralLabel() }}</a>
  @endforeach
  <a href="{{ route('admin.media.index') }}" @class(['is-active' => request()->routeIs('admin.media.*')])>Media</a>
  <a href="{{ route('admin.zoom-clinics.index') }}" @class(['is-active' => request()->routeIs('admin.zoom-clinics.*')])>Zoom Clinics</a>
  <a href="{{ route('admin.inquiries.index') }}" @class(['is-active' => request()->routeIs('admin.inquiries.*')])>Inquiries</a>
  <a href="{{ route('admin.email-templates.index') }}" @class(['is-active' => request()->routeIs('admin.email-templates.*')])>Email Templates</a>
  <a href="{{ route('admin.settings.index') }}" @class(['is-active' => request()->routeIs('admin.settings.*')])>Settings</a>
  <a href="{{ route('admin.activity.index') }}" @class(['is-active' => request()->routeIs('admin.activity.*')])>Activity Log</a>
  <a href="{{ route('admin.redirects.index') }}" @class(['is-active' => request()->routeIs('admin.redirects.*')])>Redirects</a>
  <a href="{{ route('dashboard') }}">Site Dashboard</a>
  <a href="{{ route('dashboard.admin.overview') }}">Site Admin</a>
  <a href="{{ url('/') }}">View Site</a>
</nav>
@endsection
