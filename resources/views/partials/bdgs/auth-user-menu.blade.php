@php
    $authUser = auth()->user();
    $authUser->loadMissing(['profile.avatar', 'profile.logo', 'roles']);
@endphp

<div class="bdgs-user-menu" id="bdgsUserMenu">
  <button
    type="button"
    class="bdgs-user-menu__trigger"
    id="bdgsUserMenuBtn"
    aria-haspopup="true"
    aria-expanded="false"
    aria-controls="bdgsUserMenuPanel"
    aria-label="{{ $authUser->fullName() }} account menu"
  >
    @include('partials.bdgs.user-avatar', [
        'user' => $authUser,
        'size' => 30,
        'class' => 'bdgs-user-avatar--circle bdgs-user-menu__avatar',
    ])
    <span class="bdgs-user-menu__trigger-name">{{ explode(' ', $authUser->fullName())[0] }}</span>
    <svg class="bdgs-user-menu__chevron" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
  </button>

  <div class="bdgs-user-menu__panel" id="bdgsUserMenuPanel" role="menu" aria-labelledby="bdgsUserMenuBtn" hidden>
    <div class="bdgs-user-menu__profile" aria-hidden="true">
      @include('partials.bdgs.user-avatar', [
          'user' => $authUser,
          'size' => 44,
          'class' => 'bdgs-user-avatar--circle',
      ])
      <div class="bdgs-user-menu__profile-meta">
        <p class="bdgs-user-menu__name">{{ $authUser->fullName() }}</p>
        <p class="bdgs-user-menu__role">{{ $authUser->primaryRoleLabel() }}</p>
        <p class="bdgs-user-menu__email">{{ $authUser->email }}</p>
      </div>
    </div>
    <div class="bdgs-user-menu__actions">
      <a href="{{ route('dashboard') }}" class="bdgs-user-menu__item bdgs-user-menu__item--primary" role="menuitem">Dashboard</a>
      <form method="POST" action="{{ route('logout') }}" class="bdgs-user-menu__logout">
        @csrf
        <button type="submit" class="bdgs-user-menu__item bdgs-user-menu__item--ghost" role="menuitem">Logout</button>
      </form>
    </div>
  </div>
</div>
