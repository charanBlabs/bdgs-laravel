@extends('layouts.account')

@section('account-heading', 'My Dashboard')

@section('account-content')
@can('manage-content')
  @php($publishTypes = \App\Models\BdgsDataType::query()->where('is_active', true)->orderBy('sort_order')->get())
  <div class="bdgs-publish-card">
    <h2>
      <svg viewBox="0 0 20 20" fill="none" width="20" height="20"><path d="M12 3l5 5-9.5 9.5H3V13L12 3z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
      Publish Content
    </h2>
    <div class="bdgs-publish-card__grid">
      @foreach ($publishTypes as $pt)
        <a href="{{ route('dashboard.content.create', $pt->slug) }}" class="bdgs-publish-card__item">
          @include('partials.bdgs.post-type-icon', ['slug' => $pt->slug])
          {{ $pt->name }}
        </a>
      @endforeach
    </div>
  </div>
@endcan

<div class="bdgs-account__grid">
  <div class="bdgs-account-card">
    <h2>Manage Account</h2>
    <div class="bdgs-account-card__links">
      <a href="{{ route('dashboard.settings.profile') }}" class="bdgs-account-card__action">Contact Details</a>
      <a href="{{ route('dashboard.settings.photo') }}" class="bdgs-account-card__action bdgs-account-card__action--secondary">Profile Photo</a>
    </div>
  </div>
  <div class="bdgs-account-card">
    <h2>Account Details</h2>
    <ul class="bdgs-account-card__details">
      <li><span>Joined:</span> <strong>{{ $user->created_at->format('m/d/Y') }}</strong></li>
      <li><span>Level:</span> <strong>{{ $user->primaryRoleLabel() }}</strong></li>
      <li><span>Status:</span> <strong>{{ $user->is_active ? 'Active' : 'Inactive' }}</strong></li>
    </ul>
    <div class="bdgs-account-card__links" style="margin-top: 12px;">
      <a href="{{ route('dashboard.settings.password') }}" class="bdgs-account-card__detail-link">Change Password</a>
      <form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf<button type="submit" class="bdgs-account-card__detail-link bdgs-account-card__detail-link--danger">Logout</button></form>
    </div>
  </div>
  @can('manage-content')
  <div class="bdgs-account-card">
    <h2>Site Admin</h2>
    <p>Review inquiries and quick stats without leaving the website.</p>
    <div class="bdgs-account-card__links">
      <a href="{{ route('dashboard.admin.overview') }}" class="bdgs-account-card__action">Open Site Admin</a>
      <a href="{{ route('admin.dashboard') }}" class="bdgs-account-card__action bdgs-account-card__action--secondary">Full Admin Panel →</a>
    </div>
  </div>
  @endcan
</div>
@endsection
