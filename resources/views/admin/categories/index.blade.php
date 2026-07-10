@extends('layouts.admin')

@section('page-title', ucfirst($type).' Categories')

@section('content')
<form method="POST" action="{{ route('admin.categories.store', $type) }}" class="bdgs-panel-form">
  @csrf
  <label>Name<input type="text" name="name" required></label>
  <label>Slug<input type="text" name="slug"></label>
  <label>Description<textarea name="description"></textarea></label>
  <button type="submit" class="bdgs-btn">Add Category</button>
</form>
<table class="bdgs-panel-table" style="margin-top:24px;">
  <thead><tr><th>Name</th><th>Slug</th><th></th></tr></thead>
  <tbody>
    @foreach ($categories as $category)
      <tr>
        <td>{{ $category->name }}</td>
        <td>{{ $category->slug }}</td>
        <td>
          <form method="POST" action="{{ route('admin.categories.destroy', [$type, $category]) }}" onsubmit="return confirm('Delete?')">
            @csrf @method('DELETE')
            <button type="submit">Delete</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
