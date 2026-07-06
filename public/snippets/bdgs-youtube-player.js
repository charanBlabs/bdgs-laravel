/**
 * BDGS webinar player — lite YouTube facade + custom control bar (nocookie, minimal chrome).
 *
 * Behavior:
 *  - Facade first: each host shows a thumbnail poster + coral play button. The YouTube
 *    iframe/player is NOT created until the visitor clicks play (perf + privacy + no autoplay surprises).
 *  - Custom controls (native YouTube chrome stays off via controls=0):
 *      play/pause · seek bar with buffered track + time · mute + volume · quality · CC · fullscreen.
 *  - Quality menu defaults to Auto; uses YouTube IFrame API quality levels when available.
 *  - Only one video plays at a time (starting one pauses the others).
 *
 * Requires bdgs-webinar-videos.js loaded first (window.BDGS_WEBINAR_VIDEOS).
 */
(function () {
  'use strict';

  var REGISTRY = window.BDGS_WEBINAR_VIDEOS || {};
  var apiRequested = false;
  var apiReady = false;
  var pendingInit = [];
  var allHosts = [];
  var lastVolume = 100;
  var lastMuted = false;

  var QUALITY_ORDER = ['default', 'hd1080', 'hd720', 'large', 'medium', 'small', 'tiny'];
  function normalizeQuality(q) {
    if (!q) return 'default';
    q = String(q).toLowerCase();
    if (q === 'auto' || q === 'default') return 'default';
    return q;
  }

  var QUALITY_LABELS = {
    default: 'Auto',
    hd1080: '1080p',
    hd720: '720p',
    large: '480p',
    medium: '360p',
    small: '240p',
    tiny: '144p'
  };

  var ICON = {
    play: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>',
    pause: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>',
    volume: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3a4.5 4.5 0 00-2.5-4.03v8.05A4.5 4.5 0 0016.5 12z"/></svg>',
    muted: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.59 3L19 9.41 17.59 8l-2.09 2.09L13.41 8 12 9.41 14.09 11.5 12 13.59 13.41 15l2.09-2.09L17.59 15 19 13.59 16.91 11.5z"/></svg>',
    fsOpen: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>',
    fsExit: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/></svg>'
  };

  function origin() {
    return window.location.origin || 'https://bdgrowthsuite.com';
  }

  function metaForHost(host) {
    var key = host.getAttribute('data-bdgs-yt-key');
    return key ? REGISTRY[key] : null;
  }

  function fmtTime(secs) {
    secs = Math.max(0, Math.floor(secs || 0));
    var h = Math.floor(secs / 3600);
    var m = Math.floor((secs % 3600) / 60);
    var s = secs % 60;
    var mm = h ? (m < 10 ? '0' + m : '' + m) : '' + m;
    var ss = s < 10 ? '0' + s : '' + s;
    return (h ? h + ':' : '') + mm + ':' + ss;
  }

  function isTouchUi() {
    try {
      return window.matchMedia('(hover: none), (pointer: coarse)').matches;
    } catch (e) {
      return 'ontouchstart' in window;
    }
  }

  /* ---- Preconnect (once) so the first play starts faster ---- */
  var preconnected = false;
  function preconnect() {
    if (preconnected) return;
    preconnected = true;
    ['https://www.youtube-nocookie.com', 'https://www.google.com',
     'https://googleads.g.doubleclick.net', 'https://static.doubleclick.net',
     'https://i.ytimg.com', 'https://s.ytimg.com'].forEach(function (href) {
      var l = document.createElement('link');
      l.rel = 'preconnect';
      l.href = href;
      l.crossOrigin = '';
      document.head.appendChild(l);
    });
  }

  /* ---- Poster (facade) ---- */
  function buildPoster(host, meta) {
    if (host.querySelector('.bdgs-yt-poster')) return;
    var poster = document.createElement('div');
    poster.className = 'bdgs-yt-poster';
    if (meta && meta.id) {
      var hq = 'https://i.ytimg.com/vi/' + meta.id + '/hqdefault.jpg';
      var max = 'https://i.ytimg.com/vi/' + meta.id + '/maxresdefault.jpg';
      poster.style.backgroundImage = 'url("' + hq + '")';
      // Upgrade to crisp 16:9 maxres if it exists.
      var probe = new Image();
      probe.onload = function () {
        if (probe.naturalWidth > 320) poster.style.backgroundImage = 'url("' + max + '")';
      };
      probe.src = max;
    }
    host.insertBefore(poster, host.firstChild);
  }

  /* ---- Control bar — progress row + transport row ---- */
  function buildControls(host) {
    var bar = document.createElement('div');
    bar.className = 'bdgs-yt-controls';
    bar.setAttribute('hidden', '');
    bar.innerHTML =
      '<div class="bdgs-yt-progress-row">' +
        '<div class="bdgs-yt-scrub" role="group" aria-label="Video progress">' +
          '<div class="bdgs-yt-scrub-track">' +
            '<div class="bdgs-yt-scrub-buffered"></div>' +
            '<div class="bdgs-yt-scrub-played"></div>' +
          '</div>' +
          '<input type="range" class="bdgs-yt-scrub-input" min="0" max="1000" step="1" value="0" aria-label="Seek video">' +
        '</div>' +
      '</div>' +
      '<div class="bdgs-yt-controls-row">' +
        '<button type="button" class="bdgs-yt-ctrl bdgs-yt-btn-play" aria-label="Play">' + ICON.play + '</button>' +
        '<div class="bdgs-yt-volume-group">' +
          '<button type="button" class="bdgs-yt-ctrl bdgs-yt-btn-mute" aria-label="Mute">' + ICON.volume + '</button>' +
          '<div class="bdgs-yt-volume-wrap">' +
            '<div class="bdgs-yt-volume-ui">' +
              '<div class="bdgs-yt-volume-track" aria-hidden="true"><div class="bdgs-yt-volume-fill"></div></div>' +
              '<input type="range" class="bdgs-yt-volume" min="0" max="100" step="1" value="100" aria-label="Volume">' +
            '</div>' +
          '</div>' +
        '</div>' +
        '<span class="bdgs-yt-time-wrap">' +
          '<span class="bdgs-yt-time bdgs-yt-time-cur">0:00</span>' +
          '<span class="bdgs-yt-time-sep"> / </span>' +
          '<span class="bdgs-yt-time bdgs-yt-time-dur">0:00</span>' +
        '</span>' +
        '<span class="bdgs-yt-controls-spacer"></span>' +
        '<button type="button" class="bdgs-yt-ctrl bdgs-yt-btn-cc" aria-label="Subtitles" aria-pressed="false" hidden>CC</button>' +
        '<div class="bdgs-yt-quality">' +
          '<button type="button" class="bdgs-yt-ctrl bdgs-yt-btn-quality" aria-label="Quality" aria-haspopup="true" aria-expanded="false">' +
            '<span class="bdgs-yt-quality-label">Auto</span>' +
          '</button>' +
          '<div class="bdgs-yt-quality-menu" role="menu" hidden></div>' +
        '</div>' +
        '<button type="button" class="bdgs-yt-ctrl bdgs-yt-btn-fs" aria-label="Fullscreen">' + ICON.fsOpen + '</button>' +
      '</div>';
    host.appendChild(bar);
    return bar;
  }

  function wireControls(host, player, meta) {
    var bar = buildControls(host);
    var touchUi = isTouchUi();
    if (touchUi) host.classList.add('bdgs-yt-touch');

    var tapLayer = document.createElement('button');
    tapLayer.type = 'button';
    tapLayer.className = 'bdgs-yt-tap-layer';
    tapLayer.setAttribute('aria-label', 'Show video controls');
    tapLayer.hidden = true;
    host.insertBefore(tapLayer, bar);

    var btnPlay = bar.querySelector('.bdgs-yt-btn-play');
    var curEl = bar.querySelector('.bdgs-yt-time-cur');
    var durEl = bar.querySelector('.bdgs-yt-time-dur');
    var scrub = bar.querySelector('.bdgs-yt-scrub-input');
    var played = bar.querySelector('.bdgs-yt-scrub-played');
    var buffered = bar.querySelector('.bdgs-yt-scrub-buffered');
    var btnMute = bar.querySelector('.bdgs-yt-btn-mute');
    var volGroup = bar.querySelector('.bdgs-yt-volume-group');
    var vol = bar.querySelector('.bdgs-yt-volume');
    var volFill = bar.querySelector('.bdgs-yt-volume-fill');
    var btnCc = bar.querySelector('.bdgs-yt-btn-cc');
    var qualityWrap = bar.querySelector('.bdgs-yt-quality');
    var btnQuality = bar.querySelector('.bdgs-yt-btn-quality');
    var qualityMenu = bar.querySelector('.bdgs-yt-quality-menu');
    var qualityLabel = bar.querySelector('.bdgs-yt-quality-label');
    var btnFs = bar.querySelector('.bdgs-yt-btn-fs');

    var scrubbing = false;
    var ccOn = false;
    var ticker = null;
    var hostMuted = false;
    var hostVolume = lastVolume;
    var selectedQuality = 'default';
    var qualityUserSet = false;

    function paintVolumeFill() {
      var v = parseInt(vol.value, 10);
      if (isNaN(v)) v = hostVolume || 100;
      if (volFill) volFill.style.width = Math.max(0, Math.min(100, v)) + '%';
    }

    function setPlayedVisual(pct) {
      pct = Math.max(0, Math.min(100, pct));
      played.style.width = pct + '%';
    }

    function tick() {
      if (scrubbing) return;
      var dur = 0, cur = 0;
      try { dur = player.getDuration() || 0; cur = player.getCurrentTime() || 0; } catch (e) { return; }
      if (dur > 0) {
        var pct = (cur / dur) * 100;
        setPlayedVisual(pct);
        scrub.value = Math.round(pct * 10);
        curEl.textContent = fmtTime(cur);
        durEl.textContent = fmtTime(dur);
      }
      try { buffered.style.width = ((player.getVideoLoadedFraction() || 0) * 100) + '%'; } catch (e) {}
    }

    function startTicker() { if (!ticker) ticker = setInterval(tick, 250); }
    function stopTicker() { if (ticker) { clearInterval(ticker); ticker = null; } }

    function reflectPlaying(isPlaying) {
      btnPlay.innerHTML = isPlaying ? ICON.pause : ICON.play;
      btnPlay.setAttribute('aria-label', isPlaying ? 'Pause' : 'Play');
    }

    function paintMuteIcon() {
      if (hostMuted) {
        btnMute.innerHTML = ICON.muted;
        btnMute.setAttribute('aria-label', 'Unmute');
        btnMute.setAttribute('aria-pressed', 'true');
      } else {
        btnMute.innerHTML = ICON.volume;
        btnMute.setAttribute('aria-label', 'Mute');
        btnMute.setAttribute('aria-pressed', 'false');
      }
      vol.value = hostVolume || lastVolume || 100;
      paintVolumeFill();
    }

    function syncMuteFromPlayer() {
      try {
        hostMuted = player.isMuted();
        if (!hostMuted) {
          hostVolume = player.getVolume() || hostVolume || lastVolume || 100;
        }
      } catch (e) { /* keep hostMuted */ }
      paintMuteIcon();
    }

    function applyMute(wantMuted) {
      hostMuted = wantMuted;
      lastMuted = wantMuted;
      try {
        if (wantMuted) {
          hostVolume = player.getVolume() || hostVolume || lastVolume || 100;
          lastVolume = hostVolume;
          player.mute();
        } else {
          player.unMute();
          var v = hostVolume || lastVolume || 100;
          if (v < 1) v = 100;
          player.setVolume(v);
          hostVolume = v;
        }
      } catch (e) {}
      paintMuteIcon();
      setTimeout(syncMuteFromPlayer, 120);
    }

    btnPlay.addEventListener('click', function () {
      var state;
      try { state = player.getPlayerState(); } catch (e) { return; }
      if (state === 1) { player.pauseVideo(); } else { player.playVideo(); }
    });

    scrub.addEventListener('input', function () {
      scrubbing = true;
      var pct = scrub.value / 10;
      setPlayedVisual(pct);
      var dur = 0;
      try { dur = player.getDuration() || 0; } catch (e) {}
      curEl.textContent = fmtTime((pct / 100) * dur);
    });
    function commitSeek() {
      var dur = 0;
      try { dur = player.getDuration() || 0; } catch (e) {}
      var t = (scrub.value / 1000) * dur;
      try { player.seekTo(t, true); } catch (e) {}
      scrubbing = false;
    }
    scrub.addEventListener('change', commitSeek);
    scrub.addEventListener('pointerup', commitSeek);
    scrub.addEventListener('pointerdown', function () {
      scrubbing = true;
      showBar();
    });

    var volIgnoreClose = false;

    function closeVolumePanel() {
      if (volGroup) volGroup.classList.remove('is-open');
    }

    function syncTapLayer() {
      var playing = host.classList.contains('bdgs-yt-playing');
      var ended = host.classList.contains('bdgs-yt-is-ended');
      var showUi = host.classList.contains('bdgs-yt-show-ui');
      var loading = host.classList.contains('bdgs-yt-loading');
      tapLayer.hidden = !(touchUi && playing && !ended && !loading && !showUi);
    }

    vol.addEventListener('input', function () {
      var v = parseInt(vol.value, 10);
      paintVolumeFill();
      try {
        if (v === 0) {
          applyMute(true);
        } else {
          hostVolume = v;
          lastVolume = v;
          applyMute(false);
          player.setVolume(v);
          paintMuteIcon();
        }
      } catch (e) {}
    });

    btnMute.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (touchUi && volGroup && !volGroup.classList.contains('is-open')) {
        volGroup.classList.add('is-open');
        closeQualityMenu();
        showBar();
        volIgnoreClose = true;
        setTimeout(function () { volIgnoreClose = false; }, 200);
        return;
      }
      applyMute(!hostMuted);
    });

    if (volGroup && !touchUi) {
      volGroup.addEventListener('mouseenter', function () { volGroup.classList.add('is-open'); });
      volGroup.addEventListener('mouseleave', function () { volGroup.classList.remove('is-open'); });
      volGroup.addEventListener('focusin', function () { volGroup.classList.add('is-open'); });
      volGroup.addEventListener('focusout', function () {
        if (!volGroup.contains(document.activeElement)) volGroup.classList.remove('is-open');
      });
    }
    if (volGroup && touchUi) {
      volGroup.addEventListener('focusin', function () { volGroup.classList.add('is-open'); });
    }

    // Captions: probe availability shortly after playback, then toggle via the captions module.
    function ccModuleName() {
      try {
        var t = player.getOption('captions', 'tracklist');
        if (t) return 'captions';
      } catch (e) {}
      try {
        var t2 = player.getOption('cc', 'tracklist');
        if (t2) return 'cc';
      } catch (e) {}
      return null;
    }
    function probeCaptions() {
      var mod = ccModuleName();
      var list = null;
      if (mod) { try { list = player.getOption(mod, 'tracklist'); } catch (e) {} }
      if (list && list.length) {
        btnCc.hidden = false;
        btnCc._mod = mod;
      }
    }
    btnCc.addEventListener('click', function () {
      var mod = btnCc._mod || ccModuleName() || 'captions';
      ccOn = !ccOn;
      try {
        if (ccOn) player.setOption(mod, 'track', { languageCode: 'en' });
        else player.setOption(mod, 'track', {});
      } catch (e) {}
      btnCc.setAttribute('aria-pressed', ccOn ? 'true' : 'false');
      btnCc.classList.toggle('is-active', ccOn);
      closeQualityMenu();
    });

    function qualityLabelFor(q) {
      return QUALITY_LABELS[q] || q;
    }

    function updateQualityButton() {
      if (!qualityLabel) return;
      qualityLabel.textContent = (!qualityUserSet || selectedQuality === 'default')
        ? 'Auto'
        : qualityLabelFor(selectedQuality);
    }

    function closeQualityMenu() {
      if (!qualityMenu) return;
      qualityMenu.hidden = true;
      if (btnQuality) btnQuality.setAttribute('aria-expanded', 'false');
      if (qualityWrap) qualityWrap.classList.remove('is-open');
    }

    function openQualityMenu() {
      if (!qualityMenu) return;
      qualityMenu.hidden = false;
      if (btnQuality) btnQuality.setAttribute('aria-expanded', 'true');
      if (qualityWrap) qualityWrap.classList.add('is-open');
    }

    function buildQualityMenu() {
      if (!qualityMenu) return;
      var raw = [];
      try { raw = player.getAvailableQualityLevels() || []; } catch (e) {}
      var available = [];
      var seenKeys = {};
      raw.forEach(function (q) {
        var n = normalizeQuality(q);
        if (n === 'unknown' || seenKeys[n]) return;
        seenKeys[n] = true;
        available.push(n);
      });

      var items = [];
      var seenLabels = {};
      function pushItem(q) {
        q = normalizeQuality(q);
        if (q === 'unknown') return;
        var label = qualityLabelFor(q);
        if (seenLabels[label]) return;
        seenLabels[label] = true;
        items.push(q);
      }
      pushItem('default');
      QUALITY_ORDER.slice(1).forEach(function (q) {
        if (available.indexOf(q) !== -1) pushItem(q);
      });
      available.forEach(function (q) {
        if (q !== 'default') pushItem(q);
      });

      var activeQ = (!qualityUserSet || selectedQuality === 'default') ? 'default' : normalizeQuality(selectedQuality);
      qualityMenu.innerHTML = items.map(function (q) {
        var active = q === activeQ ? ' is-active' : '';
        return '<button type="button" class="bdgs-yt-quality-opt' + active + '" role="menuitemradio" aria-checked="' + (q === activeQ ? 'true' : 'false') + '" data-quality="' + q + '">' + qualityLabelFor(q) + '</button>';
      }).join('');

      qualityMenu.querySelectorAll('.bdgs-yt-quality-opt').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          var q = normalizeQuality(btn.getAttribute('data-quality') || 'default');
          selectedQuality = q;
          qualityUserSet = q !== 'default';
          try {
            player.setPlaybackQuality(q);
          } catch (err) {}
          updateQualityButton();
          buildQualityMenu();
          closeQualityMenu();
        });
      });
    }

    if (btnQuality && qualityMenu) {
      btnQuality.addEventListener('click', function (e) {
        e.stopPropagation();
        if (qualityMenu.hidden) {
          buildQualityMenu();
          openQualityMenu();
        } else {
          closeQualityMenu();
        }
      });
    }

    document.addEventListener('click', function (e) {
      if (qualityWrap && !qualityWrap.contains(e.target)) closeQualityMenu();
      if (volIgnoreClose) return;
      if (touchUi && volGroup && !volGroup.contains(e.target)) closeVolumePanel();
    });

    tapLayer.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      showBar();
    });

    btnFs.addEventListener('click', function () {
      closeQualityMenu();
      var doc = document;
      var full = doc.fullscreenElement || doc.webkitFullscreenElement;
      if (full) {
        (doc.exitFullscreen || doc.webkitExitFullscreen || function () {}).call(doc);
      } else {
        (host.requestFullscreen || host.webkitRequestFullscreen || function () {}).call(host);
      }
    });
    function onFsChange() {
      var full = document.fullscreenElement === host || document.webkitFullscreenElement === host;
      host.classList.toggle('bdgs-yt-fs', !!full);
      btnFs.innerHTML = full ? ICON.fsExit : ICON.fsOpen;
      btnFs.setAttribute('aria-label', full ? 'Exit fullscreen' : 'Fullscreen');
    }
    document.addEventListener('fullscreenchange', onFsChange);
    document.addEventListener('webkitfullscreenchange', onFsChange);

    // Auto-hide controls while playing; reveal on activity / focus.
    var hideTimer = null;
    function showBar() {
      host.classList.add('bdgs-yt-show-ui');
      syncTapLayer();
      if (hideTimer) clearTimeout(hideTimer);
      hideTimer = setTimeout(function () {
        var state;
        try { state = player.getPlayerState(); } catch (e) { state = -1; }
        if (state === 1) {
          host.classList.remove('bdgs-yt-show-ui');
          syncTapLayer();
          if (touchUi) {
            closeVolumePanel();
            closeQualityMenu();
          }
        }
      }, touchUi ? 4000 : 2600);
    }
    host.addEventListener('mousemove', showBar);
    host.addEventListener('touchstart', showBar, { passive: true });
    bar.addEventListener('touchstart', showBar, { passive: true });
    bar.addEventListener('focusin', function () {
      host.classList.add('bdgs-yt-show-ui');
      syncTapLayer();
      if (hideTimer) clearTimeout(hideTimer);
    });

    return {
      onReady: function () {
        bar.removeAttribute('hidden');
        hostVolume = lastVolume;
        try {
          player.unMute();
          player.setVolume(hostVolume);
          if (lastMuted) player.mute();
        } catch (e) {}
        setTimeout(syncMuteFromPlayer, 200);
        setTimeout(syncMuteFromPlayer, 800);
        try { durEl.textContent = fmtTime(player.getDuration()); } catch (e) {}
        setTimeout(probeCaptions, 1400);
        setTimeout(probeCaptions, 3200);
        updateQualityButton();
        buildQualityMenu();
        paintVolumeFill();
        syncTapLayer();
      },
      onState: function (state) {
        if (state === 1) { reflectPlaying(true); startTicker(); showBar(); setTimeout(syncMuteFromPlayer, 300); }
        else if (state === 2) { reflectPlaying(false); stopTicker(); tick(); host.classList.add('bdgs-yt-show-ui'); syncTapLayer(); }
        else if (state === 0) { reflectPlaying(false); stopTicker(); syncTapLayer(); }
      }
    };
  }

  /* ---- Ended overlay + play button wiring ---- */
  function showEnded(host, player) {
    host.classList.add('bdgs-yt-is-ended');
    host.classList.remove('bdgs-yt-playing', 'bdgs-yt-show-ui', 'bdgs-yt-loading');
    var overlay = host.querySelector('.bdgs-yt-ended');
    if (overlay) {
      overlay.hidden = false;
      overlay.removeAttribute('hidden');
    }
    var controls = host.querySelector('.bdgs-yt-controls');
    if (controls) controls.setAttribute('hidden', '');
    var tap = host.querySelector('.bdgs-yt-tap-layer');
    if (tap) tap.hidden = true;
    try { player.pauseVideo(); } catch (e) {}
  }
  function hideEnded(host) {
    host.classList.remove('bdgs-yt-is-ended');
    var overlay = host.querySelector('.bdgs-yt-ended');
    if (overlay) {
      overlay.hidden = true;
      overlay.setAttribute('hidden', '');
    }
  }

  function pauseOthers(current) {
    allHosts.forEach(function (h) {
      if (h !== current && h.bdgsYtPlayer) {
        try { h.bdgsYtPlayer.pauseVideo(); } catch (e) {}
      }
    });
  }

  /* ---- Lazy player creation on first play ---- */
  function createPlayer(host) {
    if (host.dataset.bdgsYtInit === '1') return;
    var meta = metaForHost(host);
    if (!meta || !meta.id) return;
    var mount = host.querySelector('.bdgs-yt-player-mount');
    if (!mount || !mount.id) return;
    host.dataset.bdgsYtInit = '1';

    var ctl;
    var player = new YT.Player(mount.id, {
      host: 'https://www.youtube-nocookie.com',
      videoId: meta.id,
      playerVars: {
        autoplay: 1, rel: 0, modestbranding: 1, playsinline: 1,
        iv_load_policy: 3, controls: 0, disablekb: 1, fs: 0,
        cc_load_policy: 0, enablejsapi: 1, origin: origin()
      },
      events: {
        onReady: function () {
          host.classList.add('bdgs-yt-ready');
          ctl = wireControls(host, player, meta);
          ctl.onReady();
          var replay = host.querySelector('.bdgs-yt-replay');
          if (replay && replay.dataset.bound !== '1') {
            replay.dataset.bound = '1';
            replay.addEventListener('click', function () {
              hideEnded(host);
              host.classList.add('bdgs-yt-playing');
              var controls = host.querySelector('.bdgs-yt-controls');
              if (controls) controls.removeAttribute('hidden');
              try { player.seekTo(0, true); player.playVideo(); } catch (e) {}
            });
          }
        },
        onStateChange: function (e) {
          if (e.data === YT.PlayerState.PLAYING) {
            hideEnded(host);
            host.classList.add('bdgs-yt-playing');
            host.classList.remove('bdgs-yt-loading');
            var controls = host.querySelector('.bdgs-yt-controls');
            if (controls && host.bdgsYtPlayer) controls.removeAttribute('hidden');
            pauseOthers(host);
          }
          if (ctl) ctl.onState(e.data);
          if (e.data === YT.PlayerState.ENDED) showEnded(host, player);
        }
      }
    });
    host.bdgsYtPlayer = player;
  }

  function requestApi(cb) {
    if (apiReady && window.YT && window.YT.Player) { cb(); return; }
    pendingInit.push(cb);
    if (apiRequested) return;
    apiRequested = true;
    var prev = window.onYouTubeIframeAPIReady;
    window.onYouTubeIframeAPIReady = function () {
      apiReady = true;
      if (typeof prev === 'function') prev();
      pendingInit.splice(0).forEach(function (fn) { fn(); });
    };
    var tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    tag.async = true;
    var first = document.getElementsByTagName('script')[0];
    first.parentNode.insertBefore(tag, first);
  }

  function onPlayClick(host) {
    if (host.dataset.bdgsYtInit === '1') {
      try { host.bdgsYtPlayer.playVideo(); } catch (e) {}
      return;
    }
    host.classList.add('bdgs-yt-loading', 'bdgs-yt-playing');
    preconnect();
    requestApi(function () { createPlayer(host); });
  }

  function initHostFacade(host) {
    if (host.dataset.bdgsYtFacade === '1') return;
    host.dataset.bdgsYtFacade = '1';
    allHosts.push(host);
    buildPoster(host, metaForHost(host));
    var ended = host.querySelector('.bdgs-yt-ended');
    if (ended) {
      ended.hidden = true;
      ended.setAttribute('hidden', '');
    }
    host.classList.remove('bdgs-yt-is-ended');
    var playBtn = host.querySelector('.bdgs-yt-play');
    if (playBtn) {
      playBtn.addEventListener('click', function () { onPlayClick(host); });
    }
  }

  function boot() {
    var hosts = document.querySelectorAll('.bdgs-yt-host[data-bdgs-yt-key]');
    if (!hosts.length) return;
    hosts.forEach(initHostFacade);

    // Preconnect when a host approaches the viewport (does NOT create the player).
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) { preconnect(); io.unobserve(entry.target); }
        });
      }, { rootMargin: '300px 0px' });
      hosts.forEach(function (h) { io.observe(h); });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
