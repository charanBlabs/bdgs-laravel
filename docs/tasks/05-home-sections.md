# 05 — Homepage sections

**Goal:** finish the pending homepage sections in `local-html/index.html`. Sections 1 & 2 are **approval-gated** on the linked destination pages (tasks 01–03) being done first.

## Read first
- `local-html/index.html` (homepage = "v8") · `docs/knowledge-graph.md` §4 (locked 10-section flow) · `website-review.md`
- Runbook; `design-system.mdc`

## Locked specs per section (from website-review.md)
- **§1 Hero** — blocked until Explore Services (01) + all §1/§2 link targets (Reviews 02, Webinars 03) are done. Hero CTA copy: **"Read {x} reviews"** where `x` = live count (e.g. **176**), linking to the finished Reviews page. Not a static/wrong number.
- **§2** — separate from §1; approvable only after linked pages done. Same "Read {x} reviews" pattern where used.
- **§3** — **work LAST.** Do not prioritize.
- **§4 Reviews strip** — pending approval. **Lock the review card border** (gold/shimmer) — depends on the review-card decision from task 02. CTA: **"See all reviews" → "See {x} reviews"** (e.g. 176). **Remove the plus (+) icon** from that button.
- **§5 Statistics ribbon** — copy locked: **500+** directly served · **176+** verified reviews · **10+ years** in the directory ecosystem · **20+** developers. (Confirm exact wording of stat 1 and 4 with Charan.)
- **§6 Six teaser cards** — build with all connected destination pages in the same pass (1–2 day sprint). Apply the platform card pattern (fluid auto-fit grid, `--fs-h3` titles).

## Build order (from review)
1. §1, §2, §4 (border lock) + connected pages → 2. §6 six cards + connected pages (parallel) → 3. finalize §4 approval → 4. §3 last. §5 stats ribbon can go with the connected-page sprint.

## Acceptance (measurable, per section)
- One `<h1>` on the page (hero only); section titles use `--fs-h2`.
- "Read {x} reviews" and "See {x} reviews" use the live count; §4 button has no plus icon.
- §5 stat copy exactly as locked.
- `overflow:false` at 375/768/1280/1920; no CLS "dance"; §4 card matches the locked review-card decision.

## Verify
- Numeric browser check + screenshots per changed section.
- `seo-page-audit` ×2 on the homepage; `seo-pagespeed` (CLS).

## Done
- [ ] All gated sections' link targets (01–03) are live first
- [ ] §4 border matches `docs/decisions/` review-card decision
- [ ] Update `index.md` mirror; rebuild SEO files; update `progress.md` (flip §1–§6 statuses)
