@extends('layouts.account')

@section('account-heading', $clinic->exists ? 'Edit Zoom Clinic' : 'New Zoom Clinic')

@section('account-content')
<form method="POST"
      action="{{ $clinic->exists ? route('dashboard.zoom-clinics.update', $clinic->clinic_id) : route('dashboard.zoom-clinics.store') }}"
      class="bdgs-profile-form bdgs-panel-form--solution">
  @csrf
  @if ($clinic->exists) @method('PUT') @endif

  <div class="bdgs-sf__page-header">
    <h2 class="bdgs-sf__page-title">{{ $clinic->exists ? 'Edit Zoom Clinic' : 'Create Zoom Clinic' }}</h2>
    @if ($clinic->exists && $clinic->is_published)
      <a href="{{ url('/zoom-clinics') }}" target="_blank" class="bdgs-sf__view-link">View page</a>
    @endif
  </div>
  <hr class="bdgs-sf__divider">

  @if ($errors->any())
    <div class="bdgs-profile-form__section">
      <div class="bdgs-profile-form__alert bdgs-profile-form__alert--error">
        <p>Please fix the errors below.</p>
      </div>
    </div>
  @endif

  <div class="bdgs-profile-form__section">
    <h2 class="bdgs-profile-form__section-title">Clinic Details</h2>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="zc_title">Title</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="zc_title" name="title" value="{{ old('title', $clinic->title) }}" required>
        @error('title') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_slug">Slug</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="zc_slug" name="slug" value="{{ old('slug', $clinic->slug) }}" placeholder="Auto-generated from title">
        @error('slug') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_agenda">Agenda</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="zc_agenda" name="agenda" value="{{ old('agenda', $clinic->agenda) }}" maxlength="500">
        @error('agenda') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_description">Description</label>
      <div class="bdgs-profile-form__field">
        <textarea id="zc_description" name="description" rows="4">{{ old('description', $clinic->description) }}</textarea>
        @error('description') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_format">Format Note</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="zc_format" name="format_note" value="{{ old('format_note', $clinic->format_note) }}" placeholder="60-min open Q&A with our devs">
        @error('format_note') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>
  </div>

  <div class="bdgs-profile-form__section">
    <h2 class="bdgs-profile-form__section-title">Meeting Link</h2>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="zc_zoom_url">Zoom Link</label>
      <div class="bdgs-profile-form__field">
        <input type="url" id="zc_zoom_url" name="zoom_meeting_url"
               value="{{ old('zoom_meeting_url', $clinic->zoom_meeting_url) }}"
               placeholder="https://zoom.us/j/..." required>
        @error('zoom_meeting_url') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required">Access Type</label>
      <div class="bdgs-profile-form__field">
        <fieldset class="bdgs-sf__toggle" style="margin-bottom:0;">
          <legend class="bdgs-sf__legend">Who can access the meeting link?</legend>
          <label class="bdgs-sf__radio">
            <input type="radio" name="access_type" value="public"
                   @checked(old('access_type', $clinic->access_type ?? 'public') === 'public')>
            Public — anyone can join
          </label>
          <label class="bdgs-sf__radio">
            <input type="radio" name="access_type" value="registered_only"
                   @checked(old('access_type', $clinic->access_type) === 'registered_only')>
            Registered Only
          </label>
        </fieldset>
        @error('access_type') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>
  </div>

  <div class="bdgs-profile-form__section">
    <h2 class="bdgs-profile-form__section-title">Schedule</h2>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="zc_starts">Session Starts At</label>
      <div class="bdgs-profile-form__field">
        <input type="datetime-local" id="zc_starts" name="session_starts_at"
               value="{{ old('session_starts_at', optional($clinic->session_starts_at)->format('Y-m-d\TH:i')) }}" required>
        @error('session_starts_at') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="zc_ends">Session Ends At</label>
      <div class="bdgs-profile-form__field">
        <input type="datetime-local" id="zc_ends" name="session_ends_at"
               value="{{ old('session_ends_at', optional($clinic->session_ends_at)->format('Y-m-d\TH:i')) }}" required>
        @error('session_ends_at') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_buffer">Buffer Ends At</label>
      <div class="bdgs-profile-form__field">
        <input type="datetime-local" id="zc_buffer" name="buffer_ends_at"
               value="{{ old('buffer_ends_at', optional($clinic->buffer_ends_at)->format('Y-m-d\TH:i')) }}">
        @error('buffer_ends_at') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_tz">Timezone</label>
      <div class="bdgs-profile-form__field">
        <input type="text" id="zc_tz" name="source_timezone"
               value="{{ old('source_timezone', $clinic->source_timezone ?? 'Asia/Kolkata') }}" placeholder="Asia/Kolkata">
        @error('source_timezone') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>
  </div>

  <div class="bdgs-profile-form__section">
    <h2 class="bdgs-profile-form__section-title">Publishing</h2>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label bdgs-profile-form__label--required" for="zc_status">Status</label>
      <div class="bdgs-profile-form__field">
        <select id="zc_status" name="status">
          @foreach (['scheduled', 'live', 'completed', 'cancelled'] as $status)
            <option value="{{ $status }}" @selected(old('status', $clinic->status) === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        @error('status') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label">Published</label>
      <div class="bdgs-profile-form__field">
        <fieldset class="bdgs-sf__toggle" style="margin-bottom:0;">
          <label class="bdgs-sf__radio">
            <input type="radio" name="is_published" value="1" @checked(old('is_published', $clinic->is_published ?? true))> Yes
          </label>
          <label class="bdgs-sf__radio">
            <input type="radio" name="is_published" value="0" @checked(!old('is_published', $clinic->is_published ?? true))> No (Draft)
          </label>
        </fieldset>
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label">Featured</label>
      <div class="bdgs-profile-form__field">
        <fieldset class="bdgs-sf__toggle" style="margin-bottom:0;">
          <label class="bdgs-sf__radio">
            <input type="radio" name="is_featured" value="1" @checked(old('is_featured', $clinic->is_featured))> Yes
          </label>
          <label class="bdgs-sf__radio">
            <input type="radio" name="is_featured" value="0" @checked(!old('is_featured', $clinic->is_featured))> No
          </label>
        </fieldset>
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_capacity">Max Capacity</label>
      <div class="bdgs-profile-form__field">
        <input type="number" id="zc_capacity" name="max_capacity"
               value="{{ old('max_capacity', $clinic->max_capacity) }}" min="1" placeholder="Leave empty for unlimited">
        @error('max_capacity') <p class="bdgs-profile-form__error">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="bdgs-profile-form__row">
      <label class="bdgs-profile-form__label" for="zc_sort">Sort Order</label>
      <div class="bdgs-profile-form__field">
        <input type="number" id="zc_sort" name="sort_order" value="{{ old('sort_order', $clinic->sort_order ?? 0) }}">
      </div>
    </div>
  </div>

  @if ($clinic->exists)
    <div class="bdgs-profile-form__section">
      <h2 class="bdgs-profile-form__section-title">Registrations</h2>
      <p><strong>{{ $clinic->confirmed_registrations_count ?? 0 }}</strong> confirmed registration(s)</p>
    </div>
  @endif

  <div class="bdgs-profile-form__section bdgs-profile-form__actions">
    <button type="submit" class="bdgs-account-btn">{{ $clinic->exists ? 'Update Clinic' : 'Create Clinic' }}</button>
    <a href="{{ route('dashboard.zoom-clinics.index') }}" class="bdgs-account-btn bdgs-account-btn--ghost">Back to Zoom Clinics</a>
    @if ($clinic->exists)
      <form method="POST" action="{{ route('dashboard.zoom-clinics.destroy', $clinic->clinic_id) }}"
            onsubmit="return confirm('Delete this zoom clinic and all its registrations?')" style="margin-left:auto;">
        @csrf @method('DELETE')
        <button type="submit" class="bdgs-account-btn bdgs-account-btn--danger">Delete</button>
      </form>
    @endif
  </div>
</form>

@push('account-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
@endsection
