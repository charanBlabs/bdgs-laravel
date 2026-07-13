/**
 * Progressive enhancement: filters, search, and pagination update
 * without a full page refresh. Mark a region with data-ajax-list="id".
 * GET forms inside that region (or with data-ajax-list-form="id") and
 * pagination links inside it are intercepted.
 */
(function () {
  var ATTR = 'data-ajax-list';
  var FORM_ATTR = 'data-ajax-list-form';
  var inFlight = null;

  function cssEscape(value) {
    if (window.CSS && typeof CSS.escape === 'function') {
      return CSS.escape(value);
    }
    return String(value).replace(/["\\]/g, '\\$&');
  }

  function findRootFrom(el) {
    return el && el.closest ? el.closest('[' + ATTR + ']') : null;
  }

  function rootById(id) {
    if (!id) return null;
    return document.querySelector('[' + ATTR + '="' + cssEscape(id) + '"]');
  }

  function isModifiedClick(e) {
    return e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button === 1;
  }

  function isAjaxListLink(anchor) {
    if (!anchor || anchor.tagName !== 'A' || !anchor.href) return false;
    var href = anchor.getAttribute('href');
    if (!href || href === '#' || href.indexOf('javascript:') === 0) return false;
    if (anchor.hasAttribute('download')) return false;
    if (anchor.target && anchor.target !== '_self') return false;
    if (anchor.closest('.pagination, nav[role="navigation"], .bdgs-content-pagination, [data-ajax-list-pagination]')) {
      return true;
    }
    try {
      var url = new URL(anchor.href, window.location.origin);
      if (url.origin !== window.location.origin) return false;
      // Same path = pagination, clear-filters, or query-only navigation
      if (url.pathname === window.location.pathname) return true;
      return url.searchParams.has('page');
    } catch (err) {
      return false;
    }
  }

  function setLoading(root, on) {
    if (!root) return;
    root.classList.toggle('is-loading', on);
    if (on) {
      root.setAttribute('aria-busy', 'true');
    } else {
      root.removeAttribute('aria-busy');
    }
  }

  function load(root, url, push) {
    if (!root || !url) return;

    if (inFlight && typeof inFlight.abort === 'function') {
      inFlight.abort();
    }

    var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
    inFlight = controller;

    setLoading(root, true);

    var headers = {
      'X-Requested-With': 'XMLHttpRequest',
      Accept: 'text/html',
      'X-BDGS-Ajax-List': root.getAttribute(ATTR) || '1',
    };

    fetch(url, {
      method: 'GET',
      headers: headers,
      credentials: 'same-origin',
      signal: controller ? controller.signal : undefined,
    })
      .then(function (response) {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.text();
      })
      .then(function (html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var id = root.getAttribute(ATTR);
        var next = doc.querySelector('[' + ATTR + '="' + cssEscape(id) + '"]');
        if (!next) {
          window.location.href = url;
          return;
        }

        root.innerHTML = next.innerHTML;
        setLoading(root, false);

        if (push) {
          history.pushState({ bdgsAjaxList: id }, '', url);
        }

        var title = doc.querySelector('title');
        if (title && title.textContent) {
          document.title = title.textContent;
        }

        if (root.getBoundingClientRect().top < 80) {
          root.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        document.dispatchEvent(
          new CustomEvent('bdgs:ajax-list-loaded', {
            bubbles: true,
            detail: { id: id, root: root, url: url },
          })
        );
      })
      .catch(function (err) {
        if (err && err.name === 'AbortError') return;
        window.location.href = url;
      })
      .finally(function () {
        if (inFlight === controller) {
          inFlight = null;
        }
        setLoading(root, false);
      });
  }

  function formUrl(form) {
    var action = form.getAttribute('action') || window.location.pathname + window.location.search;
    var url = new URL(action, window.location.origin);
    var params = new URLSearchParams();
    var fd = new FormData(form);

    fd.forEach(function (value, key) {
      if (value === null || value === undefined) return;
      var str = String(value).trim();
      if (str === '') return;
      params.append(key, str);
    });

    url.search = params.toString();
    return url.toString();
  }

  document.addEventListener(
    'click',
    function (e) {
      if (e.defaultPrevented || isModifiedClick(e)) return;
      var anchor = e.target.closest && e.target.closest('a');
      if (!anchor) return;
      if (anchor.target && anchor.target !== '_self') return;

      var root = findRootFrom(anchor);
      if (!root || !isAjaxListLink(anchor)) return;

      e.preventDefault();
      load(root, anchor.href, true);
    },
    true
  );

  document.addEventListener(
    'submit',
    function (e) {
      var form = e.target;
      if (!form || form.tagName !== 'FORM') return;
      if ((form.getAttribute('method') || 'get').toLowerCase() !== 'get') return;

      var root = findRootFrom(form);
      if (!root) {
        root = rootById(form.getAttribute(FORM_ATTR));
      }
      if (!root) return;

      e.preventDefault();
      load(root, formUrl(form), true);
    },
    true
  );

  window.addEventListener('popstate', function () {
    var root = document.querySelector('[' + ATTR + ']');
    if (!root) return;
    load(root, window.location.href, false);
  });

  // Reusable actions menus inside ajax-replaced lists
  document.addEventListener('click', function (e) {
    var btn = e.target.closest && e.target.closest('.bdgs-content-item__actions-btn');
    if (btn) {
      e.stopPropagation();
      var menu = btn.nextElementSibling;
      var isOpen = menu && menu.classList.contains('is-open');
      document.querySelectorAll('.bdgs-content-item__actions-menu.is-open').forEach(function (m) {
        m.classList.remove('is-open');
      });
      if (menu && !isOpen) menu.classList.add('is-open');
      return;
    }

    document.querySelectorAll('.bdgs-content-item__actions-menu.is-open').forEach(function (m) {
      m.classList.remove('is-open');
    });
  });
})();
