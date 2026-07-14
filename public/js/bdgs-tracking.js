/**
 * BDGS marketing pixels — GA4 / Meta / LinkedIn
 * Boot config: #bdgs-tracking-boot data-* attributes (set in Blade).
 * Conversion: bdgsTrackZoomClinicSignup() after successful Zoom Clinic registration.
 */
(function () {
  'use strict';

  var boot = document.getElementById('bdgs-tracking-boot');
  var cfg = {
    ga4Id: boot ? (boot.getAttribute('data-ga4') || '') : '',
    metaPixelId: boot ? (boot.getAttribute('data-meta') || '') : '',
    linkedinPartnerId: boot ? (boot.getAttribute('data-linkedin-partner') || '') : '',
    linkedinZoomConversionId: boot ? (boot.getAttribute('data-linkedin-zoom-conversion') || '') : '',
  };

  window.BDGS_TRACKING = cfg;

  window.bdgsTrackZoomClinicSignup = function (data) {
    data = data || {};
    var regId = data.registration_id ? String(data.registration_id) : '';
    if (regId) {
      try {
        var dedupeKey = 'bdgs_zc_px_' + regId;
        if (window.sessionStorage && sessionStorage.getItem(dedupeKey)) {
          return;
        }
        if (window.sessionStorage) {
          sessionStorage.setItem(dedupeKey, '1');
        }
      } catch (e) {}
    }

    if (!cfg.ga4Id && !cfg.metaPixelId && !cfg.linkedinZoomConversionId) {
      return;
    }

    var clinicId = data.clinic_id || '';
    var clinicTitle = data.clinic_title || '';

    if (cfg.ga4Id && typeof window.gtag === 'function') {
      window.gtag('event', 'zoom_clinic_signup', {
        event_category: 'engagement',
        event_label: clinicTitle || 'Zoom Clinic',
        clinic_id: clinicId,
        clinic_title: clinicTitle,
        registration_id: regId,
        method: 'zoom_clinic_modal',
      });
    }

    if (cfg.metaPixelId && typeof window.fbq === 'function') {
      window.fbq('track', 'Lead', {
        content_name: 'Zoom Clinic Signup',
        content_category: 'Zoom Clinic',
        content_ids: clinicId ? [String(clinicId)] : undefined,
        status: true,
      });
    }

    if (cfg.linkedinZoomConversionId && typeof window.lintrk === 'function') {
      window.lintrk('track', { conversion_id: cfg.linkedinZoomConversionId });
    }
  };

  if (!boot) {
    return;
  }

  if (cfg.ga4Id) {
    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function () {
      window.dataLayer.push(arguments);
    };
    window.gtag('js', new Date());
    window.gtag('config', cfg.ga4Id);
  }

  if (cfg.metaPixelId) {
    /* eslint-disable */
    !(function (f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function () {
        n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s);
    })(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
    /* eslint-enable */
    window.fbq('init', cfg.metaPixelId);
    window.fbq('track', 'PageView');
  }

  if (cfg.linkedinPartnerId) {
    window._linkedin_partner_id = cfg.linkedinPartnerId;
    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
    window._linkedin_data_partner_ids.push(window._linkedin_partner_id);
    (function (l) {
      if (!l) {
        window.lintrk = function (a, b) {
          window.lintrk.q.push([a, b]);
        };
        window.lintrk.q = [];
      }
      var s = document.getElementsByTagName('script')[0];
      var b = document.createElement('script');
      b.type = 'text/javascript';
      b.async = true;
      b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
      s.parentNode.insertBefore(b, s);
    })(window.lintrk);
  }
})();
