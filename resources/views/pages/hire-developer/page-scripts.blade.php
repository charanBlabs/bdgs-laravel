<script>
(function () {
  var fab = document.getElementById('bdgsFloatingCta');
  if (!fab) return;
  var shown = false;
  function onScroll() {
    var shouldShow = window.scrollY > 480;
    if (shouldShow === shown) return;
    shown = shouldShow;
    fab.classList.toggle('visible', shown);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();
</script>
