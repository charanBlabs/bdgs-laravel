<script>
// Modal — Zoom Clinics
// Canonical clinic time: 6:30–7:30 PM Asia/Kolkata (Tue/Thu), page shows New York (EST/EDT)
const BDGS_ZOOM_CANONICAL_TZ = 'Asia/Kolkata';
const BDGS_PAGE_DISPLAY_TZ = 'America/New_York';

let ZOOM_SESSION_DB_DATE;
let ZOOM_SESSION_END_DATE;
let BDGS_ACTIVE_CLINIC_ID = null;
let BDGS_ACTIVE_CLINIC_TITLE = 'Live Website Reviews & Open Q&A';
let BDGS_ACTIVE_CLINIC_JOIN_URL = '';

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

function bdgsSetZoomSession(clinic) {
  if (clinic && clinic.starts_at && clinic.ends_at) {
    ZOOM_SESSION_DB_DATE = new Date(clinic.starts_at);
    ZOOM_SESSION_END_DATE = new Date(clinic.ends_at);
    BDGS_ACTIVE_CLINIC_ID = clinic.id || null;
    BDGS_ACTIVE_CLINIC_TITLE = clinic.title || clinic.agenda || 'Zoom Clinic';
    BDGS_ACTIVE_CLINIC_JOIN_URL = clinic.join_url || '';
  } else {
    const session = bdgsGetNextZoomSession();
    ZOOM_SESSION_DB_DATE = session.start;
    ZOOM_SESSION_END_DATE = session.end;
    BDGS_ACTIVE_CLINIC_ID = null;
    BDGS_ACTIVE_CLINIC_TITLE = 'Live Website Reviews & Open Q&A';
    BDGS_ACTIVE_CLINIC_JOIN_URL = '';
  }

  const clinicInput = document.getElementById('bdgsZoomClinicId');
  if (clinicInput) {
    clinicInput.value = BDGS_ACTIVE_CLINIC_ID || '';
  }

  const agendaEls = document.querySelectorAll('[data-bdgs-zoom-agenda]');
  agendaEls.forEach(function(el) {
    el.textContent = BDGS_ACTIVE_CLINIC_TITLE;
  });

  bdgsInitTimezoneDropdown();
  bdgsUpdateTimezoneDisplay();
}

(function bdgsInitZoomSessionDefaults() {
  const first = (window.bdgsZoomClinics && window.bdgsZoomClinics.length) ? window.bdgsZoomClinics[0] : null;
  bdgsSetZoomSession(first);
})();

function bdgsFormatZoomSessionDisplay(tz) {
  const timeFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  const timeEndFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZoneName: 'short', timeZone: tz }).format(ZOOM_SESSION_END_DATE);
  const dateFmt = new Intl.DateTimeFormat('en-US', { weekday: 'short', month: 'short', day: 'numeric', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  return dateFmt + ' - ' + timeFmt + ' to ' + timeEndFmt;
}

function bdgsFormatZoomTimeRange(startInstant, endInstant, tz, options) {
  options = options || {};
  const includeDate = options.includeDate !== false;
  const showCity = options.showCity !== false && tz === BDGS_PAGE_DISPLAY_TZ;
  const dateFmt = new Intl.DateTimeFormat('en-US', { weekday: 'short', month: 'short', day: 'numeric', timeZone: tz }).format(startInstant);
  const timeFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZone: tz }).format(startInstant);
  const timeEndFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZoneName: 'short', timeZone: tz }).format(endInstant);
  let out = includeDate ? dateFmt + ' · ' + timeFmt + ' – ' + timeEndFmt : timeFmt + ' – ' + timeEndFmt;
  if (showCity) {
    out += ' - New York';
  }
  return out;
}

function bdgsGetPageDisplayTimezone() {
  return BDGS_PAGE_DISPLAY_TZ;
}

function bdgsGetSelectedZoomTimezone() {
  const formInput = document.getElementById('bdgsZoomTimezoneForm');
  const mainInput = document.getElementById('bdgsZoomTimezone');
  return (formInput && formInput.value) || (mainInput && mainInput.value) || 'America/New_York';
}

function bdgsFormatGcalUtc(date) {
  const pad = (n) => String(n).padStart(2, '0');
  return date.getUTCFullYear()
    + pad(date.getUTCMonth() + 1)
    + pad(date.getUTCDate())
    + 'T'
    + pad(date.getUTCHours())
    + pad(date.getUTCMinutes())
    + pad(date.getUTCSeconds())
    + 'Z';
}

function bdgsBuildGoogleCalUrl(options) {
  options = options || {};
  const start = options.start || ZOOM_SESSION_DB_DATE;
  const end = options.end || ZOOM_SESSION_END_DATE;
  const title = options.title || ('Zoom Clinic: ' + BDGS_ACTIVE_CLINIC_TITLE);
  const joinUrl = options.joinUrl || '';
  const whenLabel = options.whenLabel || '';
  const name = options.name || '';
  const email = options.email || '';

  let details = 'You are registered for this free Zoom Clinic with BD Growth Suite.';
  if (whenLabel) details += '\nWhen: ' + whenLabel;
  details += '\nFormat: 60-min open Q&A with our devs';
  if (joinUrl) details += '\nJoin Zoom: ' + joinUrl;
  details += '\nPage: ' + window.location.origin + '/zoom-clinics';
  if (name || email) {
    details += '\nRegistered as: ' + [name, email ? '(' + email + ')' : ''].filter(Boolean).join(' ');
  }

  const params = new URLSearchParams({
    action: 'TEMPLATE',
    text: title,
    dates: bdgsFormatGcalUtc(start) + '/' + bdgsFormatGcalUtc(end),
    details: details,
    location: joinUrl || 'Online via Zoom',
  });

  // www.google.com avoids Workspace marketing redirects for signed-out users.
  return 'https://www.google.com/calendar/render?' + params.toString();
}

function bdgsUpdateGoogleCalLink(overrideUrl) {
  const gcalBtn = document.getElementById('bdgsGoogleCalLink');
  if (!gcalBtn) return;

  if (overrideUrl) {
    gcalBtn.href = overrideUrl;
    return;
  }

  const nameEl = document.getElementById('bdgsZoomName');
  const emailEl = document.getElementById('bdgsZoomEmail');
  let whenLabel = '';
  try {
    whenLabel = bdgsFormatZoomSessionDisplay(bdgsGetSelectedZoomTimezone());
  } catch (e) {}

  gcalBtn.href = bdgsBuildGoogleCalUrl({
    title: 'Zoom Clinic: ' + BDGS_ACTIVE_CLINIC_TITLE,
    whenLabel: whenLabel,
    joinUrl: BDGS_ACTIVE_CLINIC_JOIN_URL,
    name: nameEl ? nameEl.value.trim() : '',
    email: emailEl ? emailEl.value.trim() : '',
  });
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
    if (base) {
      try {
        const tzShort = new Intl.DateTimeFormat('en-US', { timeZone: tz, timeZoneName: 'short' })
          .formatToParts(ZOOM_SESSION_DB_DATE)
          .find(p => p.type === 'timeZoneName').value;
        opt.innerText = base.replace('{tz}', tzShort);
      } catch (e) {
        opt.innerText = base.replace(' ({tz})', '');
      }
    }
  });
  const selectedOpt = document.querySelector('.bdgs-tz-option.selected');
  if (selectedOpt) {
    ['', 'Form'].forEach(sfx => {
      const textSpan = document.getElementById('bdgsZoomTimezoneText' + sfx);
      if (textSpan) textSpan.innerText = selectedOpt.innerText;
    });
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bdgsInitTimezoneDropdown);
} else {
  bdgsInitTimezoneDropdown();
}

function bdgsZoomAutoFill() {
  const user = window.bdgsAuthUser;
  if (!user) {
    return;
  }

  const nameEl = document.getElementById('bdgsZoomName');
  const emailEl = document.getElementById('bdgsZoomEmail');

  if (nameEl && user.name) {
    nameEl.value = user.name;
  }
  if (emailEl && user.email) {
    emailEl.value = user.email;
    emailEl.readOnly = true;
    emailEl.title = 'Registrations are saved to your account email';
    emailEl.style.background = 'rgba(17, 24, 39, 0.04)';
  }
}

function bdgsUpdateTimezoneDisplay() {
  const tzInput = document.getElementById('bdgsZoomTimezone');
  const tz = tzInput ? tzInput.value : 'America/New_York';
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

function bdgsZoomModalSkipsDetails() {
  const modal = document.getElementById('bdgsZoomModal');
  return modal && modal.dataset.skipDetails === '1';
}

function bdgsResetZoomModalView() {
  const step1 = document.getElementById('bdgsZoomModalStep1');
  const form = document.getElementById('bdgsZoomBookingForm');
  if (bdgsZoomModalSkipsDetails()) {
    if (step1) step1.style.display = 'none';
    if (form) form.style.display = 'block';
  } else {
    if (step1) step1.style.display = 'block';
    if (form) form.style.display = 'none';
  }
}

function bdgsShowZoomForm() {
  document.getElementById('bdgsZoomModalStep1').style.display = 'none';
  document.getElementById('bdgsZoomBookingForm').style.display = 'block';
  bdgsZoomAutoFill();
}

function bdgsHideZoomForm() {
  document.getElementById('bdgsZoomBookingForm').style.display = 'none';
  if (!bdgsZoomModalSkipsDetails()) {
    document.getElementById('bdgsZoomModalStep1').style.display = 'block';
  }
}

function bdgsFindZoomClinicById(clinicId) {
  if (!clinicId || !window.bdgsZoomClinics) return null;
  return window.bdgsZoomClinics.find(function(c) { return String(c.id) === String(clinicId); }) || null;
}

function bdgsDefaultZoomClinic() {
  if (window.bdgsZoomClinics && window.bdgsZoomClinics.length) {
    return window.bdgsZoomClinics[0];
  }
  return null;
}

function bdgsHasRegisterableClinics() {
  return !!(window.bdgsZoomClinics && window.bdgsZoomClinics.length);
}

function bdgsShowZoomModalPanel(panel) {
  const content = document.getElementById('bdgsZoomModalContent');
  const success = document.getElementById('bdgsZoomModalSuccess');
  const empty = document.getElementById('bdgsZoomModalEmpty');
  if (content) content.style.display = panel === 'content' ? 'block' : 'none';
  if (success) success.style.display = panel === 'success' ? 'block' : 'none';
  if (empty) empty.style.display = panel === 'empty' ? 'block' : 'none';
}

function bdgsOpenZoomModal(clinicId) {
  const modal = document.getElementById('bdgsZoomModal');
  if (!modal) {
    window.alert('Zoom Clinic registration is temporarily unavailable. Please try again later.');
    return;
  }

  const clinic = bdgsFindZoomClinicById(clinicId) || (!clinicId ? bdgsDefaultZoomClinic() : null);

  if (!clinic && !bdgsHasRegisterableClinics()) {
    bdgsShowZoomModalPanel('empty');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    return;
  }

  if (clinicId && !clinic) {
    window.alert('That clinic is no longer open for registration. Please pick another upcoming clinic.');
    return;
  }

  bdgsSetZoomSession(clinic);
  bdgsZoomAutoFill();
  bdgsUpdateTimezoneDisplay();
  bdgsShowZoomModalPanel('content');
  bdgsResetZoomModalView();
  if (bdgsZoomModalSkipsDetails()) {
    bdgsShowZoomForm();
  }
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function bdgsCloseZoomModal() {
  const modal = document.getElementById('bdgsZoomModal');
  if (!modal) return;
  modal.classList.remove('active');
  document.body.style.overflow = '';
  setTimeout(function() {
    const content = document.getElementById('bdgsZoomModalContent');
    const success = document.getElementById('bdgsZoomModalSuccess');
    const empty = document.getElementById('bdgsZoomModalEmpty');
    if (content) {
      content.style.display = 'block';
      content.style.opacity = '1';
      content.style.transform = 'none';
    }
    if (success) success.style.display = 'none';
    if (empty) empty.style.display = 'none';
    const form = document.getElementById('bdgsZoomBookingForm');
    if (form) form.reset();
    bdgsSetZoomSession(bdgsDefaultZoomClinic());
    bdgsResetZoomModalView();
    const joinLink = document.getElementById('bdgsZoomJoinLink');
    if (joinLink) {
      joinLink.style.display = 'none';
      joinLink.href = '#';
    }
    const emailNote = document.getElementById('bdgsZoomEmailNote');
    if (emailNote) emailNote.style.display = 'none';
    const gcalBtn = document.getElementById('bdgsGoogleCalLink');
    if (gcalBtn) gcalBtn.removeAttribute('data-registration-id');
  }, 300);
}

function bdgsProcessZoomBooking() {
  const btn = document.getElementById('bdgsZoomScheduleBtn');
  if (!btn) return;
  const originalText = btn.innerHTML;
  btn.innerHTML = '<span style="opacity:0.8; letter-spacing: 0.5px;">Scheduling...</span>';
  btn.disabled = true;

  const clinicInput = document.getElementById('bdgsZoomClinicId');
  const clinicId = clinicInput && clinicInput.value ? parseInt(clinicInput.value, 10) : null;

  if (!clinicId && !BDGS_ACTIVE_CLINIC_ID && !bdgsHasRegisterableClinics()) {
    btn.innerHTML = originalText;
    btn.disabled = false;
    bdgsShowZoomModalPanel('empty');
    return;
  }

  const nameEl = document.getElementById('bdgsZoomName');
  const emailEl = document.getElementById('bdgsZoomEmail');
  const directoryEl = document.getElementById('bdgsZoomDirectoryUrl');
  const helpEl = document.getElementById('bdgsZoomHelpTopic');
  const payload = {
    name: nameEl ? nameEl.value.trim() : '',
    email: emailEl ? emailEl.value.trim() : '',
    directory_url: directoryEl ? directoryEl.value.trim() : '',
    help_topic: helpEl ? helpEl.value.trim() : '',
    registrant_timezone: bdgsGetSelectedZoomTimezone(),
  };

  // Logged-in users: keep account email so the seat shows in My Zoom Clinics.
  if (window.bdgsAuthUser && window.bdgsAuthUser.email) {
    payload.email = String(window.bdgsAuthUser.email).trim();
    if (window.bdgsAuthUser.name && !payload.name) {
      payload.name = String(window.bdgsAuthUser.name).trim();
    }
  }

  if (!payload.name || !payload.email) {
    btn.innerHTML = originalText;
    btn.disabled = false;
    window.alert('Please enter your name and a valid email to finish registration.');
    return;
  }

  if (clinicId) {
    payload.clinic_id = clinicId;
  } else if (BDGS_ACTIVE_CLINIC_ID) {
    payload.clinic_id = BDGS_ACTIVE_CLINIC_ID;
  }

  fetch('/api/zoom-clinics/register', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify(payload),
  })
    .then(function(response) {
      return response.text().then(function(text) {
        var data = {};
        if (text) {
          try {
            data = JSON.parse(text);
          } catch (e) {
            throw new Error(response.ok
              ? 'Unexpected response from the server. Please try again.'
              : 'Registration is temporarily unavailable. Please try again in a moment.');
          }
        }
        return { response: response, data: data };
      });
    })
    .then(function(result) {
      if (result.response.status === 429) {
        throw new Error('Too many registration attempts. Please wait a minute and try again.');
      }
      if (!result.response.ok) {
        const message = (result.data.errors && Object.values(result.data.errors).flat()[0])
          || result.data.message
          || 'Registration failed. Please try again.';
        throw new Error(message);
      }
      bdgsShowZoomBookingSuccess(btn, originalText, result.data);
    })
    .catch(function(error) {
      btn.innerHTML = originalText;
      btn.disabled = false;
      var msg = error && error.message ? error.message : 'Something went wrong. Please try again.';
      if (/failed to fetch|networkerror|load failed/i.test(msg)) {
        msg = 'Network error — check your connection and try again.';
      }
      window.alert(msg);
    });
}

function bdgsShowZoomBookingSuccess(btn, originalText, registrationData) {
  // GA4 + Meta Lead + LinkedIn — fire on successful submit (in-modal; no thank-you URL).
  if (typeof window.bdgsTrackZoomClinicSignup === 'function') {
    window.bdgsTrackZoomClinicSignup(registrationData || {});
  }

  const content = document.getElementById('bdgsZoomModalContent');
  const success = document.getElementById('bdgsZoomModalSuccess');
  if (!content || !success) {
    btn.innerHTML = originalText;
    btn.disabled = false;
    window.alert('You’re registered, but the confirmation screen could not be shown. Check your email for details.');
    return;
  }

  if (registrationData) {
    if (registrationData.clinic_title) {
      BDGS_ACTIVE_CLINIC_TITLE = registrationData.clinic_title;
      document.querySelectorAll('[data-bdgs-zoom-agenda]').forEach(function(el) {
        el.textContent = BDGS_ACTIVE_CLINIC_TITLE;
      });
    }
    if (registrationData.starts_at) {
      ZOOM_SESSION_DB_DATE = new Date(registrationData.starts_at);
    }
    if (registrationData.ends_at) {
      ZOOM_SESSION_END_DATE = new Date(registrationData.ends_at);
    }
    if (registrationData.join_url) {
      BDGS_ACTIVE_CLINIC_JOIN_URL = registrationData.join_url;
    }
  }

  bdgsUpdateZoomSuccessDetails();

  if (registrationData && registrationData.google_calendar_url) {
    bdgsUpdateGoogleCalLink(registrationData.google_calendar_url);
  } else {
    bdgsUpdateGoogleCalLink(bdgsBuildGoogleCalUrl({
      title: 'Zoom Clinic: ' + BDGS_ACTIVE_CLINIC_TITLE,
      joinUrl: registrationData && registrationData.join_url ? registrationData.join_url : BDGS_ACTIVE_CLINIC_JOIN_URL,
      whenLabel: (function() {
        try { return bdgsFormatZoomSessionDisplay(bdgsGetSelectedZoomTimezone()); } catch (e) { return ''; }
      })(),
      name: document.getElementById('bdgsZoomName').value.trim(),
      email: document.getElementById('bdgsZoomEmail').value.trim(),
    }));
  }

  const gcalBtn = document.getElementById('bdgsGoogleCalLink');
  if (gcalBtn && registrationData && registrationData.registration_id) {
    gcalBtn.setAttribute('data-registration-id', String(registrationData.registration_id));
  }

  const joinLink = document.getElementById('bdgsZoomJoinLink');
  const joinUrl = (registrationData && registrationData.join_url) || BDGS_ACTIVE_CLINIC_JOIN_URL;
  if (joinLink) {
    if (joinUrl) {
      joinLink.href = joinUrl;
      joinLink.style.display = 'flex';
    } else {
      joinLink.href = '#';
      joinLink.style.display = 'none';
    }
  }

  const emailNote = document.getElementById('bdgsZoomEmailNote');
  if (emailNote) {
    emailNote.style.display = (registrationData && registrationData.confirmation_sent === false) ? 'block' : 'none';
  }

  content.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
  content.style.opacity = '0';
  content.style.transform = 'translateY(-10px)';

  setTimeout(function() {
    bdgsShowZoomModalPanel('success');
    success.style.opacity = '0';
    success.style.transform = 'scale(0.92)';
    success.style.transition = 'opacity 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275), transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
    void success.offsetWidth;
    success.style.opacity = '1';
    success.style.transform = 'scale(1)';

    setTimeout(function() {
      btn.innerHTML = originalText;
      btn.disabled = false;
    }, 500);
  }, 250);
}

function bdgsMarkCalendarAddedFromModal(registrationId) {
  if (!registrationId || !window.bdgsAuthUser) {
    return;
  }
  const csrf = document.querySelector('meta[name="csrf-token"]');
  const token = csrf ? csrf.getAttribute('content') : '';
  if (!token) {
    return;
  }
  fetch('/dashboard/my-zoom-clinics/' + registrationId + '/calendar-added', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token,
      'X-Requested-With': 'XMLHttpRequest',
    },
    credentials: 'same-origin',
  }).then(function(res) {
        if (res.status === 401 || res.status === 419) {
          if (window.confirm('Your session expired. Sign in again to keep calendar status in sync?')) {
            window.location.href = '/dashboard/my-zoom-clinics';
          }
          return null;
        }
    if (res.status === 403) {
      return null;
    }
    return res.ok ? res.json() : null;
  }).catch(function() {});
}

function bdgsSelectTimezone(el, event) {
  event.stopPropagation();
  const value = el.getAttribute('data-value');
  const text = el.innerText;

  ['', 'Form'].forEach(sfx => {
    const input = document.getElementById('bdgsZoomTimezone' + sfx);
    const textSpan = document.getElementById('bdgsZoomTimezoneText' + sfx);
    if (input) input.value = value;
    if (textSpan) textSpan.innerText = text;

    const options = document.querySelectorAll('#bdgsTzDropdown' + sfx + ' .bdgs-tz-option');
    options.forEach(opt => opt.classList.remove('selected'));
    const matchingOpt = document.querySelector('#bdgsTzDropdown' + sfx + ' .bdgs-tz-option[data-value="' + value + '"]');
    if (matchingOpt) matchingOpt.classList.add('selected');

    const dropdown = document.getElementById('bdgsTzDropdown' + sfx);
    if (dropdown) dropdown.classList.remove('show');
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

function bdgsRenderClinicCardTimes() {
  const tz = bdgsGetPageDisplayTimezone();
  document.querySelectorAll('[data-clinic-starts]').forEach(function(el) {
    const start = new Date(el.getAttribute('data-clinic-starts'));
    const end = new Date(el.getAttribute('data-clinic-ends'));
    try {
      el.textContent = bdgsFormatZoomTimeRange(start, end, tz);
    } catch (e) {
      el.textContent = el.getAttribute('data-clinic-fallback') || '';
    }
  });
}

function bdgsUpdateHeroScheduleTime() {
  const heroEl = document.getElementById('zc-hero-time');
  if (!heroEl) {
    return;
  }
  const tz = bdgsGetPageDisplayTimezone();
  let start;
  let end;
  const startsAttr = heroEl.getAttribute('data-clinic-starts');
  const endsAttr = heroEl.getAttribute('data-clinic-ends');
  if (startsAttr && endsAttr) {
    start = new Date(startsAttr);
    end = new Date(endsAttr);
  } else if (window.bdgsZoomClinics && window.bdgsZoomClinics.length) {
    const featured = window.bdgsZoomClinics.find(function(c) { return c.is_live; }) || window.bdgsZoomClinics[0];
    start = new Date(featured.starts_at);
    end = new Date(featured.ends_at);
  } else {
    const session = bdgsGetNextZoomSession();
    start = session.start;
    end = session.end;
  }
  try {
    heroEl.textContent = bdgsFormatZoomTimeRange(start, end, tz, { includeDate: false });
  } catch (e) {
    heroEl.textContent = heroEl.getAttribute('data-fallback') || '';
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function() {
    bdgsRenderClinicCardTimes();
    bdgsUpdateHeroScheduleTime();
    const gcalBtn = document.getElementById('bdgsGoogleCalLink');
    if (gcalBtn) {
      gcalBtn.addEventListener('click', function() {
        const registrationId = gcalBtn.getAttribute('data-registration-id');
        bdgsMarkCalendarAddedFromModal(registrationId);
      });
    }
  });
} else {
  bdgsRenderClinicCardTimes();
  bdgsUpdateHeroScheduleTime();
  const gcalBtn = document.getElementById('bdgsGoogleCalLink');
  if (gcalBtn) {
    gcalBtn.addEventListener('click', function() {
      const registrationId = gcalBtn.getAttribute('data-registration-id');
      bdgsMarkCalendarAddedFromModal(registrationId);
    });
  }
}
</script>
