@extends('layouts.admin')

@section('page-title', ucfirst($type).' Posts')

@section('content')
<p><a class="bdgs-btn" href="{{ route('admin.posts.create', $type) }}">New {{ $dataType->name }}</a>
   <a class="bdgs-btn bdgs-btn--secondary" href="{{ route('admin.categories.index', $type) }}">Categories</a></p>
<table class="bdgs-panel-table">
  <thead><tr><th>Title</th><th>Status</th><th>Updated</th><th></th></tr></thead>
  <tbody>
    @forelse ($posts as $post)
      <tr>
        <td>{{ $post->title }}</td>
        <td>{{ $post->status }}</td>
        <td>{{ $post->updated_at->format('M j, Y') }}</td>
        <td><a href="{{ route('admin.posts.edit', [$type, $post]) }}">Edit</a></td>
      </tr>
    @empty
      <tr><td colspan="4">No posts yet.</td></tr>
    @endforelse
  </tbody>
</table>
{{ $posts->links() }}
@endsection
