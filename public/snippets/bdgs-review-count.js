/**
 * BDGS — exact review count from Marketplace API (no "+" suffix).
 * Updates only review-related elements; other stats (500+, 20+, 10+) are untouched.
 */
(function (global) {
  var API = 'https://bdgrowthsuite.com/api/widget/json/get/bdgs-blabs-reviews-api';
  var KEY = '3d5ca826459ec3a3b47675f7ea0a0cd3';
  var FALLBACK = 176;

  function syncBdgsReviewCount(count) {
    var n = String(count);
    var el;

    el = document.getElementById('bdgs-reviews-total-count');
    if (el) el.textContent = n;

    el = document.getElementById('bdgs-footer-reviews-link');
    if (el) el.textContent = 'Client Reviews (' + n + ')';

    el = document.getElementById('bdgs-hero-reviews-count');
    if (el) el.textContent = n;

    el = document.getElementById('bdgs-stat-reviews-count');
    if (el) el.textContent = n;

    el = document.getElementById('bdgs-hero-reviews-btn');
    if (el) el.textContent = 'Read ' + n + ' reviews';

    el = document.getElementById('bdgs-proof-reviews-link');
    if (el) el.textContent = 'See ' + n + ' reviews \u2192';

    var nodes = document.querySelectorAll('[data-bdgs-review-count]');
    for (var i = 0; i < nodes.length; i++) {
      var node = nodes[i];
      var mode = node.getAttribute('data-bdgs-review-count') || 'number';
      if (mode === 'footer') node.textContent = 'Client Reviews (' + n + ')';
      else if (mode === 'see-all') node.textContent = 'See ' + n + ' reviews \u2192';
      else if (mode === 'read-btn') node.textContent = 'Read ' + n + ' reviews';
      else node.textContent = n;
    }
  }

  function fetchBdgsReviewCount() {
    var url = API + '?action=list&published_only=1&limit=1&offset=0&bd_api_key=' + KEY;
    return fetch(url)
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data && data.ok && data.total !== undefined) {
          syncBdgsReviewCount(data.total);
          return data.total;
        }
        syncBdgsReviewCount(FALLBACK);
        return FALLBACK;
      })
      .catch(function () {
        syncBdgsReviewCount(FALLBACK);
        return FALLBACK;
      });
  }

  function autoInit() {
    var hasTarget =
      document.getElementById('bdgs-footer-reviews-link') ||
      document.getElementById('bdgs-hero-reviews-btn') ||
      document.getElementById('bdgs-proof-reviews-link') ||
      document.getElementById('bdgs-stat-reviews-count') ||
      document.getElementById('bdgs-hero-reviews-count') ||
      document.querySelector('[data-bdgs-review-count]');
    /* Reviews page runs its own list fetch — skip duplicate request there */
    if (hasTarget && !document.getElementById('reviewsGrid')) {
      fetchBdgsReviewCount();
    }
  }

  global.BDGS_REVIEW_COUNT = {
    fallback: FALLBACK,
    sync: syncBdgsReviewCount,
    fetch: fetchBdgsReviewCount
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', autoInit);
  } else {
    autoInit();
  }
})(typeof window !== 'undefined' ? window : this);
