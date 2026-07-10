@extends('layouts.admin')

@section('page-title', 'Site Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="bdgs-panel-form">
  @csrf @method('PUT')
  @foreach (['general' => $general, 'seo' => $seo, 'mail' => $mail, 'social' => $social] as $group => $pairs)
    <h2>{{ ucfirst($group) }}</h2>
    @foreach ($pairs as $key => $value)
      <label>{{ str_replace('_', ' ', ucfirst($key)) }}
        <input type="text" name="settings[{{ $group }}][{{ $key }}]" value="{{ old("settings.{$group}.{$key}", $value) }}">
      </label>
    @endforeach
  @endforeach
  <button type="submit" class="bdgs-btn">Save Settings</button>
</form>
@endsection
