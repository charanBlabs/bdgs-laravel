@extends('layouts.account')

@section('account-heading', 'Inquiries')
@section('account-lead', 'Review and respond to customer inquiries from the website.')

@section('account-content')
<div data-ajax-list="dashboard-inquiries">
  <div class="bdgs-account-table-wrap">
    <table class="bdgs-account-table">
      <thead><tr><th>Date</th><th>Name</th><th>Need</th><th>Source</th><th>Status</th><th></th></tr></thead>
      <tbody>
        @forelse ($inquiries as $inquiry)
          <tr>
            <td>{{ $inquiry->submitted_at?->format('M j, Y') }}</td>
            <td>{{ $inquiry->name }}</td>
            <td>{{ $inquiry->need }}</td>
            <td>{{ $inquiry->source }}</td>
            <td>{{ $inquiry->status ?? 'new' }}</td>
            <td><a href="{{ route('dashboard.admin.inquiries.show', $inquiry) }}">View</a></td>
          </tr>
        @empty
          <tr><td colspan="6">No inquiries yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px;">{{ $inquiries->links() }}</div>
</div>
@endsection
