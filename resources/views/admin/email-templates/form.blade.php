@extends('layouts.admin')



@section('page-title', 'Edit Template')



@section('content')

<div class="bdgs-et">

  <div class="bdgs-et__header">

    <div>

      <p class="bdgs-et__slug">{{ $template->slug }}</p>

      <p class="bdgs-et__hint">Use <code>@{{ variable }}</code> placeholders in the subject and HTML body. They are replaced automatically when the email is sent.</p>

    </div>

    @if ($template->variables)

      <div class="bdgs-et__vars">

        <span class="bdgs-et__vars-label">Available variables</span>

        <div class="bdgs-et__vars-list">

          @foreach ($template->variables as $variable)

            <code>{!! '{{ ' . e($variable) . ' }}' !!}</code>

          @endforeach

        </div>

      </div>

    @endif

  </div>



  <form method="POST" action="{{ route('admin.email-templates.update', $template) }}" class="bdgs-panel-form bdgs-panel-form--email" id="bdgs-email-template-form">

    @csrf @method('PUT')



    <div class="bdgs-et__grid bdgs-et__grid--meta">

      <label class="bdgs-et__field" for="et-name">

        <span class="bdgs-et__label">Template name</span>

        <input type="text" id="et-name" name="name" value="{{ old('name', $template->name) }}" required>

      </label>



      <label class="bdgs-et__field" for="et-subject">

        <span class="bdgs-et__label">Email subject</span>

        <input type="text" id="et-subject" name="subject" value="{{ old('subject', $template->subject) }}" required>

      </label>

    </div>



    <div class="bdgs-et__field bdgs-et__field--editor">

      <label class="bdgs-et__label" for="bdgs-email-html-editor">HTML body</label>

      <p class="bdgs-et__help">Rich email content sent to recipients. This is the main template most clients display.</p>

      <textarea id="bdgs-email-html-editor" name="body_html" rows="18">{{ old('body_html', $template->body_html) }}</textarea>

    </div>



    <details class="bdgs-et__advanced" @if(old('body_text', $template->body_text)) open @endif>

      <summary>Advanced options</summary>

      <div class="bdgs-et__advanced-body">

        <label class="bdgs-et__field" for="et-body-text">

          <span class="bdgs-et__label">Plain text body <span class="bdgs-et__optional">(optional)</span></span>

          <p class="bdgs-et__help">Fallback for email clients that do not render HTML. Leave blank to auto-generate from the HTML body when sending.</p>

          <textarea id="et-body-text" name="body_text" rows="6" class="bdgs-et__textarea-plain">{{ old('body_text', $template->body_text) }}</textarea>

        </label>

      </div>

    </details>



    <div class="bdgs-et__status">

      <label class="bdgs-et__toggle" for="et-is-active">

        <input type="checkbox" id="et-is-active" name="is_active" value="1" @checked(old('is_active', $template->is_active))>

        <span class="bdgs-et__toggle-track" aria-hidden="true"><span class="bdgs-et__toggle-thumb"></span></span>

        <span class="bdgs-et__toggle-text">

          <strong>Active</strong>

          <span>When enabled, this template is used for live emails (signup, inquiry, orders, etc.). Turn off to keep it saved but not sent — useful for templates not wired up yet.</span>

        </span>

      </label>

    </div>



    <div class="bdgs-et__actions">

      <button type="submit" class="bdgs-btn">Save Template</button>

    </div>

  </form>



  <form method="POST" action="{{ route('admin.email-templates.test', $template) }}" class="bdgs-panel-form bdgs-panel-form--email bdgs-et__test" id="bdgs-email-test-form">

    @csrf

    <h2 class="bdgs-et__section-title">Send test email</h2>

    <p class="bdgs-et__help">Sends this template with sample placeholder values so you can preview it in your inbox.</p>

    <div class="bdgs-et__test-row">

      <label class="bdgs-et__field bdgs-et__field--grow" for="et-test-email">

        <span class="bdgs-et__label">Test recipient</span>

        <input type="email" id="et-test-email" name="test_email" required placeholder="you@example.com">

      </label>

      <button type="submit" class="bdgs-btn bdgs-btn--secondary">Send Test</button>

    </div>

  </form>

</div>

@endsection



@push('panel-styles')

<link rel="stylesheet" href="/css/bdgs-admin-email-form.css?v={{ filemtime(public_path('css/bdgs-admin-email-form.css')) }}">

@endpush



@push('panel-scripts')

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>

<script src="/js/bdgs-admin-editor.js?v={{ filemtime(public_path('js/bdgs-admin-editor.js')) }}"></script>

<script>

(function () {

  function syncEditors() {

    if (typeof tinymce !== 'undefined') {

      tinymce.triggerSave();

    }

  }



  ['bdgs-email-template-form', 'bdgs-email-test-form'].forEach(function (id) {

    var form = document.getElementById(id);

    if (form) {

      form.addEventListener('submit', syncEditors);

    }

  });

})();

</script>

@endpush

