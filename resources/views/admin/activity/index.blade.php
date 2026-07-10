@extends('layouts.admin')

@section('page-title', 'Activity Log')

@section('content')
<form method="GET" class="bdgs-panel-form">
  <label>Action<input type="text" name="action" value="{{ $action }}" placeholder="created, updated, login"></label>
  <button type="submit" class="bdgs-btn bdgs-btn--secondary">Filter</button>
</form>
<table class="bdgs-panel-table" style="margin-top:16px;">
  <thead><tr><th>When</th><th>User</th><th>Action</th><th>Subject</th></tr></thead>
  <tbody>
    @foreach ($logs as $log)
      <tr>
        <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
        <td>{{ $log->user?->fullName() ?? '—' }}</td>
        <td>{{ $log->action }}</td>
        <td>{{ $log->subject_type ? class_basename($log->subject_type).' #'.$log->subject_id : '—' }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $logs->links() }}
@endsection
