# 01 — Explore Services page



**Goal:** finish and lock the Explore Services hub, then promote it to production. It's the priority because Home §1 (hero) and §2 can't be approved until it (and the other linked pages) exist.



**Status:** **LOCKED** (2026-07-02). See `docs/decisions/0001-explore-services-lock.md`.



## Read first

- `local-html/services/index.html` (canonical Antigravity source)

- `services/index.html` (production)

- `data/seo-pages.json` row for `/services` · `docs/seo-keyword-map.md` phrases for it

- Runbook in `README.md`, `.cursor/rules/*`



## Spec (locked)

- H1 contains the primary phrase "Brilliant Directories" (already: "Everything we do for your **Brilliant Directories** site").

- 8 service cards, single adaptive grid: Setup & Launch, Hire a Developer, AI Development, Custom Projects, Solutions Done-For-You, Themes, Maintenance Plans, Founder's Track (dark "Apply Only" card).

- Soft secondary CTA: "Book a free 30-min Discovery Call" → inquiry modal.

- Type scale matches reviews hero (36px max H1); AEO answer-first lead under H1.



## Done (lock + promote) — all complete



- [x] Add BreadcrumbList JSON-LD

- [x] Lock checklist / audit complete

- [x] Footer review count dynamic (176, exact — `bdgs-review-count.js`)

- [x] Add CopyPageButton (FAB)

- [x] Write `/services/index.md` mirror

- [x] Promoted to production `/services/index.html`; `status: live` in `data/seo-pages.json`

- [x] `python scripts/build_seo_files.py`; `docs/progress.md` updated

- [x] Decision recorded: `docs/decisions/0001-explore-services-lock.md`

- [x] Canonical route: `local-html/services/index.html`



## Retired (do not edit for production)



- `local-html/services.html`

- `local-html/services-v2.html`

- `local-html/services-v3.html` (superseded — use `local-html/services/index.html`)


