# Variants (review sandbox)

Staging area for sections/pages that aren't locked yet — where we build **options** for a human to pick from.

Nothing here is production. Production pages live at the site root as `/{slug}/index.html`. Files here are deliberately kept **out** of `sitemap.xml`, `llms.txt`, and `data/seo-pages.json` (status stays out of `live`) so search engines and AI tools never see half-finished work.

## Layout

```
variants/
  <area>/                     e.g. reviews/, home/section-4-border/
    v1.html, v2.html, v3.html    the options being compared
    shimmer/                     sub-options on one page when useful
    NOTES.md                     what differs, what to judge, current lead
```

## Workflow (build -> lock -> promote)

1. Build 2–3 options here. Same **gold-standard production copy** in every option — no placeholder drift (review rule).
2. User reviews and picks one.
3. Record the pick in `../decisions/` (new numbered decision).
4. Promote the winner to its real path (`/{slug}/index.html`), run the `seo-page-audit` skill, add it to `data/seo-pages.json` as `live`, and rebuild `sitemap.xml` + `llms.txt`.
5. Delete the losing options and the variant folder once the decision is locked.

## Active tracks

- `reviews/` — Reviews page card designs v1 / v2 / v3 (+ `shimmer/`); winner also ships to Home §4 widget.
- `home/section-4-border/` — gold/shimmer border options for the Home reviews strip.
