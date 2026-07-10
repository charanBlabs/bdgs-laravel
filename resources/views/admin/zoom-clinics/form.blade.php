@extends('layouts.admin')

@section('page-title', $clinic->exists ? 'Edit Zoom Clinic' : 'New Zoom Clinic')

@section('content')
<form method="POST"
      action="{{ $clinic->exists ? route('admin.zoom-clinics.update', $clinic->clinic_id) : route('admin.zoom-clinics.store') }}"
      class="bdgs-panel-form bdgs-panel-form--solution">
  @csrf
  @if ($clinic->exists) @method('PUT') @endif

  <div class="bdgs-sf__page-header">
    <h2 class="bdgs-sf__page-title">{{ $clinic->exists ? 'Edit Zoom Clinic' : 'Create Zoom Clinic' }}</h2>
    @if ($clinic->exists && $clinic->is_published)
      <a href="{{ url('/zoom-clinics') }}" target="_blank" class="bdgs-sf__view-link">View page</a>
    @endif
  </div>
  <hr class="bdgs-sf__divider">

  <div class="bdgs-sf" style="padding: clamp(20px, 3vw, 32px);">

    {{-- Title --}}
    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label bdgs-sf__label--required" for="zc-title">Title</label>
      <input type="text" id="zc-title" name="title" class="bdgs-sf__input"
             value="{{ old('title', $clinic->title) }}" required>
      @error('title') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    {{-- Slug --}}
    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-slug">Slug</label>
      <input type="text" id="zc-slug" name="slug" class="bdgs-sf__input"
             value="{{ old('slug', $clinic->slug) }}" placeholder="auto-generated from title">
      @error('slug') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    {{-- Zoom Meeting Link --}}
    <div class="bdgs-sf__section-divider">
      <h3 class="bdgs-sf__section-title">Meeting Link</h3>
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label bdgs-sf__label--required" for="zc-zoom-url">Zoom Link</label>
      <input type="url" id="zc-zoom-url" name="zoom_meeting_url" class="bdgs-sf__input"
             value="{{ old('zoom_meeting_url', $clinic->zoom_meeting_url) }}"
             placeholder="https://zoom.us/j/..." required>
      @error('zoom_meeting_url') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    {{-- Access Type --}}
    <fieldset class="bdgs-sf__toggle">
      <legend class="bdgs-sf__legend">Who can access the meeting link?</legend>
      <label class="bdgs-sf__radio">
        <input type="radio" name="access_type" value="public"
               @checked(old('access_type', $clinic->access_type ?? 'public') === 'public')>
        Public — anyone can join with the link
      </label>
      <label class="bdgs-sf__radio">
        <input type="radio" name="access_type" value="registered_only"
               @checked(old('access_type', $clinic->access_type) === 'registered_only')>
        Registered Only — link shown only to registered users
      </label>
      @error('access_type') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </fieldset>

    {{-- Schedule --}}
    <div class="bdgs-sf__section-divider">
      <h3 class="bdgs-sf__section-title">Schedule</h3>
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label bdgs-sf__label--required" for="zc-starts">Session Starts At</label>
      <input type="datetime-local" id="zc-starts" name="session_starts_at" class="bdgs-sf__input"
             value="{{ old('session_starts_at', optional($clinic->session_starts_at)->format('Y-m-d\TH:i')) }}" required>
      @error('session_starts_at') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label bdgs-sf__label--required" for="zc-ends">Session Ends At</label>
      <input type="datetime-local" id="zc-ends" name="session_ends_at" class="bdgs-sf__input"
             value="{{ old('session_ends_at', optional($clinic->session_ends_at)->format('Y-m-d\TH:i')) }}" required>
      @error('session_ends_at') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-buffer">Buffer Ends At</label>
      <input type="datetime-local" id="zc-buffer" name="buffer_ends_at" class="bdgs-sf__input"
             value="{{ old('buffer_ends_at', optional($clinic->buffer_ends_at)->format('Y-m-d\TH:i')) }}">
      @error('buffer_ends_at') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-tz">Timezone</label>
      <input type="text" id="zc-tz" name="source_timezone" class="bdgs-sf__input"
             value="{{ old('source_timezone', $clinic->source_timezone ?? 'Asia/Kolkata') }}"
             placeholder="Asia/Kolkata">
      @error('source_timezone') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    {{-- Details --}}
    <div class="bdgs-sf__section-divider">
      <h3 class="bdgs-sf__section-title">Details</h3>
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-agenda">Agenda</label>
      <input type="text" id="zc-agenda" name="agenda" class="bdgs-sf__input"
             value="{{ old('agenda', $clinic->agenda) }}" maxlength="500">
      @error('agenda') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-description">Description</label>
      <textarea id="zc-description" name="description" class="bdgs-sf__textarea" rows="4">{{ old('description', $clinic->description) }}</textarea>
      @error('description') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-format">Format Note</label>
      <input type="text" id="zc-format" name="format_note" class="bdgs-sf__input"
             value="{{ old('format_note', $clinic->format_note) }}"
             placeholder="60-min open Q&A with our devs">
      @error('format_note') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-capacity">Max Capacity</label>
      <input type="number" id="zc-capacity" name="max_capacity" class="bdgs-sf__input"
             value="{{ old('max_capacity', $clinic->max_capacity) }}" min="1"
             placeholder="Leave empty for unlimited">
      @error('max_capacity') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    {{-- Publishing --}}
    <div class="bdgs-sf__section-divider">
      <h3 class="bdgs-sf__section-title">Publishing</h3>
    </div>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label bdgs-sf__label--required" for="zc-status">Status</label>
      <div class="bdgs-sf__select-wrap">
        <select id="zc-status" name="status" class="bdgs-sf__select">
          @foreach (['scheduled', 'live', 'completed', 'cancelled'] as $status)
            <option value="{{ $status }}" @selected(old('status', $clinic->status) === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>
      @error('status') <p class="bdgs-thumb__error">{{ $message }}</p> @enderror
    </div>

    <fieldset class="bdgs-sf__toggle">
      <legend class="bdgs-sf__legend">Published?</legend>
      <label class="bdgs-sf__radio">
        <input type="radio" name="is_published" value="1"
               @checked(old('is_published', $clinic->is_published ?? true))>
        Yes
      </label>
      <label class="bdgs-sf__radio">
        <input type="radio" name="is_published" value="0"
               @checked(!old('is_published', $clinic->is_published ?? true))>
        No (Draft)
      </label>
    </fieldset>

    <fieldset class="bdgs-sf__toggle">
      <legend class="bdgs-sf__legend">Featured?</legend>
      <label class="bdgs-sf__radio">
        <input type="radio" name="is_featured" value="1"
               @checked(old('is_featured', $clinic->is_featured))>
        Yes
      </label>
      <label class="bdgs-sf__radio">
        <input type="radio" name="is_featured" value="0"
               @checked(!old('is_featured', $clinic->is_featured))>
        No
      </label>
    </fieldset>

    <div class="bdgs-sf__field">
      <label class="bdgs-sf__label" for="zc-sort">Sort Order</label>
      <input type="number" id="zc-sort" name="sort_order" class="bdgs-sf__input"
             value="{{ old('sort_order', $clinic->sort_order ?? 0) }}">
    </div>

    {{-- Registrations count (edit only) --}}
    @if ($clinic->exists)
      <div class="bdgs-sf__section-divider">
        <h3 class="bdgs-sf__section-title">Registrations</h3>
      </div>
      <p style="font-size:0.9375rem;color:#1a1a2e;">
        <strong>{{ $clinic->confirmed_registrations_count ?? 0 }}</strong> confirmed registration(s)
      </p>
    @endif

    {{-- Actions --}}
    <div class="bdgs-sf__actions">
      <button type="submit" class="bdgs-sf__save-btn">{{ $clinic->exists ? 'Update Clinic' : 'Create Clinic' }}</button>
      <a href="{{ route('admin.zoom-clinics.index') }}" class="bdgs-sf__back-link">Back to Zoom Clinics</a>
      @if ($clinic->exists)
        <form method="POST" action="{{ route('admin.zoom-clinics.destroy', $clinic->clinic_id) }}"
              onsubmit="return confirm('Delete this zoom clinic and all its registrations?')"
              style="margin-left:auto;">
          @csrf @method('DELETE')
          <button type="submit" class="bdgs-thumb__remove-btn">Delete</button>
        </form>
      @endif
    </div>

  </div>
</form>

@push('panel-styles')
<link rel="stylesheet" href="/css/bdgs-admin-post-form.css?v={{ time() }}">
@endpush
@endsection
