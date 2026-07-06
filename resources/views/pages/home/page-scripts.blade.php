<script>
// Modal — Zoom Clinics
// Canonical clinic time: 6:30–7:30 PM Asia/Kolkata (Tue/Thu), shown in visitor's selected timezone
const BDGS_ZOOM_CANONICAL_TZ = 'Asia/Kolkata';

function bdgsZoomPartsInZone(instant, tz) {
  const dtf = new Intl.DateTimeFormat('en-US', {
    timeZone: tz,
    year: 'numeric', month: '2-digit', day: '2-digit',
    hour: '2-digit', minute: '2-digit', second: '2-digit',
    hour12: false, weekday: 'short'
  });
  const map = {};
  dtf.formatToParts(instant).forEach(p => {
    if (p.type !== 'literal') map[p.type] = p.value;
  });
  return map;
}

function bdgsZoomInstantAtLocalTime(y, mo, d, h, mi, tz) {
  let utcGuess = Date.UTC(y, mo - 1, d, h, mi, 0);
  const target = Date.UTC(y, mo - 1, d, h, mi, 0);
  for (let n = 0; n < 3; n++) {
    const p = bdgsZoomPartsInZone(new Date(utcGuess), tz);
    const asUtc = Date.UTC(+p.year, +p.month - 1, +p.day, +p.hour, +p.minute, +(p.second || 0));
    utcGuess += target - asUtc;
  }
  return new Date(utcGuess);
}

function bdgsZoomNextTueOrThuYMD() {
  const now = new Date();
  for (let i = 0; i < 14; i++) {
    const probe = new Date(now.getTime() + i * 86400000);
    const p = bdgsZoomPartsInZone(probe, BDGS_ZOOM_CANONICAL_TZ);
    if (p.weekday === 'Tue' || p.weekday === 'Thu') {
      return { y: +p.year, mo: +p.month, d: +p.day };
    }
  }
  return { y: 2026, mo: 7, d: 3 };
}

function bdgsGetNextZoomSession() {
  const ymd = bdgsZoomNextTueOrThuYMD();
  return {
    start: bdgsZoomInstantAtLocalTime(ymd.y, ymd.mo, ymd.d, 18, 30, BDGS_ZOOM_CANONICAL_TZ),
    end: bdgsZoomInstantAtLocalTime(ymd.y, ymd.mo, ymd.d, 19, 30, BDGS_ZOOM_CANONICAL_TZ)
  };
}

const _bdgsZoomSession = bdgsGetNextZoomSession();
const ZOOM_SESSION_DB_DATE = _bdgsZoomSession.start;
const ZOOM_SESSION_END_DATE = _bdgsZoomSession.end;

function bdgsFormatZoomSessionDisplay(tz) {
  const timeFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  const timeEndFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZoneName: 'short', timeZone: tz }).format(ZOOM_SESSION_END_DATE);
  const dateFmt = new Intl.DateTimeFormat('en-US', { weekday: 'short', month: 'short', day: 'numeric', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  return `${dateFmt} - ${timeFmt} to ${timeEndFmt}`;
}

function bdgsGetSelectedZoomTimezone() {
  const formInput = document.getElementById('bdgsZoomTimezoneForm');
  const mainInput = document.getElementById('bdgsZoomTimezone');
  return (formInput && formInput.value) || (mainInput && mainInput.value) || 'America/New_York';
}

function bdgsFormatGcalDateTime(date, timeZone) {
  const parts = new Intl.DateTimeFormat('en-US', {
    timeZone,
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  }).formatToParts(date);
  const get = (type) => parts.find(p => p.type === type).value;
  let hour = parseInt(get('hour'), 10);
  if (hour === 24) hour = 0;
  const pad = (n) => String(n).padStart(2, '0');
  return `${get('year')}${get('month')}${get('day')}T${pad(hour)}${get('minute')}${get('second')}`;
}

function bdgsUpdateGoogleCalLink() {
  const tz = bdgsGetSelectedZoomTimezone();
  const startStr = bdgsFormatGcalDateTime(ZOOM_SESSION_DB_DATE, tz);
  const endStr = bdgsFormatGcalDateTime(ZOOM_SESSION_END_DATE, tz);
  const title = encodeURIComponent('Zoom Clinic: Live Website Reviews & Open Q&A');
  const details = encodeURIComponent('Join us for a free live Zoom session to get help and learn Brilliant Directories.\n\nFormat: 60-min open Q&A with our devs');
  const gcalUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${startStr}/${endStr}&details=${details}&ctz=${encodeURIComponent(tz)}`;
  const gcalBtn = document.getElementById('bdgsGoogleCalLink');
  if (gcalBtn) gcalBtn.href = gcalUrl;
}

function bdgsUpdateZoomSuccessDetails() {
  const tz = bdgsGetSelectedZoomTimezone();
  const tzTextEl = document.getElementById('bdgsZoomTimezoneTextForm') || document.getElementById('bdgsZoomTimezoneText');
  const tzLabel = tzTextEl ? tzTextEl.innerText : tz;
  const whenEl = document.getElementById('bdgsZoomSuccessWhen');
  const tzEl = document.getElementById('bdgsZoomSuccessTz');
  if (whenEl) {
    try {
      whenEl.textContent = bdgsFormatZoomSessionDisplay(tz);
    } catch (e) {
      whenEl.textContent = '';
    }
  }
  if (tzEl) tzEl.textContent = tzLabel;
  bdgsUpdateGoogleCalLink();
}

function bdgsInitTimezoneDropdown() {
  bdgsUpdateGoogleCalLink();

  document.querySelectorAll('.bdgs-tz-option').forEach(opt => {
    const tz = opt.getAttribute('data-value');
    const base = opt.getAttribute('data-base');
    if(base) {
      try {
        const tzShort = new Intl.DateTimeFormat('en-US', { timeZone: tz, timeZoneName: 'short' })
          .formatToParts(ZOOM_SESSION_DB_DATE)
          .find(p => p.type === 'timeZoneName').value;
        opt.innerText = base.replace('{tz}', tzShort);
      } catch(e) {
        opt.innerText = base.replace(' ({tz})', '');
      }
    }
  });
  const selectedOpt = document.querySelector('.bdgs-tz-option.selected');
  if(selectedOpt) {
    ['', 'Form'].forEach(sfx => {
      const textSpan = document.getElementById('bdgsZoomTimezoneText' + sfx);
      if(textSpan) textSpan.innerText = selectedOpt.innerText;
    });
  }
}
// Run init on load
if(document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bdgsInitTimezoneDropdown);
} else {
  bdgsInitTimezoneDropdown();
}


function bdgsZoomAutoFill() {
  // Logic to auto-fill logged-in user data
  const loggedInUser = null; // placeholder for user object
  if(loggedInUser) {
    document.getElementById('bdgsZoomName').value = loggedInUser.name || '';
    document.getElementById('bdgsZoomEmail').value = loggedInUser.email || '';
  }
}

function bdgsUpdateTimezoneDisplay() {
  const tz = document.getElementById('bdgsZoomTimezone').value;
  ['', 'Form'].forEach(sfx => {
    const display = document.getElementById('bdgsZoomSessionDisplay' + sfx);
    if (display) {
      try {
        display.innerText = bdgsFormatZoomSessionDisplay(tz);
      } catch (e) {
        display.innerText = '6:30–7:30 PM IST';
      }
    }
  });
}

function bdgsShowZoomForm() {
  document.getElementById('bdgsZoomModalStep1').style.display = 'none';
  document.getElementById('bdgsZoomBookingForm').style.display = 'block';
}

function bdgsHideZoomForm() {
  document.getElementById('bdgsZoomBookingForm').style.display = 'none';
  document.getElementById('bdgsZoomModalStep1').style.display = 'block';
}

function bdgsOpenZoomModal() {
  bdgsZoomAutoFill();
  bdgsUpdateTimezoneDisplay(); // Init timezone display on open
  document.getElementById('bdgsZoomModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}
function bdgsCloseZoomModal() {
  document.getElementById('bdgsZoomModal').classList.remove('active');
  document.body.style.overflow = '';
  // Reset the modal content smoothly
  setTimeout(() => {
    const content = document.getElementById('bdgsZoomModalContent');
    const success = document.getElementById('bdgsZoomModalSuccess');
    if (content && success) {
      content.style.display = 'block';
      content.style.opacity = '1';
      content.style.transform = 'none';
      success.style.display = 'none';
      document.getElementById('bdgsZoomModalStep1').style.display = 'block';
      document.getElementById('bdgsZoomBookingForm').style.display = 'none';
      document.getElementById('bdgsZoomBookingForm').reset();
      bdgsUpdateTimezoneDisplay();
    }
  }, 300);
}

function bdgsProcessZoomBooking() {
  const btn = document.getElementById('bdgsZoomScheduleBtn');
  const originalText = btn.innerHTML;
  btn.innerHTML = '<span style="opacity:0.8; letter-spacing: 0.5px;">Scheduling...</span>';
  btn.disabled = true;
  
  setTimeout(() => {
    const content = document.getElementById('bdgsZoomModalContent');
    const success = document.getElementById('bdgsZoomModalSuccess');

    bdgsUpdateZoomSuccessDetails();
    
    // Fade out form
    content.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
    content.style.opacity = '0';
    content.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
      content.style.display = 'none';
      
      // Prepare and fade in success message with slight pop
      success.style.display = 'block';
      success.style.opacity = '0';
      success.style.transform = 'scale(0.92)';
      success.style.transition = 'opacity 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275), transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
      
      // Trigger reflow
      void success.offsetWidth;
      
      success.style.opacity = '1';
      success.style.transform = 'scale(1)';
      
      setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
      }, 500);
    }, 250);
  }, 1200);
}
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
    bdgsCloseZoomModal();
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
<script src="/snippets/bdgs-review-count.js"></script>
<script>
(function() {
  var API = 'https://bdgrowthsuite.com/api/widget/json/get/bdgs-blabs-reviews-api';
  var KEY = '3d5ca826459ec3a3b47675f7ea0a0cd3';
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
      review_text: 'We have recently implemented the Google Review Plugin, and the experience has been nothing short of impressive. The team from Business Labs impressed us with their quick response. Every time we had a question, we got an answer right away – always polite and professional. We recommend them.',
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

  function anonymizeReviewText(text) {
    if (!text) return '';
    var clean = text
      .replace(/arendal\.net/gi, 'our directory website')
      .replace(/ConnectCare\.ie/gi, 'our directory website')
      .replace(/uppercervicalcare\.com/gi, 'our directory website')
      .replace(/RealEstatePhotography\.com/gi, 'our directory website')
      .replace(/petrolheadlife\.com/gi, 'our directory website');
    clean = clean.replace(/\b([a-z0-9]+(-[a-z0-9]+)*\.)+(com|net|org|ie|co|io|edu|gov|co\.uk)\b/gi, 'our directory website');
    return clean;
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
    var verifyLink = review.marketplace_review_id || review.review_id ? 'https://marketplace.brilliantdirectories.com/india/partner/business-labs/reviews/' + (review.marketplace_review_id || review.review_id) : 'https://marketplace.brilliantdirectories.com/india/partner/business-labs';

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

  function updateBdgsownv2Carousel(isInit) {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    cards.forEach(function(card, idx) {
      if (idx === bdgsownv2CurrentIndex) {
        card.classList.add('active');
        var scrollPos = card.offsetLeft - bdgsownv2Track.offsetLeft - (bdgsownv2Track.clientWidth - card.clientWidth) / 2;
        bdgsownv2Track.scrollTo({ left: scrollPos, behavior: isInit ? 'auto' : 'smooth' });
      } else {
        card.classList.remove('active');
      }
    });
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

    // Allow clicking on cards to navigate
    var trackCards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    trackCards.forEach(function(card, idx) {
      card.addEventListener('click', function() {
        bdgsownv2CurrentIndex = idx;
        updateBdgsownv2Carousel();
        startBdgsownv2AutoScroll();
      });
    });
  }

  // Fetch count and reviews from the API
  fetch(API + '?action=list&published_only=1&limit=200&offset=0&bd_api_key=' + KEY)
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (!data.ok) {
        throw new Error('API ok was false');
      }
      
      // Update exact review count (no "+") — other stats stay static
      if (data.total) {
        if (window.BDGS_REVIEW_COUNT) BDGS_REVIEW_COUNT.sync(data.total);
      }

      // Update carousel with dynamic fresh reviews
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

function bdgsSelectTimezone(el, event) {
  event.stopPropagation();
  const value = el.getAttribute('data-value');
  const text = el.innerText;
  
  // Update both hidden inputs and display texts
  ['', 'Form'].forEach(sfx => {
    const input = document.getElementById('bdgsZoomTimezone' + sfx);
    const textSpan = document.getElementById('bdgsZoomTimezoneText' + sfx);
    if(input) input.value = value;
    if(textSpan) textSpan.innerText = text;
    
    // Update selected class for both dropdowns
    const options = document.querySelectorAll('#bdgsTzDropdown' + sfx + ' .bdgs-tz-option');
    options.forEach(opt => opt.classList.remove('selected'));
    const matchingOpt = document.querySelector('#bdgsTzDropdown' + sfx + ' .bdgs-tz-option[data-value="'+value+'"]');
    if(matchingOpt) matchingOpt.classList.add('selected');
    
    const dropdown = document.getElementById('bdgsTzDropdown' + sfx);
    if(dropdown) dropdown.classList.remove('show');
  });
  
  bdgsUpdateTimezoneDisplay();
  bdgsUpdateGoogleCalLink();
}

document.addEventListener('click', function(e) {
  ['', 'Form'].forEach(sfx => {
    const wrapper = document.getElementById('bdgsTzWrapper' + sfx);
    const dropdown = document.getElementById('bdgsTzDropdown' + sfx);
    if (wrapper && dropdown && !wrapper.contains(e.target)) {
      dropdown.classList.remove('show');
    }
  });
});
</script>


<script>
// Modal — Zoom Clinics
// Canonical clinic time: 6:30–7:30 PM Asia/Kolkata (Tue/Thu), shown in visitor's selected timezone
const BDGS_ZOOM_CANONICAL_TZ = 'Asia/Kolkata';

function bdgsZoomPartsInZone(instant, tz) {
  const dtf = new Intl.DateTimeFormat('en-US', {
    timeZone: tz,
    year: 'numeric', month: '2-digit', day: '2-digit',
    hour: '2-digit', minute: '2-digit', second: '2-digit',
    hour12: false, weekday: 'short'
  });
  const map = {};
  dtf.formatToParts(instant).forEach(p => {
    if (p.type !== 'literal') map[p.type] = p.value;
  });
  return map;
}

function bdgsZoomInstantAtLocalTime(y, mo, d, h, mi, tz) {
  let utcGuess = Date.UTC(y, mo - 1, d, h, mi, 0);
  const target = Date.UTC(y, mo - 1, d, h, mi, 0);
  for (let n = 0; n < 3; n++) {
    const p = bdgsZoomPartsInZone(new Date(utcGuess), tz);
    const asUtc = Date.UTC(+p.year, +p.month - 1, +p.day, +p.hour, +p.minute, +(p.second || 0));
    utcGuess += target - asUtc;
  }
  return new Date(utcGuess);
}

function bdgsZoomNextTueOrThuYMD() {
  const now = new Date();
  for (let i = 0; i < 14; i++) {
    const probe = new Date(now.getTime() + i * 86400000);
    const p = bdgsZoomPartsInZone(probe, BDGS_ZOOM_CANONICAL_TZ);
    if (p.weekday === 'Tue' || p.weekday === 'Thu') {
      return { y: +p.year, mo: +p.month, d: +p.day };
    }
  }
  return { y: 2026, mo: 7, d: 3 };
}

function bdgsGetNextZoomSession() {
  const ymd = bdgsZoomNextTueOrThuYMD();
  return {
    start: bdgsZoomInstantAtLocalTime(ymd.y, ymd.mo, ymd.d, 18, 30, BDGS_ZOOM_CANONICAL_TZ),
    end: bdgsZoomInstantAtLocalTime(ymd.y, ymd.mo, ymd.d, 19, 30, BDGS_ZOOM_CANONICAL_TZ)
  };
}

const _bdgsZoomSession = bdgsGetNextZoomSession();
const ZOOM_SESSION_DB_DATE = _bdgsZoomSession.start;
const ZOOM_SESSION_END_DATE = _bdgsZoomSession.end;

function bdgsFormatZoomSessionDisplay(tz) {
  const timeFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  const timeEndFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZoneName: 'short', timeZone: tz }).format(ZOOM_SESSION_END_DATE);
  const dateFmt = new Intl.DateTimeFormat('en-US', { weekday: 'short', month: 'short', day: 'numeric', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  return `${dateFmt} - ${timeFmt} to ${timeEndFmt}`;
}

function bdgsGetSelectedZoomTimezone() {
  const formInput = document.getElementById('bdgsZoomTimezoneForm');
  const mainInput = document.getElementById('bdgsZoomTimezone');
  return (formInput && formInput.value) || (mainInput && mainInput.value) || 'America/New_York';
}

function bdgsFormatGcalDateTime(date, timeZone) {
  const parts = new Intl.DateTimeFormat('en-US', {
    timeZone,
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  }).formatToParts(date);
  const get = (type) => parts.find(p => p.type === type).value;
  let hour = parseInt(get('hour'), 10);
  if (hour === 24) hour = 0;
  const pad = (n) => String(n).padStart(2, '0');
  return `${get('year')}${get('month')}${get('day')}T${pad(hour)}${get('minute')}${get('second')}`;
}

function bdgsUpdateGoogleCalLink() {
  const tz = bdgsGetSelectedZoomTimezone();
  const startStr = bdgsFormatGcalDateTime(ZOOM_SESSION_DB_DATE, tz);
  const endStr = bdgsFormatGcalDateTime(ZOOM_SESSION_END_DATE, tz);
  const title = encodeURIComponent('Zoom Clinic: Live Website Reviews & Open Q&A');
  const details = encodeURIComponent('Join us for a free live Zoom session to get help and learn Brilliant Directories.\n\nFormat: 60-min open Q&A with our devs');
  const gcalUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${startStr}/${endStr}&details=${details}&ctz=${encodeURIComponent(tz)}`;
  const gcalBtn = document.getElementById('bdgsGoogleCalLink');
  if (gcalBtn) gcalBtn.href = gcalUrl;
}

function bdgsUpdateZoomSuccessDetails() {
  const tz = bdgsGetSelectedZoomTimezone();
  const tzTextEl = document.getElementById('bdgsZoomTimezoneTextForm') || document.getElementById('bdgsZoomTimezoneText');
  const tzLabel = tzTextEl ? tzTextEl.innerText : tz;
  const whenEl = document.getElementById('bdgsZoomSuccessWhen');
  const tzEl = document.getElementById('bdgsZoomSuccessTz');
  if (whenEl) {
    try {
      whenEl.textContent = bdgsFormatZoomSessionDisplay(tz);
    } catch (e) {
      whenEl.textContent = '';
    }
  }
  if (tzEl) tzEl.textContent = tzLabel;
  bdgsUpdateGoogleCalLink();
}

function bdgsInitTimezoneDropdown() {
  bdgsUpdateGoogleCalLink();

  document.querySelectorAll('.bdgs-tz-option').forEach(opt => {
    const tz = opt.getAttribute('data-value');
    const base = opt.getAttribute('data-base');
    if(base) {
      try {
        const tzShort = new Intl.DateTimeFormat('en-US', { timeZone: tz, timeZoneName: 'short' })
          .formatToParts(ZOOM_SESSION_DB_DATE)
          .find(p => p.type === 'timeZoneName').value;
        opt.innerText = base.replace('{tz}', tzShort);
      } catch(e) {
        opt.innerText = base.replace(' ({tz})', '');
      }
    }
  });
  const selectedOpt = document.querySelector('.bdgs-tz-option.selected');
  if(selectedOpt) {
    ['', 'Form'].forEach(sfx => {
      const textSpan = document.getElementById('bdgsZoomTimezoneText' + sfx);
      if(textSpan) textSpan.innerText = selectedOpt.innerText;
    });
  }
}
// Run init on load
if(document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bdgsInitTimezoneDropdown);
} else {
  bdgsInitTimezoneDropdown();
}


function bdgsZoomAutoFill() {
  // Logic to auto-fill logged-in user data
  const loggedInUser = null; // placeholder for user object
  if(loggedInUser) {
    document.getElementById('bdgsZoomName').value = loggedInUser.name || '';
    document.getElementById('bdgsZoomEmail').value = loggedInUser.email || '';
  }
}

function bdgsUpdateTimezoneDisplay() {
  const tz = document.getElementById('bdgsZoomTimezone').value;
  ['', 'Form'].forEach(sfx => {
    const display = document.getElementById('bdgsZoomSessionDisplay' + sfx);
    if (display) {
      try {
        display.innerText = bdgsFormatZoomSessionDisplay(tz);
      } catch (e) {
        display.innerText = '6:30–7:30 PM IST';
      }
    }
  });
}

function bdgsShowZoomForm() {
  document.getElementById('bdgsZoomModalStep1').style.display = 'none';
  document.getElementById('bdgsZoomBookingForm').style.display = 'block';
}

function bdgsHideZoomForm() {
  document.getElementById('bdgsZoomBookingForm').style.display = 'none';
  document.getElementById('bdgsZoomModalStep1').style.display = 'block';
}

function bdgsOpenZoomModal() {
  bdgsZoomAutoFill();
  bdgsUpdateTimezoneDisplay(); // Init timezone display on open
  document.getElementById('bdgsZoomModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}
function bdgsCloseZoomModal() {
  document.getElementById('bdgsZoomModal').classList.remove('active');
  document.body.style.overflow = '';
  // Reset the modal content smoothly
  setTimeout(() => {
    const content = document.getElementById('bdgsZoomModalContent');
    const success = document.getElementById('bdgsZoomModalSuccess');
    if (content && success) {
      content.style.display = 'block';
      content.style.opacity = '1';
      content.style.transform = 'none';
      success.style.display = 'none';
      document.getElementById('bdgsZoomModalStep1').style.display = 'block';
      document.getElementById('bdgsZoomBookingForm').style.display = 'none';
      document.getElementById('bdgsZoomBookingForm').reset();
      bdgsUpdateTimezoneDisplay();
    }
  }, 300);
}

function bdgsProcessZoomBooking() {
  const btn = document.getElementById('bdgsZoomScheduleBtn');
  const originalText = btn.innerHTML;
  btn.innerHTML = '<span style="opacity:0.8; letter-spacing: 0.5px;">Scheduling...</span>';
  btn.disabled = true;
  
  setTimeout(() => {
    const content = document.getElementById('bdgsZoomModalContent');
    const success = document.getElementById('bdgsZoomModalSuccess');

    bdgsUpdateZoomSuccessDetails();
    
    // Fade out form
    content.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
    content.style.opacity = '0';
    content.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
      content.style.display = 'none';
      
      // Prepare and fade in success message with slight pop
      success.style.display = 'block';
      success.style.opacity = '0';
      success.style.transform = 'scale(0.92)';
      success.style.transition = 'opacity 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275), transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
      
      // Trigger reflow
      void success.offsetWidth;
      
      success.style.opacity = '1';
      success.style.transform = 'scale(1)';
      
      setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
      }, 500);
    }, 250);
  }, 1200);
}
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
    bdgsCloseZoomModal();
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
<script src="/snippets/bdgs-review-count.js"></script>
<script>
(function() {
  var API = 'https://bdgrowthsuite.com/api/widget/json/get/bdgs-blabs-reviews-api';
  var KEY = '3d5ca826459ec3a3b47675f7ea0a0cd3';
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
      review_text: 'We have recently implemented the Google Review Plugin, and the experience has been nothing short of impressive. The team from Business Labs impressed us with their quick response. Every time we had a question, we got an answer right away – always polite and professional. We recommend them.',
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

  function anonymizeReviewText(text) {
    if (!text) return '';
    var clean = text
      .replace(/arendal\.net/gi, 'our directory website')
      .replace(/ConnectCare\.ie/gi, 'our directory website')
      .replace(/uppercervicalcare\.com/gi, 'our directory website')
      .replace(/RealEstatePhotography\.com/gi, 'our directory website')
      .replace(/petrolheadlife\.com/gi, 'our directory website');
    clean = clean.replace(/\b([a-z0-9]+(-[a-z0-9]+)*\.)+(com|net|org|ie|co|io|edu|gov|co\.uk)\b/gi, 'our directory website');
    return clean;
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
    var verifyLink = review.marketplace_review_id || review.review_id ? 'https://marketplace.brilliantdirectories.com/india/partner/business-labs/reviews/' + (review.marketplace_review_id || review.review_id) : 'https://marketplace.brilliantdirectories.com/india/partner/business-labs';

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

  function updateBdgsownv2Carousel(isInit) {
    var cards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    cards.forEach(function(card, idx) {
      if (idx === bdgsownv2CurrentIndex) {
        card.classList.add('active');
        var scrollPos = card.offsetLeft - bdgsownv2Track.offsetLeft - (bdgsownv2Track.clientWidth - card.clientWidth) / 2;
        bdgsownv2Track.scrollTo({ left: scrollPos, behavior: isInit ? 'auto' : 'smooth' });
      } else {
        card.classList.remove('active');
      }
    });
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

    // Allow clicking on cards to navigate
    var trackCards = bdgsownv2Track.querySelectorAll('.bdgsownv2-card');
    trackCards.forEach(function(card, idx) {
      card.addEventListener('click', function() {
        bdgsownv2CurrentIndex = idx;
        updateBdgsownv2Carousel();
        startBdgsownv2AutoScroll();
      });
    });
  }

  // Fetch count and reviews from the API
  fetch(API + '?action=list&published_only=1&limit=200&offset=0&bd_api_key=' + KEY)
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (!data.ok) {
        throw new Error('API ok was false');
      }
      
      // Update exact review count (no "+") — other stats stay static
      if (data.total) {
        if (window.BDGS_REVIEW_COUNT) BDGS_REVIEW_COUNT.sync(data.total);
      }

      // Update carousel with dynamic fresh reviews
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

function bdgsSelectTimezone(el, event) {
  event.stopPropagation();
  const value = el.getAttribute('data-value');
  const text = el.innerText;
  
  // Update both hidden inputs and display texts
  ['', 'Form'].forEach(sfx => {
    const input = document.getElementById('bdgsZoomTimezone' + sfx);
    const textSpan = document.getElementById('bdgsZoomTimezoneText' + sfx);
    if(input) input.value = value;
    if(textSpan) textSpan.innerText = text;
    
    // Update selected class for both dropdowns
    const options = document.querySelectorAll('#bdgsTzDropdown' + sfx + ' .bdgs-tz-option');
    options.forEach(opt => opt.classList.remove('selected'));
    const matchingOpt = document.querySelector('#bdgsTzDropdown' + sfx + ' .bdgs-tz-option[data-value="'+value+'"]');
    if(matchingOpt) matchingOpt.classList.add('selected');
    
    const dropdown = document.getElementById('bdgsTzDropdown' + sfx);
    if(dropdown) dropdown.classList.remove('show');
  });
  
  bdgsUpdateTimezoneDisplay();
  bdgsUpdateGoogleCalLink();
}

document.addEventListener('click', function(e) {
  ['', 'Form'].forEach(sfx => {
    const wrapper = document.getElementById('bdgsTzWrapper' + sfx);
    const dropdown = document.getElementById('bdgsTzDropdown' + sfx);
    if (wrapper && dropdown && !wrapper.contains(e.target)) {
      dropdown.classList.remove('show');
    }
  });
});
</script>

<div class="bdgs-mob-overlay" id="bdgsMobOverlay" aria-hidden="true">
  <div class="bdgs-mob-header">
    <a href="/" class="bdgsownv2-logo">
      <img referrerpolicy="no-referrer" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png" alt="BD Growth Suite" height="32">
    </a>
    <button type="button" class="bdgs-mob-close" id="bdgsMobCloseBtn" aria-label="Close menu">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
  </div>
  <div class="bdgs-mob-panels" id="bdgsMobPanels">
    <div class="bdgs-mob-panel bdgs-mob-panel--active" data-panel="main">
      <div class="bdgs-mob-panel-scroll">
        <ul class="bdgs-mob-list">
          <li class="bdgs-mob-item bdgs-mob-item--drillable" data-target="services">
            <span class="bdgs-mob-item-label">Services</span>
            <svg class="bdgs-mob-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </li>
          <li class="bdgs-mob-item bdgs-mob-item--drillable" data-target="grow-ai">
            <span class="bdgs-mob-item-label">Grow with AI</span>
            <svg class="bdgs-mob-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </li>
          <li class="bdgs-mob-item">
            <a href="#svc-dev" class="bdgs-mob-item-link">Hire a Developer</a>
          </li>
          <li class="bdgs-mob-item bdgs-mob-item--drillable" data-target="tools">
            <span class="bdgs-mob-item-label">Tools</span>
            <svg class="bdgs-mob-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </li>
          <li class="bdgs-mob-item">
            <a href="/themes" class="bdgs-mob-item-link">Themes</a>
          </li>
          <li class="bdgs-mob-item bdgs-mob-item--pill">
            <a href="/zoom-clinics" class="bdgs-mob-zoom"><span class="pulse"></span> Zoom Clinics</a>
          </li>
        </ul>
        <div class="bdgs-mob-cta-wrap">
          <button type="button" class="bdgs-mob-cta">Get Started →</button>
        </div>
      </div>
    </div>
    <div class="bdgs-mob-panel" data-panel="services">
      <button type="button" class="bdgs-mob-back" data-back="main">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span>Services</span>
      </button>
      <div class="bdgs-mob-panel-scroll">
        <div class="bdgs-mob-section">
          <p class="bdgs-mob-section-heading">Services</p>
          <a href="/setup" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Setup &amp; Launch</span><span class="bdgs-mob-subitem-desc">Concierge directory launch. Business-first approach.</span></a>
          <a href="/hire-developer" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Hire a Developer</span><span class="bdgs-mob-subitem-desc">AI-savvy directory devs. Long-term permanent team.</span></a>
          <a href="/customization" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Custom Projects</span><span class="bdgs-mob-subitem-desc">One-off scoped builds. Integrations and widgets.</span></a>
          <a href="/maintenance" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Maintenance Plans</span><span class="bdgs-mob-subitem-desc">Ongoing support, backups, updates, optimization.</span></a>
          <a href="/consultation" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Consultation</span><span class="bdgs-mob-subitem-desc">Talk to Yakin's team. 18+ years directory expertise.</span></a>
          <a href="/founders-track" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Founder's Track</span><span class="bdgs-mob-subitem-desc">Co-founder level strategy + dev + AI. Apply to join.</span></a>
        </div>
        <div class="bdgs-mob-divider"></div>
        <div class="bdgs-mob-section">
          <p class="bdgs-mob-section-heading">Solutions Done For You</p>
          <a href="/solutions?category=seo" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">SEO &amp; Schema</span><span class="bdgs-mob-subitem-desc">Advanced markup &amp; technical SEO. Rank higher.</span></a>
          <a href="/solutions?category=lead-gen" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Lead Gen &amp; Conversion</span><span class="bdgs-mob-subitem-desc">Capture more leads. Turn visitors into members.</span></a>
          <a href="/solutions?category=member-profile" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Member Profile Enhancement</span><span class="bdgs-mob-subitem-desc">Custom profile layouts. Make members stand out.</span></a>
          <a href="/solutions?category=search" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Search &amp; Discovery</span><span class="bdgs-mob-subitem-desc">Optimized search flows. Help users find what they need.</span></a>
          <a href="/solutions?category=design" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Page Design &amp; Development</span><span class="bdgs-mob-subitem-desc">Stunning layouts. Built for modern directories.</span></a>
          <a href="/solutions?category=content" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Content &amp; Engagement</span><span class="bdgs-mob-subitem-desc">Keep audiences hooked. Automated content strategies.</span></a>
          <a href="/solutions?category=integrations" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Integrations</span><span class="bdgs-mob-subitem-desc">Connect your favorite tools. Seamless API integrations.</span></a>
          <a href="/solutions/member-management" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Member Management</span><span class="bdgs-mob-subitem-desc">Approval workflows, dashboards, member control.</span></a>
        </div>
        <div class="bdgs-mob-also">
          <p class="bdgs-mob-also-label">Also explore</p>
          <div class="bdgs-mob-also-pills">
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="grow-ai">Grow with AI</a>
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="tools">Tools</a>
            <a href="/themes" class="bdgs-mob-also-pill">Themes</a>
          </div>
        </div>
      </div>
    </div>
    <div class="bdgs-mob-panel" data-panel="grow-ai">
      <button type="button" class="bdgs-mob-back" data-back="main">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span>Grow with AI</span>
      </button>
      <div class="bdgs-mob-panel-scroll">
        <div class="bdgs-mob-section">
          <a href="/ai-development" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">AI-Powered Development</span><span class="bdgs-mob-subitem-desc">Faster execution. Savings passed directly to you.</span></a>
          <a href="/ai-dev-fleet" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">AI Dev Fleet</span><span class="bdgs-mob-subitem-desc">A team of AI works for you. Pay for one developer.</span></a>
          <a href="/ai-for-your-site" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">AI for Your Directory</span><span class="bdgs-mob-subitem-desc">Chatbots, agents, smart search — on your site.</span></a>
          <a href="/bd-automation" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Directory Automation</span><span class="bdgs-mob-subitem-desc">Follow-ups, lead gen, content AI, member engagement.</span></a>
          <a href="/growth-plans" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Growth Plans</span><span class="bdgs-mob-subitem-desc">AI-powered strategy. Apply to join.</span></a>
          <a href="/seo-growth" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">SEO &amp; Growth</span><span class="bdgs-mob-subitem-desc">Audits, schema, traffic strategy — directory-specific.</span></a>
        </div>
        <div class="bdgs-mob-highlight">
          <span>Claude / Anthropic Service Partner<br>— The only directory team building with AI</span>
        </div>
        <div class="bdgs-mob-also">
          <p class="bdgs-mob-also-label">Also explore</p>
          <div class="bdgs-mob-also-pills">
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="services">Services</a>
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="tools">Tools</a>
            <a href="/themes" class="bdgs-mob-also-pill">Themes</a>
          </div>
        </div>
      </div>
    </div>
    <div class="bdgs-mob-panel" data-panel="tools">
      <button type="button" class="bdgs-mob-back" data-back="main">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span>Tools</span>
      </button>
      <div class="bdgs-mob-panel-scroll">
        <div class="bdgs-mob-section">
          <a href="/tools" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">All Tools &amp; Themes</span><span class="bdgs-mob-subitem-desc">Productized software, AI tools, and premium themes.</span></a>
          <a href="/tools/brilliantchat" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">BrilliantChat</span><span class="bdgs-mob-subitem-desc">AI chatbot for your directory site.</span></a>
          <a href="/tools?category=seo" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">SEO Solutions</span><span class="bdgs-mob-subitem-desc">Schema, audit, speed, on-page fixes.</span></a>
          <a href="/tools?category=conversion" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Conversion Boosters</span><span class="bdgs-mob-subitem-desc">Lead gen, CTAs, member signup flows.</span></a>
        </div>
        <div class="bdgs-mob-also">
          <p class="bdgs-mob-also-label">Also explore</p>
          <div class="bdgs-mob-also-pills">
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="services">Services</a>
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="grow-ai">Grow with AI</a>
            <a href="/themes" class="bdgs-mob-also-pill">Themes</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
