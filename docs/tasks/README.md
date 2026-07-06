# Tasks — build runbook + index

Deep, model-agnostic implementation briefs. Any agent can pick one file here and execute it end to end without extra context. Each brief follows the same shape: **Goal → Read first → Spec (locked) → Build steps → Acceptance (measurable) → Verify → Done.**

## Where files live (important)

- **Active work happens in `local-html/`** (the Antigravity working repo) — that is where the current pages/variants are.
- **The production folder is this repo** (`BD Growth Suite/`). A page moves here only when it's **locked** (approved), as `/{slug}/index.html` (see `.cursor/rules/site-structure.mdc`).
- Unfinished options live in `docs/variants/` and stay out of `sitemap.xml` / `llms.txt` / `seo-pages.json (live)`.

## The rules every task obeys (read once)

- `.cursor/rules/design-system.mdc` — tokens, **px fluid type scale**, golden-standard principles, CWV budget, responsive matrix.
- `.cursor/rules/seo-standards.mdc` — the page "definition of done" + AEO.
- `.cursor/rules/site-structure.mdc` — URLs, root-relative links, promote/lock pipeline.
- Skills: `seo-page-audit`, `seo-copywriter`, `seo-pagespeed` (invoke by name).
- Brand voice + banned/preferred terms live in the `seo-copywriter` skill.

## Standard page build procedure (do this for every page)

1. **Read first:** this task's brief, the page's row in `data/seo-pages.json`, and the phrases mapped to it in `docs/seo-keyword-map.md` (use only those; don't invent keywords).
2. **Set the foundation `:root`** — paste the fluid **px** type scale + rhythm tokens from `design-system.mdc` (`--fs-*`, `--section-y`, `--content-measure`, `--gutter`). Never size fonts in `rem` (Bootstrap 3 root = 10px).
3. **Structure:** exactly one `<h1>` (primary phrase, 46px desktop via `--fs-h1`); `<h2>`s written as real search questions; first sentence under each h2 is a direct, quotable answer (AEO). Body copy width ≤ `--content-measure`.
4. **Layout:** flow-based. Section padding = `--section-y`. Fluid container (`max-width` + `padding-inline:var(--gutter)`). Card/tile grids use `repeat(auto-fit, minmax(min(100%, <Npx>), 1fr))` so they reflow with width AND zoom. **Never** `justify-content:center` inside a `100vh` shell.
5. **States:** every interactive element has rest/hover/focus-visible/active/disabled. Coral focus ring. Respect `prefers-reduced-motion`.
6. **Media/perf:** images have width/height or aspect-ratio, `loading="lazy"` below fold, WebP via ImageKit. Fonts: preconnect + `display=swap` + preload hero weight.
7. **SEO head:** unique title, meta description ≤160 chars (with primary phrase), canonical + OG + Twitter using the **production domain** `https://bdgrowthsuite.com/{slug}/`, favicon. JSON-LD (Organization + WebPage + Breadcrumb; FAQPage if there's an FAQ) — templates in the `seo-page-audit` skill.
8. **Verify visually + numerically** (see below) at 375 / 768 / 1280 / 1920 and 100/125/150% zoom.
9. **Run `seo-page-audit`.** Fix every FAIL. Run it again. **Two clean passes = shippable.**
10. **At lock/promote only:** write the `/{slug}/index.md` mirror, add the CopyPageButton (`snippets/copy-page-button.html`), move the file to `/{slug}/index.html`, set `status: live` in `data/seo-pages.json`, run `python scripts/build_seo_files.py`, update `docs/progress.md`, delete losing variants.

## How to verify a page (numeric, not eyeballing)

```powershell
# from local-html/ (or the folder containing the page)
python -m http.server 8765
```

Open `http://localhost:8765/<page>.html` in the browser tool, then per width use CDP:

- `Emulation.setDeviceMetricsOverride` → `{width:1920,height:1080,deviceScaleFactor:1,mobile:false}` (repeat for 1280 ≈150% zoom, 768, and `{width:375,height:812,mobile:true}`).
- `Runtime.evaluate` (returnByValue) to assert, at each width:

```js
(function(){var h1=document.querySelector('h1');return JSON.stringify({
  vw:innerWidth,
  overflow:document.documentElement.scrollWidth>innerWidth,   // must be false
  h1_px:getComputedStyle(h1).fontSize                          // must be 46px on desktop
});})()
```

Pass = `overflow:false` at every width, `h1_px` = 46px at ≥1200px and no smaller than ~28px on mobile. Screenshot each for the record. Then `Emulation.clearDeviceMetricsOverride` and stop the server.

## Task index (build order from `website-review.md`)

| # | Brief | Priority | Status |
|---|-------|----------|--------|
| 01 | `01-explore-services.md` | — | **locked** |
| 02 | `02-reviews.md` | high | needs variants + fixes |
| 03 | `03-webinars.md` | high | not started |
| 04 | `04-services-redesign.md` | high | rejected — resolve scope first |
| 05 | `05-home-sections.md` | after linked pages | blocked on 01–03 |
| 06 | `06-sitewide-gates.md` | continuous | in progress |
