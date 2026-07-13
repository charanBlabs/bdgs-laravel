<script>
(function() {
  if (typeof bdgsUpdateHeroScheduleTime === 'function') {
    bdgsUpdateHeroScheduleTime();
  }

  document.querySelectorAll('.zc-quote-rotator').forEach(function(rotator) {
    var quotes;
    try {
      quotes = JSON.parse(rotator.dataset.quotes || '[]');
    } catch (e) {
      return;
    }
    if (!Array.isArray(quotes) || quotes.length < 2) {
      return;
    }

    var line = rotator.querySelector('.zc-clinic-hook');
    if (!line) {
      return;
    }

    var idx = 0;
    var interval = parseInt(rotator.dataset.interval || '5500', 10);
    var fadeMs = 420;

    setInterval(function() {
      line.classList.add('zc-clinic-hook--fade-out');
      setTimeout(function() {
        idx = (idx + 1) % quotes.length;
        line.textContent = '\u201C' + quotes[idx] + '\u201D';
        line.classList.remove('zc-clinic-hook--fade-out');
      }, fadeMs);
    }, interval);
  });
})();
</script>
