<script>
function bdgsOpenInquiryModal() {
  document.getElementById('bdgsInquiryModal').classList.add('active');
  document.getElementById('bdgsInquiryFormWrap').style.display = 'block';
  document.getElementById('bdgsInquiryThanks').classList.remove('active');
  document.body.style.overflow = 'hidden';
}
function bdgsCloseInquiryModal() {
  document.getElementById('bdgsInquiryModal').classList.remove('active');
  document.body.style.overflow = '';
}
// Close inquiry modal on overlay click (zoom modal: close button / Escape only)
document.getElementById('bdgsInquiryModal').addEventListener('click', function(e) {
  if (e.target === this) bdgsCloseInquiryModal();
});
document.getElementById('bdgsInquiryForm').addEventListener('submit', function(e) {
  e.preventDefault();
  document.getElementById('bdgsInquiryFormWrap').style.display = 'none';
  document.getElementById('bdgsInquiryThanks').classList.add('active');
});
// Close modal on Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    if (typeof bdgsCloseZoomModal === 'function') bdgsCloseZoomModal();
    bdgsCloseInquiryModal();
  }
});

// Sticky header
window.addEventListener('scroll', function() {
  document.getElementById('bdgsHeader').classList.toggle('scrolled', window.scrollY > 10);
});

// Menu - click + hover based toggle with 0.5s grace period
var menuHoverTimeout;
document.querySelectorAll('.bdgsownv2-nav-item').forEach(function(item) {
  var link = item.querySelector('.bdgsownv2-nav-link');
  var dropdown = item.querySelector('.bdgsownv2-dropdown');

  if (dropdown && link) {
    // Click to toggle
    link.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      document.querySelectorAll('.bdgsownv2-nav-item.active').forEach(function(otherItem) {
        if (otherItem !== item) {
          otherItem.classList.remove('active');
        }
      });

      item.classList.toggle('active');
    });

    // Hover to open (no click needed)
    item.addEventListener('mouseenter', function() {
      clearTimeout(menuHoverTimeout);

      document.querySelectorAll('.bdgsownv2-nav-item.active').forEach(function(otherItem) {
        if (otherItem !== item) {
          otherItem.classList.remove('active');
        }
      });

      item.classList.add('active');
    });

    // 0.5s grace period before closing on mouseleave
    item.addEventListener('mouseleave', function() {
      var currentItem = item;
      menuHoverTimeout = setTimeout(function() {
        currentItem.classList.remove('active');
      }, 500);
    });
  }
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.bdgsownv2-nav-item')) {
    clearTimeout(menuHoverTimeout);
    document.querySelectorAll('.bdgsownv2-nav-item.active').forEach(function(item) {
      item.classList.remove('active');
    });
  }
});

// Close dropdown when clicking on a dropdown item
document.querySelectorAll('.bdgsownv2-dropdown-item').forEach(function(item) {
  item.addEventListener('click', function() {
    clearTimeout(menuHoverTimeout);
    document.querySelectorAll('.bdgsownv2-nav-item.active').forEach(function(navItem) {
      navItem.classList.remove('active');
    });
  });
});

</script>
