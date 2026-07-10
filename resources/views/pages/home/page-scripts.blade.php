@include('partials.bdgs.zoom-clinic-scripts')
<script>
// Stack cards now handled purely by CSS sticky positioning

// Tab → card mapping (tab href matches card ID)
var bdgsTabs = document.querySelectorAll('.bdgsownv2-stack-tab');
var bdgsStackCards = document.querySelectorAll('.bdgsownv2-stack-card');
var bdgsStackSectionEl = document.querySelector('.bdgsownv2-services-stack');

/** Must stay in sync with `.bdgsownv2-stack-cards > .bdgsownv2-stack-card:nth-child(n){ top: … }` */
var bdgsStackStickyTopsPx = [140, 182, 224, 266, 308, 350];
var bdgsStackScrollTargetsPx = [];
var bdgsStackTabScrolling = false;

function bdgsStackScrollOffsetPx() {
  return window.matchMedia('(min-width: 992px)').matches ? 168 : 112;
}

function bdgsDocTopFromViewport(el) {
  var rect = el.getBoundingClientRect();
  return rect.top + window.scrollY;
}

/** Flow-layout scroll targets (sticky getBoundingClientRect lies while stacked). */
function bdgsCacheStackScrollTargets() {
  if (!bdgsStackCards.length) return;
  var stackDesktop = window.matchMedia('(min-width: 992px)').matches;
  var container = document.querySelector('.bdgsownv2-stack-cards');
  if (!container) return;

  var saved = [];
  var i;
  for (i = 0; i < bdgsStackCards.length; i++) {
    saved.push({
      position: bdgsStackCards[i].style.position,
      transform: bdgsStackCards[i].style.transform
    });
    bdgsStackCards[i].style.position = 'static';
    bdgsStackCards[i].style.transform = 'none';
  }

  var containerDoc = container.getBoundingClientRect().top + window.scrollY;
  bdgsStackScrollTargetsPx = [];
  for (i = 0; i < bdgsStackCards.length; i++) {
    var rail = stackDesktop ? bdgsStackStickyTopsPx[i] : bdgsStackScrollOffsetPx();
    bdgsStackScrollTargetsPx[i] = Math.max(0, containerDoc + bdgsStackCards[i].offsetTop - rail);
  }

  for (i = 0; i < bdgsStackCards.length; i++) {
    bdgsStackCards[i].style.position = saved[i].position;
    bdgsStackCards[i].style.transform = saved[i].transform;
  }
}

// 1. Tab click → smooth scroll to card + immediate active
bdgsTabs.forEach(function(tab) {
  tab.addEventListener('click', function(e) {
    e.preventDefault();
    var targetId = this.getAttribute('href').replace('#', '');
    var target = document.getElementById(targetId);
    if (!target) return;
    var idx = Array.prototype.indexOf.call(bdgsStackCards, target);
    if (idx < 0) return;

    if (!bdgsStackScrollTargetsPx.length) bdgsCacheStackScrollTargets();
    var top = bdgsStackScrollTargetsPx[idx];
    if (top == null) return;

    bdgsStackTabScrolling = true;
    bdgsTabs.forEach(function(t, i) {
      t.classList.toggle('active', i === idx);
    });
    window.scrollTo({ top: top, behavior: 'smooth' });
    window.setTimeout(function() {
      bdgsStackTabScrolling = false;
      bdgsUpdateActiveTab();
    }, 900);
  });
});

// 2. Auto-activate tab — see bdgsUpdateActiveTab() comment (desktop stack rail + fallbacks).

/**
 * Desktop stack: several cards can be sticky at their CSS `top` at once (each layer has its own rail).
 * Tab must track the visually front layer (highest z-index among layers currently parked on the rail).
 *
 * Rule:
 * 1) "At rail" for card i: rect.bottom shows it is on-screen, and rect.top is within RAIL_STRICT_PX of
 *    bdgsStackStickyTopsPx[i] (must match nth-child `top` in CSS).
 * 2) If multiple indices qualify (common while scrolling the stack), take Math.max — highest card wins.
 * 3) If none qualify in the strict band (subpixel / zoom / in-between scroll), take cards whose top is
 *    within a looser band under their rail (still "in the stack") and pick the one with the largest
 *    rect.bottom (extends lowest in the viewport ≈ front face of the pile); ties → higher index.
 * 4) Else fall back to document probe (same idea as mobile).
 * 5) If the whole services section has scrolled past above the viewport, keep the last tab (Growth)
 *    so the strip does not snap backward to Setup.
 */
function bdgsUpdateActiveTab() {
  if (bdgsStackTabScrolling) return;
  var activeIndex = 0;
  var stackDesktop = window.matchMedia('(min-width: 992px)').matches;
  var n = bdgsStackCards.length;

  if (!stackDesktop) {
    var probeY = window.scrollY + 130;
    for (var j = n - 1; j >= 0; j--) {
      if (probeY + 1 >= bdgsDocTopFromViewport(bdgsStackCards[j])) {
        activeIndex = j;
        break;
      }
    }
  } else {
    if (bdgsStackSectionEl) {
      var srect = bdgsStackSectionEl.getBoundingClientRect();
      if (srect.bottom < 80) {
        activeIndex = n - 1;
        bdgsTabs.forEach(function(tab, k) {
          tab.classList.toggle('active', k === activeIndex);
        });
        return;
      }
    }

    var minBottom = 100;
    var RAIL_STRICT_PX = 22;
    var foundActive = -1;

    for (var i = 0; i < n; i++) {
      var rect = bdgsStackCards[i].getBoundingClientRect();
      var t = bdgsStackStickyTopsPx[i];
      if (rect.bottom <= minBottom) continue;

      // If the card has reached its sticky rail (or been pushed past it), it's a candidate.
      // Since later cards stack on top, the highest index candidate is the visible front card.
      if (rect.top <= t + RAIL_STRICT_PX) {
        foundActive = i;
      }
    }

    if (foundActive >= 0) {
      activeIndex = foundActive;
    } else {
      var probeY2 = window.scrollY + bdgsStackScrollOffsetPx();
      for (var jj = n - 1; jj >= 0; jj--) {
        if (probeY2 + 1 >= bdgsDocTopFromViewport(bdgsStackCards[jj])) {
          activeIndex = jj;
          break;
        }
      }
    }
  }
  bdgsTabs.forEach(function(tab, k) {
    tab.classList.toggle('active', k === activeIndex);
  });
  bdgsStackCards.forEach(function(card, k) {
    if (k < activeIndex) {
      // Stacked behind
      var scale = 1 - (activeIndex - k) * 0.03;
      card.style.transform = 'scale(' + scale + ')';
    } else {
      // Active or upcoming
      card.style.transform = 'scale(1)';
    }
  });
}

// Throttled scroll + resize
var bdgsScrollTicking = false;
function bdgsScheduleActiveTab() {
  if (!bdgsScrollTicking) {
    window.requestAnimationFrame(function() {
      bdgsUpdateActiveTab();
      bdgsScrollTicking = false;
    });
    bdgsScrollTicking = true;
  }
}
window.addEventListener('scroll', bdgsScheduleActiveTab, { passive: true });
window.addEventListener('resize', bdgsScheduleActiveTab);

// Initial state
bdgsUpdateActiveTab();

// Mobile: IntersectionObserver for active card tracking (tabs hidden; keeps state for anchor jumps)
if (window.matchMedia('(max-width: 991px)').matches && bdgsStackCards.length) {
  var mobileObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting && entry.intersectionRatio >= 0.4) {
        var idx = Array.prototype.indexOf.call(bdgsStackCards, entry.target);
        bdgsTabs.forEach(function(t, i) {
          t.classList.toggle('active', i === idx);
        });
      }
    });
  }, { threshold: 0.4 });
  bdgsStackCards.forEach(function(card) { mobileObserver.observe(card); });
}

// Smooth scroll for Explore Services links to perfectly align with the first card
var svcLinks = document.querySelectorAll('a[href="#svc"]');
svcLinks.forEach(function(link) {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    var target = document.getElementById('svc-setup');
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// Sync card heights so shorter cards fully cover taller cards stacked behind them
function bdgsSyncCardHeights() {
  if (!window.matchMedia('(min-width: 992px)').matches) {
    bdgsStackCards.forEach(function(c) { c.style.height = ''; });
    bdgsCacheStackScrollTargets();
    return;
  }
  var maxH = 0;
  // Reset first to find natural height
  bdgsStackCards.forEach(function(c) {
    c.style.height = '';
  });
  // Measure tallest
  bdgsStackCards.forEach(function(c) {
    var h = c.offsetHeight;
    if (h > maxH) maxH = h;
  });
  // Apply to all using height, not minHeight, so absolute children stretch properly
  bdgsStackCards.forEach(function(c) {
    c.style.height = maxH + 'px';
  });
  bdgsCacheStackScrollTargets();
}
window.addEventListener('resize', bdgsSyncCardHeights);
window.addEventListener('load', function() {
  bdgsSyncCardHeights();
  bdgsCacheStackScrollTargets();
  bdgsUpdateActiveTab();
});
// Run aggressively during page load to catch font wrapping changes (desktop only)
if (window.matchMedia('(min-width: 992px)').matches) {
  setTimeout(bdgsSyncCardHeights, 50);
  setTimeout(bdgsSyncCardHeights, 500);
  setTimeout(bdgsSyncCardHeights, 2000);
}

// Zoom Clinics — 6:30–7:30 PM Asia/Kolkata (Tue/Thu anchor) → visitor local window
(function bdgsRenderZoomClinicsLocalTime() {
  var el = document.getElementById('bdgs-zoom-clinics-local-time');
  if (!el) return;

  var INDIA_ZONES = { 'Asia/Kolkata': 1, 'Asia/Calcutta': 1 };
  var IST_OFFSET_MIN = 330;
  var INDIA_RANGE = '6:30–7:30 PM IST';

  function tzOffsetMinutesAt(instant, tz) {
    var parts = new Intl.DateTimeFormat('en-US', {
      timeZone: tz,
      timeZoneName: 'longOffset',
      hour: 'numeric'
    }).formatToParts(instant);
    var name = '';
    for (var i = 0; i < parts.length; i++) {
      if (parts[i].type === 'timeZoneName') name = parts[i].value;
    }
    var m = name.match(/GMT([+-])(\d{1,2})(?::(\d{2}))?/);
    if (!m) return null;
    var sign = m[1] === '+' ? 1 : -1;
    return sign * (parseInt(m[2], 10) * 60 + (m[3] ? parseInt(m[3], 10) : 0));
  }

  function isIndiaVisitor(userTz, sessionInstant) {
    if (INDIA_ZONES[userTz]) return true;
    var userOff = tzOffsetMinutesAt(sessionInstant, userTz);
    var istOff = tzOffsetMinutesAt(sessionInstant, BDGS_ZOOM_CANONICAL_TZ);
    return userOff !== null && userOff === istOff && userOff === IST_OFFSET_MIN;
  }

  function clockParts(instant, tz, withTz) {
    var opts = {
      timeZone: tz,
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    };
    if (withTz) opts.timeZoneName = 'short';
    var map = {};
    new Intl.DateTimeFormat('en-US', opts).formatToParts(instant).forEach(function(p) {
      if (p.type !== 'literal') map[p.type] = p.value;
    });
    return map;
  }

  function hm(p) {
    return p.minute === '00' ? p.hour + ':00' : p.hour + ':' + p.minute;
  }

  function formatVisitorLocalRange(startInstant, endInstant, tz) {
    var sp = clockParts(startInstant, tz, false);
    var ep = clockParts(endInstant, tz, true);
    var tzAbbr = ep.timeZoneName ? ' ' + ep.timeZoneName : '';
    if (sp.dayPeriod === ep.dayPeriod) {
      return hm(sp) + '–' + hm(ep) + ' ' + ep.dayPeriod + tzAbbr;
    }
    return hm(sp) + ' ' + sp.dayPeriod + '–' + hm(ep) + ' ' + ep.dayPeriod + tzAbbr;
  }

  try {
    var session = bdgsGetNextZoomSession();
    var userTz = Intl.DateTimeFormat().resolvedOptions().timeZone || BDGS_ZOOM_CANONICAL_TZ;

    if (isIndiaVisitor(userTz, session.start)) {
      el.textContent = INDIA_RANGE;
    } else {
      el.textContent = formatVisitorLocalRange(session.start, session.end, userTz);
    }
  } catch (err) {
    el.textContent = INDIA_RANGE;
  }
})();
</script>

<!-- Dynamic review fetcher & Variant 2 Carousel logic -->
<script>
(function() {
  // Reviews from Laravel DB (SSR window.bdgsCarouselReviews, else public API)
  function resolveCarouselData() {
    if (window.bdgsCarouselReviews && window.bdgsCarouselReviews.length) {
      return Promise.resolve({
        ok: true,
        reviews: window.bdgsCarouselReviews,
        total: window.bdgsReviewCount || window.bdgsCarouselReviews.length
      });
    }
    return fetch('/api/reviews/list?published_only=1&limit=200&offset=0')
      .then(function (r) { return r.json(); });
  }

  var bdgsownv2Track = document.getElementById('bdgsownv2-track');
  var bdgsownv2ProgressFill = document.querySelector('.bdgsownv2-progress-fill');
  var BDGSOWNV2_INTERVAL_MS = 9000;
  var bdgsownv2Timer = null;
  var bdgsownv2Paused = false;
  var bdgsownv2CurrentIndex = Math.floor(50 / 2);
        bdgsownv2CurrentIndex = bdgsownv2CurrentIndex - (bdgsownv2CurrentIndex % 5);
  var fetchedReviews = [];

  var FALLBACK_REVIEWS = [
    {
      review_date: '7 May 2026',
      title: 'Highly Recommend BusinessLabs',
      overall_rating: 5, service_rating: 5, responsiveness_rating: 5, expertise_rating: 5, results_rating: 5, communication_rating: 5,
      review_text: 'Great communication, project delivered on time, first class customer service. Would not hesitate to use BusinessLabs for future products.',
      marketplace_review_id: '1609'
    },
    {
      review_date: '30 Jan 2026',
      title: 'Impressive Response & Service',
      overall_rating: 5, service_rating: 5, responsiveness_rating: 5, expertise_rating: 5, results_rating: 5, communication_rating: 5,
      review_text: 'We have recently implemented the Google Review Plugin, and the experience has been nothing short of impressive. The team from Business Labs impressed us with their quick response. Every time we had a question, we got an answer right away — always polite and professional. We recommend them.',
      marketplace_review_id: '1588'
    },
    {
      review_date: '26 Jan 2026',
      title: 'Sensible Suggestions & Professional Work',
      overall_rating: 5, service_rating: 5, responsiveness_rating: 5, expertise_rating: 5, results_rating: 5, communication_rating: 5,
      review_text: 'I can confidently recommend Business Labs. They helped design and build our directory website and were excellent from start to finish, clear communication, fast turnaround, and a very professional approach. They understood what I was trying to achieve, made sensible suggestions, and implemented changes quickly. The site is now far more polished and easier for users to navigate.',
      marketplace_review_id: '1586'
    },
    {
      review_date: '24 Jan 2026',
      title: 'BLabs Really Knows BD Inside and Out',
      overall_rating: 5, service_rating: 5, responsiveness_rating: 5, expertise_rating: 5, results_rating: 5, communication_rating: 5,
      review_text: "Brilliant Directories is a powerful directory solution, but with all those features come lots of options and settings. We use Brilliant Labs to get things done quickly without having to learn every little thing ourselves. They're super easy to work with. We just enter a ticket in the portal and typically someone takes care of it by the next day. Like having an expert we can check in with whenever we need to instead of hiring our own person.",
      marketplace_review_id: '1585'
    }
  ];

  function getStars(num) {
    var starSVG = '<svg viewBox="0 0 24 24" width="11" height="11" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
    var html = '';
    for (var i = 0; i < num; i++) {
      html += starSVG;
    }
    return html;
  }

  function escapeHtml(str) {
    if (!str) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
  }

  function anonymizeReviewText(text) {
    if (!text) return '';
    var clean = text
      .replace(/arendal\.net/gi, 'our directory website')
      .replace(/ConnectCare\.ie/gi, 'our directory website')
      .replace(/uppercervicalcare\.com/gi, 'our directory website')
      .replace(/RealEstatePhotography\.com/gi, 'our directory website')
      .replace(/petrolheadlife\.com/gi, 'our directory website');
    clean = clean.replace(/\b([a-z0-9]+(-[a-z0-9]+)*\.)+(com|net|org|ie|co|io|edu|gov|co\.uk)\b/gi, 'our directory website');
    return escapeHtml(clean);
  }

  function createBdgsownv2CardHTML(review) {
    var ratingCategories = [
      { label: 'Overall', value: review.overall_rating },
      { label: 'Service', value: review.service_rating },
      { label: 'Responsiveness', value: review.responsiveness_rating },
      { label: 'Expertise', value: review.expertise_rating },
      { label: 'Results', value: review.results_rating },
      { label: 'Communication', value: review.communication_rating },
    ];

    var pillsHTML = ratingCategories.map(function(cat) {
      return '<span class="bdgsownv2-rating-pill">' + cat.label + ' <span class="pill-stars">' + getStars(cat.value) + '</span></span>';
    }).join('');

    var dateObj = new Date(review.review_date);
    var dateStr = '';
    if (isNaN(dateObj.getTime())) { 
      // If review_date is already a formatted string like '7 May 2026'
      dateStr = review.review_date;
    } else {
      dateStr = dateObj.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
    }
    var cleanText = anonymizeReviewText(review.review_text);
    var cleanTitle = anonymizeReviewText(review.title);
    var rawVerifyLink = review.marketplace_review_id || review.review_id ? 'https://marketplace.brilliantdirectories.com/india/partner/business-labs/reviews/' + (review.marketplace_review_id || review.review_id) : 'https://marketplace.brilliantdirectories.com/india/partner/business-labs';
    var verifyLink = /^https?:\/\//i.test(rawVerifyLink) ? escapeHtml(rawVerifyLink) : 'https://marketplace.brilliantdirectories.com/india/partner/business-labs';

    return '<div class="v2-review-card">' +
      '<div class="bdgsownv2-card-header">' +
        '<span class="bdgsownv2-submitted-by">Submitted by Verified Brilliant Directories Site Owner on ' + dateStr + '</span>' +
        '<h3 class="bdgsownv2-review-title">' + cleanTitle + '</h3>' +
      '</div>' +
      '<div class="bdgsownv2-rating-pills">' + pillsHTML + '</div>' +
      '<p class="bdgsownv2-review-text">' + cleanText + '</p>' +
      '<div class="bdgsownv2-card-footer">' +
        '<span class="bdgsownv2-verified-badge">' +
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>' +
          'Verified Client Review' +
        '</span>' +
        '<a class="bdgsownv2-verify-link" href="' + verifyLink + '" target="_blank" rel="noopener">' +
          'Verified on Marketplace ' +
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>' +
        '</a>' +
      '</div>' +
    '</div>';
  }

  function startBdgsownv2Progress() {
    if (bdgsownv2ProgressFill) {
      bdgsownv2ProgressFill.style.transition = 'none';
      bdgsownv2ProgressFill.style.width = '0%';
      void bdgsownv2ProgressFill.offsetWidth;
      bdgsownv2ProgressFill.style.transition = 'width ' + BDGSOWNV2_INTERVAL_MS + 'ms linear';
      bdgsownv2ProgressFill.style.width = '100%';
    }
  }

  function resetBdgsownv2Progress() {
    if (bdgsownv2ProgressFill) {
      bdgsownv2ProgressFill.style.transition = 'none';
      bdgsownv2ProgressFill.style.width = '0%';
    }
  }

  function getBdgsownv2CenteredIndex() {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    if (!cards.length) return 0;
    var trackRect = bdgsownv2Track.getBoundingClientRect();
    var trackCenter = trackRect.left + trackRect.width / 2;
    var closestIdx = 0;
    var closestDist = Infinity;
    cards.forEach(function(card, idx) {
      var cardRect = card.getBoundingClientRect();
      var cardCenter = cardRect.left + cardRect.width / 2;
      var dist = Math.abs(cardCenter - trackCenter);
      if (dist < closestDist) {
        closestDist = dist;
        closestIdx = idx;
      }
    });
    return closestIdx;
  }

  function setBdgsownv2ActiveIndex(idx, skipScroll) {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    if (!cards.length) return;
    bdgsownv2CurrentIndex = idx;
    cards.forEach(function(card, i) {
      card.classList.toggle('active', i === idx);
    });
    if (!skipScroll) {
      var card = cards[idx];
      var scrollPos = card.offsetLeft - (bdgsownv2Track.clientWidth - card.offsetWidth) / 2;
      bdgsownv2Track.scrollTo({ left: Math.max(0, scrollPos), behavior: 'smooth' });
    }
    resetBdgsownv2Progress();
    if (!bdgsownv2Paused) startBdgsownv2Progress();
  }

  function syncBdgsownv2FromScroll() {
    var idx = getBdgsownv2CenteredIndex();
    if (idx === bdgsownv2CurrentIndex) return;
    bdgsownv2CurrentIndex = idx;
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    cards.forEach(function(card, i) {
      card.classList.toggle('active', i === idx);
    });
    resetBdgsownv2Progress();
    if (!bdgsownv2Paused) startBdgsownv2Progress();
  }

  function snapBdgsownv2ToCenter(behavior) {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    if (!cards.length) return;
    var idx = getBdgsownv2CenteredIndex();
    bdgsownv2CurrentIndex = idx;
    cards.forEach(function(card, i) {
      card.classList.toggle('active', i === idx);
    });
    var card = cards[idx];
    var scrollPos = card.offsetLeft - (bdgsownv2Track.clientWidth - card.offsetWidth) / 2;
    bdgsownv2Track.scrollTo({
      left: Math.max(0, scrollPos),
      behavior: behavior || 'smooth'
    });
    resetBdgsownv2Progress();
    if (!bdgsownv2Paused) startBdgsownv2Progress();
  }

  function updateBdgsownv2Carousel(isInit) {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    if (!cards.length) return;
    cards.forEach(function(card, i) {
      card.classList.toggle('active', i === bdgsownv2CurrentIndex);
    });
    var card = cards[bdgsownv2CurrentIndex];
    if (card) {
      var scrollPos = card.offsetLeft - (bdgsownv2Track.clientWidth - card.offsetWidth) / 2;
      bdgsownv2Track.scrollTo({ left: Math.max(0, scrollPos), behavior: isInit ? 'auto' : 'smooth' });
    }
    resetBdgsownv2Progress();
    if (!bdgsownv2Paused) startBdgsownv2Progress();
  }

  function bdgsownv2Next() {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    if (!cards.length) return;
    bdgsownv2CurrentIndex = (bdgsownv2CurrentIndex + 1) % cards.length;
    updateBdgsownv2Carousel();
  }

  function bdgsownv2Prev() {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    if (!cards.length) return;
    bdgsownv2CurrentIndex = (bdgsownv2CurrentIndex - 1 + cards.length) % cards.length;
    updateBdgsownv2Carousel();
  }

  function startBdgsownv2AutoScroll() {
    clearInterval(bdgsownv2Timer);
    bdgsownv2Timer = setInterval(function() {
      if (!bdgsownv2Paused) bdgsownv2Next();
    }, BDGSOWNV2_INTERVAL_MS);
    startBdgsownv2Progress();
  }

  function bindBdgsownv2Events() {
    var nextBtn = document.getElementById('bdgsownv2-next');
    var prevBtn = document.getElementById('bdgsownv2-prev');
    if (nextBtn) {
      nextBtn.addEventListener('click', function() {
        bdgsownv2Next();
        startBdgsownv2AutoScroll();
      });
    }
    if (prevBtn) {
      prevBtn.addEventListener('click', function() {
        bdgsownv2Prev();
        startBdgsownv2AutoScroll();
      });
    }

    var v2Wrapper = document.querySelector('.bdgsownv2-carousel-wrapper');
    if (v2Wrapper) {
      v2Wrapper.addEventListener('mouseenter', function() {
        bdgsownv2Paused = true;
        if (bdgsownv2ProgressFill) {
          var computed = getComputedStyle(bdgsownv2ProgressFill).width;
          var parent = bdgsownv2ProgressFill.parentElement.offsetWidth;
          var pct = (parseFloat(computed) / parent * 100).toFixed(2);
          bdgsownv2ProgressFill.style.transition = 'none';
          bdgsownv2ProgressFill.style.width = pct + '%';
        }
      });
      v2Wrapper.addEventListener('mouseleave', function() {
        bdgsownv2Paused = false;
        if (bdgsownv2ProgressFill) {
          var computed = getComputedStyle(bdgsownv2ProgressFill).width;
          var parent = bdgsownv2ProgressFill.parentElement.offsetWidth;
          var currentPct = parseFloat(computed) / parent;
          var remaining = BDGSOWNV2_INTERVAL_MS * (1 - currentPct);
          bdgsownv2ProgressFill.style.transition = 'width ' + remaining + 'ms linear';
          bdgsownv2ProgressFill.style.width = '100%';
        }
      });
    }

    if (bdgsownv2Track && !bdgsownv2Track.dataset.bdgsScrollBound) {
      bdgsownv2Track.dataset.bdgsScrollBound = '1';
      var scrollSyncTimer = null;
      var touchScrolling = false;

      bdgsownv2Track.addEventListener('scroll', function() {
        clearTimeout(scrollSyncTimer);
        scrollSyncTimer = setTimeout(function() {
          syncBdgsownv2FromScroll();
        }, 60);
      }, { passive: true });

      if ('onscrollend' in window) {
        bdgsownv2Track.addEventListener('scrollend', function() {
          snapBdgsownv2ToCenter('smooth');
        });
      }

      bdgsownv2Track.addEventListener('touchstart', function() {
        touchScrolling = true;
        bdgsownv2Paused = true;
        if (bdgsownv2ProgressFill) {
          var computed = getComputedStyle(bdgsownv2ProgressFill).width;
          var parent = bdgsownv2ProgressFill.parentElement.offsetWidth;
          var pct = (parseFloat(computed) / parent * 100).toFixed(2);
          bdgsownv2ProgressFill.style.transition = 'none';
          bdgsownv2ProgressFill.style.width = pct + '%';
        }
      }, { passive: true });

      bdgsownv2Track.addEventListener('touchend', function() {
        touchScrolling = false;
        setTimeout(function() {
          snapBdgsownv2ToCenter('smooth');
          bdgsownv2Paused = false;
          startBdgsownv2AutoScroll();
        }, 150);
      }, { passive: true });
    }

    // Allow clicking on cards to navigate
    var trackCards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    trackCards.forEach(function(card, idx) {
      if (card.dataset.bdgsClickBound) return;
      card.dataset.bdgsClickBound = '1';
      card.addEventListener('click', function() {
        if (idx === bdgsownv2CurrentIndex) return;
        setBdgsownv2ActiveIndex(idx, false);
        startBdgsownv2AutoScroll();
      });
    });
  }

  if (bdgsownv2Track) {
    bindBdgsownv2Events();
    setTimeout(function() { updateBdgsownv2Carousel(true); }, 0);
  }

  resolveCarouselData()
    .then(function(data) {
      if (!data.ok) {
        throw new Error('API ok was false');
      }
      if (data.reviews && data.reviews.length > 0 && bdgsownv2Track) {
        var targetIds = ['910', '912', '862', '928', '932', '881', '864', '848', '742', '838', '827'];
        var filteredReviews = data.reviews.filter(function(r) {
          return targetIds.indexOf(String(r.marketplace_review_id)) !== -1 || targetIds.indexOf(String(r.review_id)) !== -1;
        });
        
        fetchedReviews = filteredReviews.length > 0 ? filteredReviews : data.reviews;
        bdgsownv2Track.innerHTML = '';
        var repeatedReviews = [];
        for (var i = 0; i < 10; i++) {
          repeatedReviews = repeatedReviews.concat(fetchedReviews);
        }
        bdgsownv2CurrentIndex = Math.floor(50 / 2);
        bdgsownv2CurrentIndex = bdgsownv2CurrentIndex - (bdgsownv2CurrentIndex % 5);
        repeatedReviews.forEach(function(review, idx) {
          var card = document.createElement('div');
          card.className = 'bdgsownv2-card rev-v3 rev-v3--shimmer-d' + (idx === bdgsownv2CurrentIndex ? ' active' : '');
          card.setAttribute('data-bdgsownv2-index', idx);
          card.innerHTML = '<div class="rev-v3__inner">' + createBdgsownv2CardHTML(review) + '</div>';
          bdgsownv2Track.appendChild(card);
        });
        bindBdgsownv2Events();
        setTimeout(function() {
          updateBdgsownv2Carousel(true);
          startBdgsownv2AutoScroll();
        }, 50);
      } else {
        throw new Error('No reviews returned');
      }
    })
    .catch(function(err) {
      console.error('Error loading reviews, using fallback array:', err);
      // Fallback: Populate track with FALLBACK_REVIEWS
      if (bdgsownv2Track) {
        bdgsownv2Track.innerHTML = '';
        var repeatedReviewsFallback = [];
        for (var i = 0; i < 10; i++) {
          repeatedReviewsFallback = repeatedReviewsFallback.concat(FALLBACK_REVIEWS);
        }
        bdgsownv2CurrentIndex = Math.floor(50 / 2);
        bdgsownv2CurrentIndex = bdgsownv2CurrentIndex - (bdgsownv2CurrentIndex % 5);
        repeatedReviewsFallback.forEach(function(review, idx) {
          var card = document.createElement('div');
          card.className = 'bdgsownv2-card rev-v3 rev-v3--shimmer-d' + (idx === bdgsownv2CurrentIndex ? ' active' : '');
          card.setAttribute('data-bdgsownv2-index', idx);
          card.innerHTML = '<div class="rev-v3__inner">' + createBdgsownv2CardHTML(review) + '</div>';
          bdgsownv2Track.appendChild(card);
        });
        bindBdgsownv2Events();
        setTimeout(function() {
          updateBdgsownv2Carousel(true);
          startBdgsownv2AutoScroll();
        }, 50);
      }
    });
})();
</script>
