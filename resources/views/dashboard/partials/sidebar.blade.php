<nav class="bdgs-panel__nav">
  <a href="{{ route('dashboard') }}" @class(['is-active' => request()->routeIs('dashboard')])>Overview</a>
  <a href="{{ route('dashboard.settings.profile') }}" @class(['is-active' => request()->routeIs('dashboard.settings.profile')])>Profile</a>
  <a href="{{ route('dashboard.settings.password') }}" @class(['is-active' => request()->routeIs('dashboard.settings.password')])>Password</a>
  <a href="{{ route('dashboard.settings.photo') }}" @class(['is-active' => request()->routeIs('dashboard.settings.photo')])>Photo & Logo</a>
  <a href="{{ route('dashboard.contact') }}" @class(['is-active' => request()->routeIs('dashboard.contact')])>Contact Us</a>
  <a href="{{ url('/') }}">Back to Site</a>
</nav>
