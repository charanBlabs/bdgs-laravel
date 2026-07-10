<script>
// Modal — Zoom Clinics
// Canonical clinic time: 6:30–7:30 PM Asia/Kolkata (Tue/Thu), shown in visitor's selected timezone
const BDGS_ZOOM_CANONICAL_TZ = 'Asia/Kolkata';

let ZOOM_SESSION_DB_DATE;
let ZOOM_SESSION_END_DATE;
let BDGS_ACTIVE_CLINIC_ID = null;
let BDGS_ACTIVE_CLINIC_TITLE = 'Live Website Reviews & Open Q&A';

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
  } else {
    const session = bdgsGetNextZoomSession();
    ZOOM_SESSION_DB_DATE = session.start;
    ZOOM_SESSION_END_DATE = session.end;
    BDGS_ACTIVE_CLINIC_ID = null;
    BDGS_ACTIVE_CLINIC_TITLE = 'Live Website Reviews & Open Q&A';
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
  bdgsSetZoomSession(null);
})();

function bdgsFormatZoomSessionDisplay(tz) {
  const timeFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  const timeEndFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZoneName: 'short', timeZone: tz }).format(ZOOM_SESSION_END_DATE);
  const dateFmt = new Intl.DateTimeFormat('en-US', { weekday: 'short', month: 'short', day: 'numeric', timeZone: tz }).format(ZOOM_SESSION_DB_DATE);
  return dateFmt + ' - ' + timeFmt + ' to ' + timeEndFmt;
}

function bdgsFormatZoomTimeRange(startInstant, endInstant, tz) {
  const dateFmt = new Intl.DateTimeFormat('en-US', { weekday: 'short', month: 'short', day: 'numeric', timeZone: tz }).format(startInstant);
  const timeFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZone: tz }).format(startInstant);
  const timeEndFmt = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', timeZoneName: 'short', timeZone: tz }).format(endInstant);
  return dateFmt + ' · ' + timeFmt + ' – ' + timeEndFmt;
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
  return get('year') + get('month') + get('day') + 'T' + pad(hour) + get('minute') + get('second');
}

function bdgsUpdateGoogleCalLink() {
  const tz = bdgsGetSelectedZoomTimezone();
  const startStr = bdgsFormatGcalDateTime(ZOOM_SESSION_DB_DATE, tz);
  const endStr = bdgsFormatGcalDateTime(ZOOM_SESSION_END_DATE, tz);
  const title = encodeURIComponent('Zoom Clinic: ' + BDGS_ACTIVE_CLINIC_TITLE);
  const details = encodeURIComponent('Join us for a free live Zoom session to get help and learn Brilliant Directories.\n\nFormat: 60-min open Q&A with our devs');
  const gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' + title + '&dates=' + startStr + '/' + endStr + '&details=' + details + '&ctz=' + encodeURIComponent(tz);
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

function bdgsShowZoomForm() {
  document.getElementById('bdgsZoomModalStep1').style.display = 'none';
  document.getElementById('bdgsZoomBookingForm').style.display = 'block';
  bdgsZoomAutoFill();
}

function bdgsHideZoomForm() {
  document.getElementById('bdgsZoomBookingForm').style.display = 'none';
  document.getElementById('bdgsZoomModalStep1').style.display = 'block';
}

function bdgsFindZoomClinicById(clinicId) {
  if (!clinicId || !window.bdgsZoomClinics) return null;
  return window.bdgsZoomClinics.find(function(c) { return String(c.id) === String(clinicId); }) || null;
}

function bdgsOpenZoomModal(clinicId) {
  const clinic = bdgsFindZoomClinicById(clinicId);
  bdgsSetZoomSession(clinic);
  bdgsZoomAutoFill();
  bdgsUpdateTimezoneDisplay();
  document.getElementById('bdgsZoomModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function bdgsCloseZoomModal() {
  document.getElementById('bdgsZoomModal').classList.remove('active');
  document.body.style.overflow = '';
  setTimeout(function() {
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
      bdgsSetZoomSession(null);
    }
  }, 300);
}

function bdgsProcessZoomBooking() {
  const btn = document.getElementById('bdgsZoomScheduleBtn');
  const originalText = btn.innerHTML;
  btn.innerHTML = '<span style="opacity:0.8; letter-spacing: 0.5px;">Scheduling...</span>';
  btn.disabled = true;

  const clinicInput = document.getElementById('bdgsZoomClinicId');
  const clinicId = clinicInput && clinicInput.value ? parseInt(clinicInput.value, 10) : null;

  const payload = {
    name: document.getElementById('bdgsZoomName').value.trim(),
    email: document.getElementById('bdgsZoomEmail').value.trim(),
    registrant_timezone: bdgsGetSelectedZoomTimezone(),
  };

  if (clinicId) {
    payload.clinic_id = clinicId;
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
      return response.json().then(function(data) {
        return { response: response, data: data };
      });
    })
    .then(function(result) {
      if (!result.response.ok) {
        const message = result.data.message
          || (result.data.errors && Object.values(result.data.errors).flat()[0])
          || 'Registration failed. Please try again.';
        throw new Error(message);
      }
      bdgsShowZoomBookingSuccess(btn, originalText);
    })
    .catch(function(error) {
      btn.innerHTML = originalText;
      btn.disabled = false;
      window.alert(error.message || 'Something went wrong. Please try again.');
    });
}

function bdgsShowZoomBookingSuccess(btn, originalText) {
  const content = document.getElementById('bdgsZoomModalContent');
  const success = document.getElementById('bdgsZoomModalSuccess');

  bdgsUpdateZoomSuccessDetails();

  content.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
  content.style.opacity = '0';
  content.style.transform = 'translateY(-10px)';

  setTimeout(function() {
    content.style.display = 'none';
    success.style.display = 'block';
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
  bdgsRenderClinicCardTimes();
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
  const tz = bdgsGetSelectedZoomTimezone();
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

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bdgsRenderClinicCardTimes);
} else {
  bdgsRenderClinicCardTimes();
}
</script>
