# BD Growth Suite — Progress

Living status tracker. Update every session. This is state only — the "why" lives in `decisions/` and `knowledge-graph.md`; the source review is `website-review.md`.

Legend: `not-started` · `in-progress` · `needs-options` (staged in `variants/`) · `blocked` · `pending-approval` · `locked` (approved + promoted).

Last updated: **2026-07-04** (added `/customization/` from Antigravity index-v2; home, services, reviews, webinars live)

---

## System setup (email Phase 1 Part A)

| Item | Status | Notes |
|------|--------|-------|
| SEO skills (6) | locked | seo-guru + keyword-research/page-audit/pagespeed/copywriter/content-topics — self-contained |
| Rules (6) | locked | `seo-standards`, `site-structure`, `design-system` (incl. `--prm-*`), `shared-components-parity`, `brand-voice-and-copy`, `locked-resources` |
| Base files | locked | robots.txt, sitemap.xml, llms.txt, index.md, `data/seo-pages.json`, CopyPageButton v4 |
| Keyword map | **next** | Import the "done & logged" research into `docs/seo-keyword-map.md`, then Yakin locks |
| Production sync | **done** | Jul 4 — homepage + webinars + snippets synced; services/reviews updated to Antigravity lock |

---

## Homepage sections (source = `website-review.md`)

| § | Section | Status | Notes / gate |
|---|---------|--------|--------------|
| 1 | Hero | **locked** | Yakin sign-off Jul 3 — Explore Services → `/services/`; Zoom Clinic modal; links locked (#21–22 ☑) |
| 2 | CEO endorsement | **locked** | Yakin sign-off Jul 3 — Read {x} reviews → `/blabs-review/`; webinars link (#21–22 ☑) |
| 3 | — | not-started | Deferred — work **last** (#29) |
| 4 | Reviews strip | pending-approval | **Card locked** (#13, #27 ☑): v3 · Card D trophy shine; CTA done (#28); section sign-off (#33) after §6 destinations |
| 5 | Stats ribbon | **locked** | Copy + dynamic review count (#30 ☑): 500+ directly served · 176 API · 10+ years · 20+ devs |
| 6 | Six teaser cards | not-started | Build with all connected destination pages, 1–2 day sprint (#31) |
| 7+ | Later sections | not-started | Follow the locked 10-section flow in `knowledge-graph.md` §4 |

## Connected / destination pages

| Page | Status | Notes |
|------|--------|-------|
| Explore Services | **locked** | `/services/` — approved Jul 4 (#7 ☑). Decision `0001-explore-services-lock.md` |
| Services (redesign) | **locked** | Same as Explore Services — approved Jul 4 |
| Reviews | **in-progress** | `/blabs-review/` — unlocked for re-audit Jul 4; Card D trophy shine (Decision 0003); gold `#a88632` |
| Webinars | **locked** | `/webinars/` — First session / Follow-up (Decision 0002); custom player; #24–#26 ☑ |
| Custom Projects | **pending-approval** | `/customization/` — ported from Antigravity `index-v2.html`; shell from services donor; not locked yet |

## Site-wide quality gates (apply to every page + section)

| Gate | Status | Notes |
|------|--------|-------|
| Responsive self-adaptation | **done (built pages)** | Home, `/services`, `/blabs-review`, `/webinars` (#4 ☑). Remainder audit-on-build |
| Unified title/type scale | **done (built pages)** | Fluid tokens H1–H4 + lead/body (#5 ☑). Remainder audit-on-build |
| CLS / title dance | **done (built pages)** | `display=swap`, min-height title reserve (#17 ☑). Remainder audit-on-build |
| PageSpeed (#18) | **on hold** | Post-deploy on live BDGS domain (custom stack — not github.io, not BD) |
| SEO Part 1 enforcement | **done (built pages)** | Skills + rules; home + services + reviews + webinars (#1–2 ☑). Remainder audit-on-build |
| Registration/schedule modal | **done** | Homepage modal (#19, #20) |
| Review count + dynamic CTAs | **done** | `#bdgs-footer-reviews-link`, hero/proof CTAs, §5 stat count (#23, #28, #30) |

## Review variant tracks (stage in `variants/`, then lock -> promote)

- `variants/reviews/` — **locked** Card D trophy shine → promoted to Reviews + Home §4 (#13 ☑)
- ~~`variants/reviews/shimmer/`~~ — pick complete (Card D)
- ~~`variants/home/section-4-border/`~~ — same lock applied to Home §4 (#27 ☑)

## Platform / deploy (not started — #34–#37)

| # | Item | Status |
|---|------|--------|
| 34 | WAMP + Apache/PHP 8.1/MySQL skeleton for cPanel | not-started |
| 35 | GitHub `main` + `develop` branches | not-started |
| 36 | Push / release rules | not-started |
| 37 | GitHub Actions → cPanel deploy | not-started |
