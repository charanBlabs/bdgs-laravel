# 06 — Site-wide quality gates (continuous)

**Goal:** the cross-cutting fixes that apply to **every** page/section. Run these as pages are built; some are standalone fixes.

## Read first
- `website-review.md` (Yakin feedback sections) · `design-system.mdc` · Runbook

## Gates & fixes (locked)

### A. Unified title/type scale
- Apply the design-system **px fluid type scale** (`--fs-*`) to every page. A page H1 = 46px desktop, same as home. No per-page/per-section title drift. On large screens titles must not feel thin/small; on small screens not oversized — the `clamp()` tokens handle this.

### B. No title/font "dance" (CLS)
- Titles render final-size immediately: explicit `clamp` px sizes + font `preconnect`/`display=swap`/preload hero weight. No late CSS overriding title size.
- Target **CLS ≤ 0.1** on every page.

### C. PageSpeed on the LIVE domain
- Run PageSpeed Insights on **every** BD page on the **real BD domain** — NOT `github.io` (those results are invalid for sign-off). Log score + Core Web Vitals per URL; fix flagged items. Use the `seo-pagespeed` skill.

### D. Responsive self-adaptation
- Every page AND every section passes the responsive matrix (320→1920, zoom 90–200%): no h-scroll, clip, or overlap. Fix breakpoint failures; document per page.

### E. Registration/schedule-complete modal
- Remove the "Please use the button below…" line.
- Show event **title + date/time + timezone** with proper spacing/hierarchy.
- **Add to Google Calendar** must open with the event in the user's **selected timezone** (e.g. India → IST). Encode start/end correctly, or use UTC + `ctz=Asia/Kolkata`. Test: select India → Add to Calendar → verify IST slot.

### F. Shared component: review count
- Footer/anywhere showing "169+" reviews → **176+** (locked). Update the shared footer once and apply across all pages (nav/footer are locked components — change deliberately, site-wide, in one pass).

### G. Link labels
- Any "View on Marketplace"/"Verify" → **"Verified on Marketplace"** wherever review cards appear (see task 02).

### H. Visual design-system alignment
- Compare each page's colors, typography, spacing, components, and focus rings against `docs/design-guidelines.html`. That file is the rendered source of truth for all design tokens. Drift from it = drift from the standard.

## Acceptance / Verify
- Per page: numeric browser check (overflow + H1 size) at 375/768/1280/1920; `seo-pagespeed` CLS ≤ 0.1; PageSpeed logged on live domain.
- Per page: visual spot-check against `docs/design-guidelines.html` (type scale, colors, button styles, spacing).
- Modal: manual test of timezone + copy.

## Done
- [ ] Type scale applied to all built pages
- [ ] CLS ≤ 0.1 site-wide; PageSpeed logged per URL (live domain)
- [ ] Modal copy + calendar timezone fixed and tested
- [ ] Review count 176+ everywhere; link labels corrected
- [ ] Visual alignment with `docs/design-guidelines.html` verified per page
- [ ] `progress.md` site-wide gates updated
