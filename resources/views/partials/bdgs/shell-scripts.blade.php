<script>
// Close modal on Escape (inquiry handled in inquiry-scripts.blade.php)
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    if (typeof bdgsCloseZoomModal === 'function') bdgsCloseZoomModal();
    if (typeof bdgsCloseInquiryModal === 'function') bdgsCloseInquiryModal();
  }
});

// Sticky header
window.addEventListener('scroll', function() {
  document.getElementById('bdgsHeader').classList.toggle('scrolled', window.scrollY > 10);
});

// Menu — click + hover toggle; shared timeout so link→dropdown travel stays open
(function bdgsInitNavDropdowns() {
  var menuHoverTimeout;

  function openNavItem(item) {
    clearTimeout(menuHoverTimeout);
    document.querySelectorAll('.bdgsownv2-nav-item.active').forEach(function(otherItem) {
      if (otherItem !== item) {
        otherItem.classList.remove('active');
      }
    });
    item.classList.add('active');
  }

  function scheduleCloseNavItem(item) {
    clearTimeout(menuHoverTimeout);
    menuHoverTimeout = setTimeout(function() {
      item.classList.remove('active');
    }, 250);
  }

  function closeAllNavItems() {
    clearTimeout(menuHoverTimeout);
    document.querySelectorAll('.bdgsownv2-nav-item.active').forEach(function(navItem) {
      navItem.classList.remove('active');
    });
  }

  document.querySelectorAll('.bdgsownv2-nav-item').forEach(function(item) {
    var link = item.querySelector('.bdgsownv2-nav-link');
    var dropdown = item.querySelector('.bdgsownv2-dropdown');

    if (!dropdown || !link) {
      return;
    }

    link.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      if (item.classList.contains('active')) {
        item.classList.remove('active');
      } else {
        openNavItem(item);
      }
    });

    item.addEventListener('mouseenter', function() {
      openNavItem(item);
    });

    item.addEventListener('mouseleave', function(e) {
      if (dropdown.contains(e.relatedTarget)) {
        return;
      }
      scheduleCloseNavItem(item);
    });

    dropdown.addEventListener('mouseenter', function() {
      openNavItem(item);
    });

    dropdown.addEventListener('mouseleave', function(e) {
      if (item.contains(e.relatedTarget)) {
        return;
      }
      scheduleCloseNavItem(item);
    });
  });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.bdgsownv2-nav-item')) {
      closeAllNavItems();
    }
  });

  document.querySelectorAll('.bdgsownv2-dropdown-item').forEach(function(item) {
    item.addEventListener('click', closeAllNavItems);
  });
})();

(function bdgsInitUserMenu() {
  var menu = document.getElementById('bdgsUserMenu');
  var btn = document.getElementById('bdgsUserMenuBtn');
  var panel = document.getElementById('bdgsUserMenuPanel');
  if (!menu || !btn || !panel) return;

  function closeMenu() {
    menu.classList.remove('is-open');
    btn.setAttribute('aria-expanded', 'false');
    panel.hidden = true;
  }

  function openMenu() {
    menu.classList.add('is-open');
    btn.setAttribute('aria-expanded', 'true');
    panel.hidden = false;
  }

  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    if (menu.classList.contains('is-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('#bdgsUserMenu')) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeMenu();
    }
  });
})();

</script>
