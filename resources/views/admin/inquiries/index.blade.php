@extends('layouts.admin')

@section('page-title', 'Inquiries')

@section('content')
<table class="bdgs-panel-table">
  <thead><tr><th>Date</th><th>Name</th><th>Need</th><th>Source</th><th>Status</th><th></th></tr></thead>
  <tbody>
    @foreach ($inquiries as $inquiry)
      <tr>
        <td>{{ $inquiry->submitted_at?->format('M j, Y') }}</td>
        <td>{{ $inquiry->name }}</td>
        <td>{{ $inquiry->need }}</td>
        <td>{{ $inquiry->source }}</td>
        <td>{{ $inquiry->status ?? 'new' }}</td>
        <td><a href="{{ route('admin.inquiries.show', $inquiry) }}">View</a></td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $inquiries->links() }}
@endsection
