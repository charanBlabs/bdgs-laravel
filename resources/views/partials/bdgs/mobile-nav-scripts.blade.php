<script>
(function() {
  var overlay = document.getElementById('bdgsMobOverlay');
  var openBtn = document.getElementById('bdgsMobMenuBtn');
  var closeBtn = document.getElementById('bdgsMobCloseBtn');
  var panels = document.getElementById('bdgsMobPanels');
  var scrollTopBeforeLock = 0;
  var animating = false;
  var ANIM_DURATION = 300;

  if (!overlay || !openBtn || !closeBtn || !panels) return;

  function clearPanelClasses(panel) {
    panel.classList.remove(
      'bdgs-mob-panel--active',
      'bdgs-mob-panel--exit-left',
      'bdgs-mob-panel--enter-left',
      'bdgs-mob-panel--exit-right'
    );
  }

  function transitionPanel(targetId, direction) {
    if (animating) return;
    var current = panels.querySelector('.bdgs-mob-panel--active');
    var target = panels.querySelector('[data-panel="' + targetId + '"]');
    if (!target || target === current) return;

    animating = true;

    if (direction === 'forward') {
      target.style.transition = 'none';
      target.classList.remove('bdgs-mob-panel--exit-left', 'bdgs-mob-panel--enter-left', 'bdgs-mob-panel--exit-right');
      target.style.transform = 'translateX(100%)';
      target.style.opacity = '0';
      target.offsetHeight;
      target.style.transition = '';
      target.style.transform = '';
      target.style.opacity = '';
      target.classList.add('bdgs-mob-panel--active');
      if (current) {
        current.classList.remove('bdgs-mob-panel--active');
        current.classList.add('bdgs-mob-panel--exit-left');
      }
    } else {
      target.style.transition = 'none';
      target.classList.remove('bdgs-mob-panel--exit-left', 'bdgs-mob-panel--enter-left', 'bdgs-mob-panel--exit-right');
      target.style.transform = 'translateX(-100%)';
      target.style.opacity = '0';
      target.offsetHeight;
      target.style.transition = '';
      target.style.transform = '';
      target.style.opacity = '';
      target.classList.add('bdgs-mob-panel--active');
      if (current) {
        current.classList.remove('bdgs-mob-panel--active');
        current.classList.add('bdgs-mob-panel--exit-right');
      }
    }

    var scrollEl = target.querySelector('.bdgs-mob-panel-scroll');
    if (scrollEl) scrollEl.scrollTop = 0;

    setTimeout(function() {
      if (current) clearPanelClasses(current);
      animating = false;
    }, ANIM_DURATION);
  }

  function resetToMain() {
    var allP = panels.querySelectorAll('.bdgs-mob-panel');
    allP.forEach(function(p) {
      clearPanelClasses(p);
      p.style.transition = 'none';
      p.style.transform = '';
      p.style.opacity = '';
      if (p.dataset.panel === 'main') {
        p.classList.add('bdgs-mob-panel--active');
      }
      p.offsetHeight;
      p.style.transition = '';
    });
    animating = false;
  }

  function openMenu() {
    scrollTopBeforeLock = window.pageYOffset;
    document.body.style.top = '-' + scrollTopBeforeLock + 'px';
    document.body.classList.add('bdgs-mob-noscroll');
    overlay.classList.add('bdgs-mob-overlay--open');
    overlay.setAttribute('aria-hidden', 'false');
    openBtn.setAttribute('aria-expanded', 'true');
    openBtn.setAttribute('aria-label', 'Close menu');
    openBtn.classList.add('bdgs-mob-hamburger--active');
  }

  function closeMenu() {
    overlay.classList.remove('bdgs-mob-overlay--open');
    overlay.setAttribute('aria-hidden', 'true');
    openBtn.setAttribute('aria-expanded', 'false');
    openBtn.setAttribute('aria-label', 'Open menu');
    openBtn.classList.remove('bdgs-mob-hamburger--active');
    document.body.classList.remove('bdgs-mob-noscroll');
    document.body.style.top = '';
    window.scrollTo(0, scrollTopBeforeLock);
    setTimeout(resetToMain, ANIM_DURATION + 50);
  }

  openBtn.addEventListener('click', function() {
    if (overlay.classList.contains('bdgs-mob-overlay--open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });
  closeBtn.addEventListener('click', closeMenu);

  var mobCta = overlay.querySelector('.bdgs-mob-cta');
  if (mobCta) {
    mobCta.addEventListener('click', function(e) {
      e.preventDefault();
      if (typeof bdgsOpenInquiryModal === 'function') bdgsOpenInquiryModal();
      closeMenu();
    });
  }

  overlay.addEventListener('click', function(e) {
    var drillable = e.target.closest('.bdgs-mob-item--drillable');
    if (drillable) {
      transitionPanel(drillable.dataset.target, 'forward');
      return;
    }
    var backBtn = e.target.closest('.bdgs-mob-back');
    if (backBtn) {
      transitionPanel(backBtn.dataset.back, 'back');
      return;
    }
    var alsoPill = e.target.closest('[data-nav-to]');
    if (alsoPill) {
      e.preventDefault();
      transitionPanel(alsoPill.dataset.navTo, 'forward');
      return;
    }
    var link = e.target.closest('a[href]:not([data-nav-to])');
    if (link && link.getAttribute('href').startsWith('#')) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && overlay.classList.contains('bdgs-mob-overlay--open')) {
      closeMenu();
    }
  });

  window.addEventListener('resize', function() {
    if (window.innerWidth > 1029 && overlay.classList.contains('bdgs-mob-overlay--open')) {
      closeMenu();
    }
  });
})();
</script>
