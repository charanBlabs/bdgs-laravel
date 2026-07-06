# 02 — Reviews page (+ Home review widget)

**Goal:** ship a production-grade Reviews page, present 3 card design variants for a human to pick, fix the known bugs, then promote the winning card to both the Reviews page and the Home §4 single-review widget.

**Status:** needs variants + fixes. Reviews page is a required destination for Home §1/§2 approval.

## Read first
- `local-html/reviews.html` (current), `local-html/reviews-v2.html` (premium `--prm-*` card)
- `data/seo-pages.json` row for `/blabs-review` (or `/reviews` — confirm slug) · keyword map
- Runbook; `design-system.mdc` (`--prm-*` gold palette is registered there)

## Spec (locked — from website-review.md)
- **Title copy is approved** — do not change wording. Fix **typography/header treatment only** to production-grade (use `--fs-h1`, consistent with platform).
- **Three variants on the Reviews page: v1 / v2 / v3** for side-by-side comparison. One will be locked, then that same card ships on Home.
  - **v3 (primary candidate):** original simpler card **minus** the certificate and the four corner squares; **gold border + shimmer**. Show **multiple shimmer styles on one page** so a human can pick one.
  - **v2:** shimmer from Home "design #2" direction — without certificate, original layout.
- **Link label:** every "View on Marketplace" / "Verify" → **"Verified on Marketplace"** (replace BOTH duplicate links). Banned: "View on Marketplace", "Verify".
- **Hover rule:** **no hover effect on the whole card** (no lift/glow/scale/border change on the container). Only the **"Verified on Marketplace" link** gets a CSS hover effect, scoped to that element.
- **Demo copy = production copy:** identical gold-standard body text + link labels across all variants. No placeholder/lorem or differing labels between demos.
- **Bug — "dancing cards":** eliminate layout shift/movement on load and interaction (reserve sizes; no late resize; stable carousel). CLS defect — must load stable.
- Review count is **176+** (not 169) — use the live/locked number.

## Build steps
1. Fix Reviews page **header typography** first (H1 via `--fs-h1`, spacing via `--section-y`). Verify no title "dance" (explicit sizes, font preload).
2. Diagnose and fix the "dancing cards" (inspect for `height` vs `min-height`, image dims, carousel reflow, animation on load). Reserve space so nothing shifts.
3. Build v1/v2/v3 cards in `docs/variants/reviews/` (one page showing all three + a `shimmer/` page showing multiple shimmer styles). Same production copy in every card.
4. Apply the label change site-wide on review cards → "Verified on Marketplace"; scope hover to that link only; remove all card-container hover effects.
5. Assemble the Reviews page using the current locked layout + the variant section for review.

## Acceptance (measurable)
- CLS ≤ 0.1 on load (no title/card dance) — verify with pagespeed/`seo-pagespeed`.
- `overflow:false` at 375/768/1280/1920; H1 46px desktop.
- All review links read "Verified on Marketplace"; no card-level hover; link hover works.
- All three variants use identical demo/production copy.

## Verify
- Numeric browser check (widths + zoom). Screenshot each variant.
- `seo-pagespeed` for CLS; `seo-page-audit` ×2.

## Done (after human picks a variant)
- [ ] Record the winning variant in `docs/decisions/NNNN-review-card.md`
- [ ] Promote winner to the Reviews page AND the Home §4 single-review widget
- [ ] Reviews page: CopyPageButton + `/…/index.md` mirror + audit ×2
- [ ] Promote to production route; `status: live`; rebuild SEO files; update `progress.md`; delete losing variants
