<script>
(function() {
  var heroTz = document.getElementById('zc-hero-time');
  if (heroTz && typeof bdgsFormatZoomTimeRange === 'function' && window.bdgsZoomClinics && window.bdgsZoomClinics.length) {
    var featured = window.bdgsZoomClinics[0];
    var tz = typeof bdgsGetSelectedZoomTimezone === 'function' ? bdgsGetSelectedZoomTimezone() : 'America/New_York';
    try {
      heroTz.textContent = bdgsFormatZoomTimeRange(new Date(featured.starts_at), new Date(featured.ends_at), tz);
    } catch (e) {}
  }
})();
</script>
