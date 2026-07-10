(function () {
  'use strict';

  var modal = document.getElementById('bdgs-sol-demo-modal');

  if (modal) {
    var embedHost = modal.querySelector('[data-bdgs-demo-embed]');

    function openDemo(html) {
      if (!html || !embedHost) return;
      embedHost.innerHTML = html;
      modal.hidden = false;
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closeDemo() {
      modal.hidden = true;
      modal.setAttribute('aria-hidden', 'true');
      if (embedHost) embedHost.innerHTML = '';
      document.body.style.overflow = '';
    }

    document.addEventListener('click', function (event) {
      var openBtn = event.target.closest('[data-bdgs-demo-open]');
      if (openBtn) {
        var templateId = openBtn.getAttribute('data-demo-template');
        var html = '';

        if (templateId) {
          var tpl = document.getElementById(templateId);
          html = tpl ? tpl.innerHTML : '';
        } else {
          html = openBtn.getAttribute('data-demo-html') || '';
        }

        openDemo(html);
        return;
      }

      if (event.target.closest('[data-bdgs-demo-close]')) {
        closeDemo();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !modal.hidden) {
        closeDemo();
      }
    });
  }

  var wrap = document.querySelector('[data-bdgs-content-wrap]');
  if (!wrap) return;

  var body = wrap.querySelector('[data-bdgs-content-body]');
  var btn = wrap.querySelector('[data-bdgs-show-more]');
  if (!body || !btn) return;

  var mobileMax = 960;
  var collapseHeight = 440;

  function resetState() {
    wrap.classList.remove('is-collapsible', 'is-collapsed', 'is-expanded');
    btn.hidden = true;
  }

  function applyCollapse() {
    if (window.innerWidth > mobileMax) {
      resetState();
      return;
    }

    wrap.classList.remove('is-collapsed', 'is-expanded');
    var needsCollapse = body.scrollHeight > collapseHeight;

    if (!needsCollapse) {
      wrap.classList.remove('is-collapsible');
      btn.hidden = true;
      return;
    }

    wrap.classList.add('is-collapsible');

    if (wrap.dataset.bdgsExpanded === 'true') {
      wrap.classList.add('is-expanded');
      btn.hidden = true;
      return;
    }

    wrap.classList.add('is-collapsed');
    btn.hidden = false;
  }

  btn.addEventListener('click', function () {
    wrap.dataset.bdgsExpanded = 'true';
    wrap.classList.remove('is-collapsed');
    wrap.classList.add('is-expanded');
    btn.hidden = true;
  });

  applyCollapse();
  window.addEventListener('resize', applyCollapse);
})();
