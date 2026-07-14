/**
 * Smooth-scroll to list top after Livewire pagination.
 * CSP-safe: no eval / Alpine expressions.
 */
(function () {
  var scrollTimer = null;

  function scrollToList() {
    var target = document.querySelector('[data-bdgs-scroll-on-page]');
    if (!target) return;

    try {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } catch (err) {
      var top = target.getBoundingClientRect().top + window.pageYOffset - 16;
      window.scrollTo(0, Math.max(0, top));
    }
  }

  /**
   * Called from pager buttons via onclick. Retries briefly so we scroll
   * after Livewire finishes morphing the new page into the DOM.
   */
  window.bdgsScheduleListScroll = function () {
    if (scrollTimer) {
      window.clearTimeout(scrollTimer);
      scrollTimer = null;
    }

    var attempts = 0;
    var run = function () {
      attempts += 1;
      scrollToList();
      if (attempts < 4) {
        scrollTimer = window.setTimeout(run, 120);
      }
    };

    // First attempt after Livewire request has a moment to land.
    scrollTimer = window.setTimeout(run, 60);
  };

  // Backup: server event from UsesBdgsPagination@updatedPaginators
  window.addEventListener('bdgs-scroll-list-top', function (e) {
    if (!e || !e.__livewire) return;
    window.bdgsScheduleListScroll();
  });

  // Backup: catch pager clicks even if onclick is missing
  document.addEventListener(
    'click',
    function (e) {
      var btn = e.target && e.target.closest && e.target.closest('.bdgs-pager button.bdgs-pager__btn');
      if (btn) window.bdgsScheduleListScroll();
    },
    true
  );
})();
