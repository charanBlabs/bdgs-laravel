@extends('layouts.admin')

@section('page-title', 'URL Redirects')

@section('content')
<form method="POST" action="{{ route('admin.redirects.store') }}" class="bdgs-panel-form">
  @csrf
  <label>From URL<input type="text" name="from_url" required placeholder="/old-path/"></label>
  <label>To URL<input type="text" name="to_url" required placeholder="/new-path/"></label>
  <label>Status Code
    <select name="status_code">
      <option value="301">301 Permanent</option>
      <option value="302">302 Temporary</option>
    </select>
  </label>
  <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
  <button type="submit" class="bdgs-btn">Add Redirect</button>
</form>
<table class="bdgs-panel-table" style="margin-top:24px;">
  <thead><tr><th>From</th><th>To</th><th>Code</th><th>Active</th><th></th></tr></thead>
  <tbody>
    @foreach ($redirects as $redirect)
      <tr>
        <td>{{ $redirect->from_url }}</td>
        <td>{{ $redirect->to_url }}</td>
        <td>{{ $redirect->status_code }}</td>
        <td>{{ $redirect->is_active ? 'Yes' : 'No' }}</td>
        <td>
          <form method="POST" action="{{ route('admin.redirects.destroy', $redirect) }}" onsubmit="return confirm('Delete?')">
            @csrf @method('DELETE')
            <button type="submit">Delete</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $redirects->links() }}
@endsection
