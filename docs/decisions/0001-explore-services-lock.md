# Decision 0001 — Explore Services hub locked

**Date:** 2026-07-02  
**Status:** LOCKED  
**Owner:** Charan (sign-off)

## What was locked

The **Explore Services** hub at `/services/` — single adaptive 8-card grid hub page.

## Canonical files

| Role | Path |
|------|------|
| Production HTML | `services/index.html` |
| MD mirror | `services/index.md` |
| Antigravity source | `local-html/services/index.html` (+ `local-html/services/index.md`) |

## Design decisions (frozen)

- **H1:** "Everything we do for your **Brilliant Directories** site" (36px max, matches reviews hero scale)
- **AEO lead:** Answer-first paragraph under H1 (20+ developers, setup/customization/AI/growth)
- **Layout:** 8 cards in fluid 4-column grid (desktop); cards scale down on short viewports
- **Viewport:** Main fills `100dvh − header` on large screens — footer below fold; mobile stacks naturally
- **Secondary CTA:** "Book a free 30-min Discovery Call" → inquiry modal
- **Footer review count:** Dynamic exact count via `snippets/bdgs-review-count.js`
- **FAB:** CopyPageButton (Copy/View MD, Open in ChatGPT/Claude)

## Cards (order locked)

1. Setup & Launch → `/setup/`
2. Hire a Developer → `/hire-developer/`
3. AI Development → `/grow-with-ai/`
4. Custom Projects → `/customization/`
5. Solutions Done-For-You → `/solutions/`
6. Themes → `/themes/`
7. Maintenance Plans → `/maintenance/`
8. Founder's Track (dark, Apply Only) → `/founders-track/`

## Retired variants (do not ship)

- `local-html/services.html`
- `local-html/services-v2.html`
- `local-html/services-v3.html` (superseded by `local-html/services/index.html`)

## SEO

- `data/seo-pages.json`: `/services` → `status: live`
- Schema: WebPage + ItemList + BreadcrumbList
- Audit: PASS (lock checklist complete 2026-07-02)

## Unblocks

Home §1 and §2 approval gate — **Explore Services** portion cleared. Still blocked on Reviews + Webinars + other link targets per MoM.
