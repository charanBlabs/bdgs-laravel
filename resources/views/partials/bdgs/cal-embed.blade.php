{{-- Cal.com element-click embed — Discovery Call. Values from Admin → Settings. --}}
@php
  $cal = app(\App\Services\SettingsService::class)->cal();
  $calBoot = [
      'origin' => $cal['origin'],
      'namespace' => $cal['namespace'],
      'link' => $cal['link'],
      'config' => $cal['config'],
      'ui' => [
          'hideEventTypeDetails' => false,
          'layout' => $cal['config']['layout'] ?? 'month_view',
      ],
  ];
@endphp
<script type="application/json" id="bdgs-cal-boot">{!! json_encode($calBoot, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<script type="text/javascript">
  (function (C, A, L) {
    var p = function (a, ar) { a.q.push(ar); };
    var d = C.document;
    C.Cal = C.Cal || function () {
      var cal = C.Cal;
      var ar = arguments;
      if (!cal.loaded) {
        cal.ns = {};
        cal.q = cal.q || [];
        d.head.appendChild(d.createElement("script")).src = A;
        cal.loaded = true;
      }
      if (ar[0] === L) {
        var api = function () { p(api, arguments); };
        var namespace = ar[1];
        api.q = api.q || [];
        if (typeof namespace === "string") {
          cal.ns[namespace] = cal.ns[namespace] || api;
          p(cal.ns[namespace], ar);
          p(cal, ["initNamespace", namespace]);
        } else {
          p(cal, ar);
        }
        return;
      }
      p(cal, ar);
    };
  })(window, "https://app.cal.com/embed/embed.js", "init");

  (function () {
    var bootEl = document.getElementById("bdgs-cal-boot");
    if (!bootEl) return;
    var boot = JSON.parse(bootEl.textContent);
    var ns = boot.namespace;
    var origin = boot.origin;
    var link = boot.link;
    var cfg = boot.config;
    var ui = boot.ui;

    Cal("init", ns, { origin: origin });
    Cal.config = Cal.config || {};
    Cal.config.forwardQueryParams = true;
    Cal.ns[ns]("ui", ui);

    window.bdgsOpenDiscoveryCall = function () {
      var el = document.querySelector('[data-cal-link="' + link + '"]');
      if (el) {
        el.click();
        return;
      }
      if (typeof Cal === "undefined") return;
      if (Cal.ns && Cal.ns[ns]) {
        Cal.ns[ns]("modal", { calLink: link, config: cfg });
        return;
      }
      Cal("modal", { calLink: link, config: cfg });
    };
  })();
</script>
