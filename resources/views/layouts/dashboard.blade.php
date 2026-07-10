<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('panel-title', 'Dashboard') — BD Growth Suite</title>
@include('partials.bdgs.head-assets')
<link rel="stylesheet" href="/css/bdgs-shell.css">
<link rel="stylesheet" href="/css/bdgs-dashboard.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stack('panel-styles')
</head>
<body class="bdgs-panel">
<div class="bdgs-panel__shell">
  <aside class="bdgs-panel__sidebar">
    <div class="bdgs-panel__brand">
      <a href="{{ url('/') }}">BD Growth Suite</a>
      <span>{{ $panelType ?? 'Dashboard' }}</span>
    </div>
    @yield('sidebar')
    <form method="POST" action="{{ route('logout') }}" class="bdgs-panel__logout">
      @csrf
      <button type="submit">Log out</button>
    </form>
  </aside>
  <div class="bdgs-panel__main">
    <header class="bdgs-panel__topbar">
      <h1>@yield('page-title', 'Overview')</h1>
      <div class="bdgs-panel__topbar-actions">
        @yield('topbar-actions')
        <span class="bdgs-panel__user">{{ auth()->user()?->fullName() }}</span>
      </div>
    </header>
    @if (session('status'))
      <div class="bdgs-panel__flash">{{ session('status') }}</div>
    @endif
    <main class="bdgs-panel__content">
      @yield('content')
    </main>
  </div>
</div>
<script src="/js/bdgs-dashboard.js" defer></script>
@stack('panel-scripts')
</body>
</html>
