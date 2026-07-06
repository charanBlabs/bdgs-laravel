<!-- JS -->
<script>
(function () {
  var origin = window.location.origin;
  var path = window.location.pathname.replace(/\/index\.html$/, "/");
  var mdPath = path === "/" ? "/index.md" : path.replace(/\/$/, "") + "/index.md";
  var pageUrl = origin + (path === "/" ? "/" : path);
  var mdUrl = origin + mdPath;
  var q = encodeURIComponent("Read " + pageUrl + " and answer my questions about it.");

  var fab = document.querySelector(".cpb-fab");
  if (!fab) return;
  var trigger = fab.querySelector(".cpb-fab__trigger");
  var primaryBtn = fab.querySelector('[data-cpb="copy-primary"]');
  var menuCopyBtn = fab.querySelector('[data-cpb="copy"]');

  fab.querySelector('[data-cpb="view"]').href = mdUrl;
  fab.querySelector('[data-cpb="chatgpt"]').href = "https://chatgpt.com/?q=" + q;
  fab.querySelector('[data-cpb="claude"]').href = "https://claude.ai/new?q=" + q;

  function setOpen(open) {
    fab.setAttribute("data-cpb-state", open ? "open" : "closed");
    trigger.setAttribute("aria-expanded", open ? "true" : "false");
  }

  trigger.addEventListener("click", function (e) {
    e.stopPropagation();
    setOpen(fab.getAttribute("data-cpb-state") !== "open");
  });

  document.addEventListener("click", function (e) {
    if (!fab.contains(e.target) && fab.getAttribute("data-cpb-state") === "open") {
      setOpen(false);
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && fab.getAttribute("data-cpb-state") === "open") {
      setOpen(false);
      trigger.focus();
    }
  });

  var copyIcon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>';
  var checkIcon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>';

  function flashCopied(btn, html) {
    btn.innerHTML = html;
    setTimeout(function () {
      if (btn === primaryBtn) {
        btn.innerHTML = copyIcon + '<span class="cpb-fab__primary-label">Copy page</span>';
      } else {
        btn.innerHTML = copyIcon + " Copy page as Markdown";
      }
    }, 2000);
  }

  function copyMarkdown(btn, isPrimary) {
    fetch(mdUrl)
      .then(function (r) { if (!r.ok) throw new Error("no mirror"); return r.text(); })
      .then(function (text) { return navigator.clipboard.writeText(text); })
      .then(function () {
        if (isPrimary) {
          flashCopied(btn, checkIcon + '<span class="cpb-fab__primary-label">Copied!</span>');
        } else {
          flashCopied(btn, checkIcon + " Copied!");
        }
      })
      .catch(function () { window.open(mdUrl, "_blank", "noopener"); });
  }

  primaryBtn.addEventListener("click", function () {
    copyMarkdown(primaryBtn, true);
  });

  menuCopyBtn.addEventListener("click", function () {
    copyMarkdown(menuCopyBtn, false);
  });
})();
</script>
