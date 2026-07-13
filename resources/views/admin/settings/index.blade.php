@extends('layouts.admin')

@section('page-title', 'Site Settings')

@section('content')
@if (session('status'))
  <p class="bdgs-flash bdgs-flash--ok">{{ session('status') }}</p>
@endif
@if ($errors->any())
  <div class="bdgs-flash bdgs-flash--err">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

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

  <h2>Discovery Call (Cal.com)</h2>
  <p class="bdgs-panel-help">Controls the public “Book a 30-min Discovery Call” popup. Paste a new Cal.com element-click embed below to refresh fields, or edit them manually.</p>

  <label>Paste Cal.com embed code (optional)
    <textarea name="cal_embed_paste" rows="8" placeholder="Paste the full &lt;!-- Cal element-click embed code --&gt; snippet from Cal.com…">{{ old('cal_embed_paste') }}</textarea>
  </label>

  <label>Cal link
    <input type="text" name="settings[cal][link]" value="{{ old('settings.cal.link', $cal['link']) }}" placeholder="username/30min">
  </label>
  <label>Namespace
    <input type="text" name="settings[cal][namespace]" value="{{ old('settings.cal.namespace', $cal['namespace']) }}" placeholder="30min">
  </label>
  <label>Origin
    <input type="text" name="settings[cal][origin]" value="{{ old('settings.cal.origin', $cal['origin']) }}" placeholder="https://app.cal.com">
  </label>
  <label>Config JSON
    <textarea name="settings[cal][config]" rows="4">{{ old('settings.cal.config', $cal['config']) }}</textarea>
  </label>

  <button type="submit" class="bdgs-btn">Save Settings</button>
</form>
@endsection
