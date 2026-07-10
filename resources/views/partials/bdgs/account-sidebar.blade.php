@php
    $user = auth()->user();
    $user?->loadMissing(['profile.avatar', 'profile.logo', 'roles']);
    $isAdmin = $user?->canManageContent() ?? false;
@endphp

<aside class="bdgs-account__sidebar" aria-label="Account navigation">
  <div class="bdgs-account__user-card">
    <div class="bdgs-account__avatar-wrap">
      @include('partials.bdgs.user-avatar', ['user' => $user, 'size' => 110, 'class' => 'bdgs-account__avatar'])
    </div>
    <p class="bdgs-account__user-name">{{ $user->fullName() }}</p>
    <p class="bdgs-account__user-meta">Member #{{ $user->id }}</p>
    <p class="bdgs-account__user-meta">Level: {{ $user->primaryRoleLabel() }}</p>
  </div>

  <nav class="bdgs-account__nav">
    <a href="{{ route('dashboard') }}" @class(['is-active' => request()->routeIs('dashboard') && ! request()->routeIs('dashboard.admin.*')])>
      <svg viewBox="0 0 20 20" fill="none"><path d="M3 10.5L10 4l7 6.5V17a1 1 0 01-1 1H4a1 1 0 01-1-1v-6.5z" stroke="currentColor" stroke-width="1.5"/></svg>
      My Dashboard
    </a>

    @cannot('manage-content')
      <a href="{{ route('dashboard.services') }}" @class(['is-active' => request()->routeIs('dashboard.services')])>
        <svg viewBox="0 0 20 20" fill="none"><path d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" stroke="currentColor" stroke-width="1.5"/><path d="M8 7h4M8 10h4M8 13h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        My Services
      </a>

      <a href="{{ route('dashboard.orders') }}" @class(['is-active' => request()->routeIs('dashboard.orders')])>
        <svg viewBox="0 0 20 20" fill="none"><path d="M3 6a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V6z" stroke="currentColor" stroke-width="1.5"/><path d="M3 9h14" stroke="currentColor" stroke-width="1.5"/><circle cx="7" cy="13" r="1" fill="currentColor"/><circle cx="10" cy="13" r="1" fill="currentColor"/></svg>
        My Orders
      </a>

      <a href="{{ route('dashboard.tickets') }}" @class(['is-active' => request()->routeIs('dashboard.tickets')])>
        <svg viewBox="0 0 20 20" fill="none"><path d="M3 5.5A1.5 1.5 0 014.5 4h11A1.5 1.5 0 0117 5.5v7a1.5 1.5 0 01-1.5 1.5H7l-4 3v-10.5z" stroke="currentColor" stroke-width="1.5"/><path d="M7 8h6M7 11h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        My Tickets
      </a>
    @endcannot

    @can('manage-content')
      @php($postTypes = \App\Models\BdgsDataType::query()->where('is_active', true)->withCount('posts')->orderBy('sort_order')->get())
      @foreach ($postTypes as $pt)
        <a href="{{ route('dashboard.content.index', $pt->slug) }}" @class(['is-active' => request()->is("dashboard/content/{$pt->slug}*")])>
          @include('partials.bdgs.post-type-icon', ['slug' => $pt->slug])
          {{ $pt->pluralLabel() }}
          <span class="bdgs-account__nav-badge">{{ $pt->posts_count }}</span>
        </a>
      @endforeach

      @php($zoomClinicCount = \App\Models\BdgsZoomClinic::query()->count())
      <a href="{{ route('dashboard.zoom-clinics.index') }}" @class(['is-active' => request()->routeIs('dashboard.zoom-clinics.*')])>
        <svg viewBox="0 0 20 20" fill="none"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" stroke="currentColor" stroke-width="1.5"/><path d="M7 10h6M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Zoom Clinics
        <span class="bdgs-account__nav-badge">{{ $zoomClinicCount }}</span>
      </a>
    @endcan

    <div class="bdgs-account__nav-group">
      <button type="button" class="bdgs-account__nav-toggle" data-group="manage" aria-expanded="true">
        <svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M3.5 18c0-3.59 2.91-6.5 6.5-6.5s6.5 2.91 6.5 6.5" stroke="currentColor" stroke-width="1.5"/></svg>
        Manage Account
        <svg class="bdgs-account__nav-chevron" viewBox="0 0 12 12" fill="none"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
      </button>
      <div class="bdgs-account__nav-sub" data-group-panel="manage">
        <a href="{{ route('dashboard.settings.profile') }}" @class(['is-active' => request()->routeIs('dashboard.settings.profile')])>Contact Details</a>
        <a href="{{ route('dashboard.settings.photo') }}" @class(['is-active' => request()->routeIs('dashboard.settings.photo')])>Profile Photo</a>
        <a href="{{ route('dashboard.settings.password') }}" @class(['is-active' => request()->routeIs('dashboard.settings.password')])>Password</a>
      </div>
    </div>

    <a href="{{ route('dashboard.notifications.index') }}" @class(['is-active' => request()->routeIs('dashboard.notifications.*')])>
      <svg viewBox="0 0 20 20" fill="none"><path d="M10 2a5.5 5.5 0 00-5.5 5.5v3.59l-.9 1.81A.75.75 0 004.27 14h11.46a.75.75 0 00.67-1.1l-.9-1.81V7.5A5.5 5.5 0 0010 2z" stroke="currentColor" stroke-width="1.5"/><path d="M8 14v.5a2 2 0 004 0V14" stroke="currentColor" stroke-width="1.5"/></svg>
      Notifications
    </a>

    @cannot('manage-content')
      <a href="{{ route('dashboard.contact') }}" @class(['is-active' => request()->routeIs('dashboard.contact*')])>
        <svg viewBox="0 0 20 20" fill="none"><path d="M3 5.5A1.5 1.5 0 014.5 4h11A1.5 1.5 0 0117 5.5v9a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 14.5v-9z" stroke="currentColor" stroke-width="1.5"/><path d="M3 6l7 5 7-5" stroke="currentColor" stroke-width="1.5"/></svg>
        Contact Us
      </a>
    @endcannot

    @can('manage-content')
      <div class="bdgs-account__nav-divider"></div>
      <p class="bdgs-account__nav-label bdgs-account__nav-label--admin">Site Admin</p>
      <a href="{{ route('dashboard.admin.overview') }}" @class(['is-active' => request()->routeIs('dashboard.admin.overview')])>
        <svg viewBox="0 0 20 20" fill="none"><rect x="3" y="3" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="3" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/><rect x="3" y="11" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/><rect x="11" y="11" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="1.5"/></svg>
        Admin Overview
      </a>
      <a href="{{ route('dashboard.admin.inquiries.index') }}" @class(['is-active' => request()->routeIs('dashboard.admin.inquiries.*')])>
        <svg viewBox="0 0 20 20" fill="none"><path d="M4 4h12v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" stroke="currentColor" stroke-width="1.5"/><path d="M4 4h12M8 8h4M8 11h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        Inquiries
      </a>
      <a href="{{ route('admin.dashboard') }}" class="bdgs-account__nav-full-admin">
        <svg viewBox="0 0 20 20" fill="none"><path d="M12 3l5 5-9.5 9.5H3V13L12 3z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
        Full Admin Panel →
      </a>
    @endcan
  </nav>

  <form method="POST" action="{{ route('logout') }}" class="bdgs-account__logout">
    @csrf
    <button type="submit">
      <svg viewBox="0 0 20 20" fill="none"><path d="M7 17H4a1 1 0 01-1-1V4a1 1 0 011-1h3M14 14l4-4-4-4M8 10h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Log out
    </button>
  </form>
</aside>
