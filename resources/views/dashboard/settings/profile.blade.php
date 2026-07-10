@extends('layouts.account')

@section('account-heading', 'Contact Details')

@section('account-content')
<form method="POST" action="{{ route('dashboard.settings.profile.update') }}" class="bdgs-profile-form" id="bdgsProfileForm">
  @csrf
  @method('PUT')

  <div class="bdgs-profile-form__section">
    <h2 class="bdgs-profile-form__section-title">Enter Contact Information Below</h2>

    @if ($errors->any())
      <div class="bdgs-profile-form__alert bdgs-profile-form__alert--error">
        <p>Please fix the errors below.</p>
      </div>
    @endif

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_first_name">First Name</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
        @error('first_name') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_last_name">Last Name</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
        @error('last_name') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_email">Email Address</label>
      <div class="bdgs-profile-form__field">
        <input type="email" id="pf_email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_phone">Phone Number</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_phone" name="phone" value="{{ old('phone', $user->profile?->phone) }}">
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_company">Company Name</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_company" name="company" value="{{ old('company', $user->profile?->company) }}">
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_website">Website</label>
      <div class="bdgs-profile-form__field">
        <input type="url" id="pf_website" name="website" value="{{ old('website', $user->profile?->website) }}" placeholder="ex: https://www.mywebsite.com">
        @error('website') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_position">Your Position</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_position" name="position" value="{{ old('position', $user->profile?->position) }}">
      </div>
    </div>
  </div>

  <div class="bdgs-profile-form__section">
    <h2 class="bdgs-profile-form__section-title">Where Are You Located?</h2>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_address1">Address Line 1</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_address1" name="address_line_1" value="{{ old('address_line_1', $user->profile?->address_line_1) }}">
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_address2">Address Line 2</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_address2" name="address_line_2" value="{{ old('address_line_2', $user->profile?->address_line_2) }}" placeholder="Optional">
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_city">City</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_city" name="city" value="{{ old('city', $user->profile?->city) }}">
        @error('city') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_state">State</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_state" name="state" value="{{ old('state', $user->profile?->state) }}">
        @error('state') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="pf_country">Country</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_country" name="country" value="{{ old('country', $user->profile?->country) }}">
        @error('country') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="pf_postal">Enter Postal Code</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="pf_postal" name="postal_code" value="{{ old('postal_code', $user->profile?->postal_code) }}">
      </div>
    </div>
  </div>

  <div class="bdgs-profile-form__actions">
    <button type="submit" class="bdgs-account-btn">Save &amp; Continue</button>
  </div>
</form>
@endsection
