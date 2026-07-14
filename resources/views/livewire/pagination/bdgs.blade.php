@php
    $pageName = $paginator->getPageName();
@endphp

@if ($paginator->hasPages())
  <nav role="navigation" aria-label="Pagination Navigation" class="bdgs-pager">
    <p class="bdgs-pager__summary">
      Showing
      <span>{{ $paginator->firstItem() }}</span>
      to
      <span>{{ $paginator->lastItem() }}</span>
      of
      <span>{{ $paginator->total() }}</span>
      results
    </p>

    <div class="bdgs-pager__links">
      @if ($paginator->onFirstPage())
        <span class="bdgs-pager__btn is-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
          <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </span>
      @else
        <button
          type="button"
          class="bdgs-pager__btn"
          wire:click="previousPage('{{ $pageName }}')"
          onclick="window.bdgsScheduleListScroll && window.bdgsScheduleListScroll()"
          aria-label="{{ __('pagination.previous') }}"
        >
          <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </button>
      @endif

      @foreach ($elements as $element)
        @if (is_string($element))
          <span class="bdgs-pager__btn is-disabled" aria-disabled="true">{{ $element }}</span>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="bdgs-pager__btn is-current" aria-current="page" wire:key="paginator-{{ $pageName }}-page{{ $page }}">{{ $page }}</span>
            @else
              <button
                type="button"
                class="bdgs-pager__btn"
                wire:key="paginator-{{ $pageName }}-page{{ $page }}"
                wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                onclick="window.bdgsScheduleListScroll && window.bdgsScheduleListScroll()"
                aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
              >{{ $page }}</button>
            @endif
          @endforeach
        @endif
      @endforeach

      @if ($paginator->hasMorePages())
        <button
          type="button"
          class="bdgs-pager__btn"
          wire:click="nextPage('{{ $pageName }}')"
          onclick="window.bdgsScheduleListScroll && window.bdgsScheduleListScroll()"
          aria-label="{{ __('pagination.next') }}"
        >
          <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </button>
      @else
        <span class="bdgs-pager__btn is-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
          <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </span>
      @endif
    </div>
  </nav>
@endif
