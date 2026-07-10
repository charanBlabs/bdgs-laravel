@extends('layouts.account')

@section('account-heading', 'Profile Photo')

@section('account-content')
<form method="POST" action="{{ route('dashboard.settings.photo.update') }}" enctype="multipart/form-data" id="bdgsPhotoForm">
  @csrf

  <h2 class="bdgs-photo__title">Upload Profile Photo</h2>

  <div class="bdgs-photo__formats-bar">
    <strong>ACCEPTED FORMATS:</strong> JPG, GIF &amp; PNG
  </div>

  <div class="bdgs-photo__cards">
    <div class="bdgs-photo__card">
      <h3 class="bdgs-photo__card-title">Profile Photo</h3>
      <p class="bdgs-photo__card-hint">Recommended Size: 400x400 pixels</p>
      <div class="bdgs-photo__preview" id="bdgsAvatarPreview">
        @if ($user->profilePhotoUrl('thumb'))
          <img src="{{ $user->profilePhotoUrl('thumb') }}" alt="Current profile photo" class="bdgs-photo__preview-img">
        @else
          <img src="{{ asset('images/default-profile.svg') }}" alt="Default profile placeholder" class="bdgs-photo__preview-img bdgs-photo__preview-img--placeholder">
        @endif
      </div>
      <label class="bdgs-photo__upload-btn">
        Upload Photo
        <input type="file" name="avatar" id="bdgsAvatarInput" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
      </label>
    </div>

    <div class="bdgs-photo__card">
      <h3 class="bdgs-photo__card-title">Profile Logo</h3>
      <p class="bdgs-photo__card-hint">Recommended Size: 400x400 pixels</p>
      <div class="bdgs-photo__preview bdgs-photo__preview--logo" id="bdgsLogoPreview">
        @if ($user->companyLogoUrl('thumb'))
          <img src="{{ $user->companyLogoUrl('thumb') }}" alt="Current company logo" class="bdgs-photo__preview-img">
        @else
          <img src="{{ asset('images/default-profile.svg') }}" alt="Default logo placeholder" class="bdgs-photo__preview-img bdgs-photo__preview-img--placeholder">
        @endif
      </div>
      <label class="bdgs-photo__upload-btn bdgs-photo__upload-btn--secondary">
        Upload Logo
        <input type="file" name="logo" id="bdgsLogoInput" accept="image/jpeg,image/png,image/webp,image/svg+xml" hidden>
      </label>
    </div>
  </div>

  <button type="submit" class="bdgs-photo__submit">Save Photos</button>
</form>
@endsection

@push('account-scripts')
<script>
(function () {
  function bindPreview(inputId, previewId, alt) {
    var input = document.getElementById(inputId);
    var preview = document.getElementById(previewId);
    if (!input || !preview) return;
    input.addEventListener('change', function () {
      var file = input.files && input.files[0];
      if (!file || !file.type.startsWith('image/')) return;
      var reader = new FileReader();
      reader.onload = function (e) {
        preview.innerHTML = '<img src="' + e.target.result + '" alt="' + alt + '" class="bdgs-photo__preview-img">';
      };
      reader.readAsDataURL(file);
    });
  }
  bindPreview('bdgsAvatarInput', 'bdgsAvatarPreview', 'Profile photo preview');
  bindPreview('bdgsLogoInput', 'bdgsLogoPreview', 'Company logo preview');
})();
</script>
@endpush
