@extends('layouts.admin')

@section('page-title', 'Email Templates')

@section('content')
<table class="bdgs-panel-table">
  <thead><tr><th>Name</th><th>Slug</th><th>Active</th><th></th></tr></thead>
  <tbody>
    @foreach ($templates as $template)
      <tr>
        <td>{{ $template->name }}</td>
        <td>{{ $template->slug }}</td>
        <td>{{ $template->is_active ? 'Yes' : 'No' }}</td>
        <td><a href="{{ route('admin.email-templates.edit', $template) }}">Edit</a></td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
