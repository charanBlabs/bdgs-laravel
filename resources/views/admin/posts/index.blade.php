@extends('layouts.admin')

@section('page-title', ucfirst($type).' Posts')

@section('content')
<p><a class="bdgs-btn" href="{{ route('admin.posts.create', $type) }}">New {{ $dataType->name }}</a>
   <a class="bdgs-btn bdgs-btn--secondary" href="{{ route('admin.categories.index', $type) }}">Categories</a></p>
<div data-ajax-list="admin-posts-{{ $type }}">
  <table class="bdgs-panel-table">
    <thead><tr><th>Image</th><th>Title</th><th>Status</th><th>Updated</th><th></th></tr></thead>
    <tbody>
      @forelse ($posts as $post)
        <tr>
          <td>
            <div class="bdgs-panel-thumb">
              @if ($post->featuredMedia)
                @php($media = $post->featuredMedia)
                <img
                  src="{{ $media->url('medium') }}"
                  srcset="{{ $media->srcset(['medium', 'large']) }}"
                  sizes="120px"
                  alt="{{ $post->title }}"
                  loading="lazy"
                  decoding="async"
                  width="{{ $media->dimensions('medium')[0] ?? '' }}"
                  height="{{ $media->dimensions('medium')[1] ?? '' }}"
                >
              @else
                <span class="bdgs-panel-thumb__empty">No image</span>
              @endif
            </div>
          </td>
          <td>{{ $post->title }}</td>
          <td>{{ $post->status }}</td>
          <td>{{ $post->updated_at->format('M j, Y') }}</td>
          <td><a href="{{ route('admin.posts.edit', [$type, $post]) }}">Edit</a></td>
        </tr>
      @empty
        <tr><td colspan="5">No posts yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  {{ $posts->links() }}
</div>
@endsection
