@switch($slug)
  @case('blog')
    <svg viewBox="0 0 20 20" fill="none"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" stroke="currentColor" stroke-width="1.5"/><path d="M8 6h4M8 9h4M8 12h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    @break
  @case('event')
    <svg viewBox="0 0 20 20" fill="none"><rect x="3" y="4" width="14" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M3 8h14M7 2v4M13 2v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    @break
  @case('solution')
    <svg viewBox="0 0 20 20" fill="none"><path d="M10 2a5 5 0 013 9v2a1 1 0 01-1 1H8a1 1 0 01-1-1v-2a5 5 0 013-9z" stroke="currentColor" stroke-width="1.5"/><path d="M8 16h4M9 18h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    @break
  @case('tool')
    <svg viewBox="0 0 20 20" fill="none"><path d="M14.5 3a3.5 3.5 0 00-3.27 4.73L4.5 14.5 5.5 15.5l6.77-6.73A3.5 3.5 0 0014.5 3z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><circle cx="14.5" cy="5.5" r="1" fill="currentColor"/></svg>
    @break
  @case('theme')
    <svg viewBox="0 0 20 20" fill="none"><path d="M3 10a7 7 0 1114 0 1 1 0 01-1 1h-2a2 2 0 00-2 2v1a1 1 0 01-1 1 7 7 0 01-8-5z" stroke="currentColor" stroke-width="1.5"/><circle cx="7" cy="8" r="1.2" fill="currentColor"/><circle cx="10" cy="6" r="1.2" fill="currentColor"/><circle cx="13" cy="8" r="1.2" fill="currentColor"/></svg>
    @break
  @default
    <svg viewBox="0 0 20 20" fill="none"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" stroke="currentColor" stroke-width="1.5"/></svg>
@endswitch
