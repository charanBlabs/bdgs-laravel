---
name: seo-pagespeed
description: Measure page performance for BD Growth Suite pages and list exact, confirmed fixes (LCP, CLS, performance score). Use when a page feels slow, before launch, or when SEO Guru flags speed. Triggered by "check pagespeed", "why is this page slow", "run lighthouse", or "fix LCP/CLS".
---

# SEO Pagespeed

Measure, then give each failing item a confirmed fix. Speed matters for Google ranking and for agent reliability — a fast, stable page is easier for a browsing agent to act on.

## How to run

- **Google PageSpeed Insights** — paste the live URL at pagespeed.web.dev (test mobile and desktop).
- **Lighthouse** — Chrome DevTools (F12) -> Lighthouse tab -> Performance (and the Agentic Browsing category for agent-readiness where available).

## Report format

```
# Pagespeed: /url (mobile)
Performance: 78
LCP: 3.4s  (target < 2.5s)
CLS: 0.18  (target < 0.1)
Fixes:
- LCP: hero image is 1.8MB PNG -> serve WebP, add width/height, preload it
- CLS: fonts swap late -> add font-display: swap
- Defer non-critical JS with the defer attribute
```

## Common fixes (Laravel)

- Compress images and convert to WebP.
- Set `width`/`height` (or `aspect-ratio`) on every image to stop layout jump (CLS).
- `loading="lazy"` on below-fold images.
- `defer` non-critical JavaScript; keep only above-fold CSS blocking.
- `font-display: swap` for web fonts.
- Minify CSS/JS before launch.

## Rules

- Give real numbers from a real run — never estimate a score.
- Hand copy-length constraints (e.g. "hero cannot have autoplay video") to `seo-copywriter`.
