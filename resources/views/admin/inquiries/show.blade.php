@extends('layouts.admin')

@section('page-title', 'Inquiry #'.$inquiry->inquiry_id)

@section('content')
<div class="bdgs-card">
  <p><strong>{{ $inquiry->name }}</strong> — {{ $inquiry->email }} — {{ $inquiry->phone }}</p>
  <p><strong>Need:</strong> {{ $inquiry->need }}</p>
  <p>{{ $inquiry->message }}</p>
</div>

<form method="POST" action="{{ route('admin.inquiries.reply', $inquiry) }}" class="bdgs-panel-form" style="margin-top:24px;">
  @csrf
  <label>Reply<textarea name="admin_reply" required>{{ old('admin_reply', $inquiry->admin_reply) }}</textarea></label>
  <button type="submit" class="bdgs-btn">Send Reply</button>
</form>
@endsection
