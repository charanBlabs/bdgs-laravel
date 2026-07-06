---
name: full-lock-audit
description: Full lock audit — SEO + design system + shared components + brand voice in one pass. Produces a single SHIP / NOT READY verdict with scored sections and exact fixes. Use before locking a page, after major edits, or to recheck all live pages. Triggered by "full audit /page", "lock check /page", "lock audit /page", "recheck /page", "audit all pages", "lock check all", or "is this page ready to lock".
---

# Full Lock Audit

One command. Four domains. One verdict. Run this before locking any page or after major edits to recheck everything from scratch.

**Scoring:** 4 sections × 5.0 points each = **20.0 max**. Each section has checkpoints that subdivide its 5.0 points. A sub-item either scores its full weight (PASS) or 0 (FAIL).

**Ship threshold:** ≥ 18.0/20.0 AND zero critical fails (marked ⚠️) AND all four sections ≥ 4.0/5.0 individually. **Two consecutive passes = LOCKED.**

---

## Before Auditing

1. Read the page Blade views: `resources/views/pages/{slug}/index.blade.php`, `content.blade.php`, and `resources/views/layouts/bdgs.blade.php`.
2. Read `data/seo-pages.json` — confirm page entry, status, primary keyword, schema types.
3. Read `docs/seo-keyword-map.md` — locked keyword phrases for this page.
4. Read `public/sitemap.xml` and `public/llms.txt` — confirm page is listed (if live).
5. Read shared partials (when auditing partials or after partial changes):
   - `resources/views/partials/bdgs/header.blade.php`
   - `resources/views/partials/bdgs/footer.blade.php`
   - `resources/views/partials/bdgs/mobile-nav.blade.php`
   - `resources/views/partials/bdgs/inquiry-modal.blade.php`
   - `resources/views/partials/bdgs/copy-page-fab.blade.php`
6. Read `docs/design-guidelines.html` heading/token reference if checking type scale.

For **"audit all pages"**: loop through every page with `"status": "live"` in `seo-pages.json` and run the full audit on each. Report individual verdicts plus a site-wide summary.

---

## Section A: SEO & AI Readiness (5.0 pts)

Run the `seo-page-audit` skill internally. It scores 12 checkpoints out of 12.0. Normalize to 5.0.

**Conversion:** `Section A score = (seo-page-audit score / 12.0) × 5.0`

The SEO audit covers: indexing/discovery, meta tags, headings/keywords, AEO, schema/JSON-LD, MD mirror, CopyPageButton, images, internal linking, brand language, AI summary div, page load/technical.

**Critical fails (⚠️) from SEO audit carry through** — any ⚠️ blocks the SHIP verdict regardless of total score.

Report the SEO audit score AND the normalized Section A score. List all fails with exact fixes.

---

## Section B: Design System Compliance (5.0 pts)

**10 checkpoints → 0.50 each**

| # | Checkpoint | Weight | What to verify |
|---|-----------|--------|----------------|
| B1 | Color tokens | 0.50 | Every color traces to a `--bdgs-*` global token or a registered `--prm-*` / `--rev-*` scoped token. No unregistered hex values in inline styles or `<style>` blocks. Semantic colors (`--bdgs-success/error/warning/info`) used only for state, never as brand accents. |
| B2 | Typography tokens | 0.50 | All headings use the fluid `--fs-h1/h2/h3` tokens via `clamp()`. Body text uses `--fs-body` (16px). No per-page heading size overrides. No media-query font-size changes on headings. DM Sans loaded with `display=swap`. |
| B3 | Spacing on 4px grid | 0.50 | All margin, padding, gap values land on the 4px scale (4/8/12/16/20/24/28/32/36/40/48/64/80). No magic numbers like `17px`, `13px`, `37px`. Section padding uses `--section-y`. |
| B4 | Layout rules | 0.50 | No `height` on variable-content containers (only `min-height`). No fixed pixel widths on layout containers. Body copy ≤ 68ch (`--content-measure`). No `overflow-x: hidden` on body as a band-aid. |
| B5 | Responsive / zoom | 0.50 | Page has no horizontal scrollbar at 320/768/1024/1440/1920px widths. No clipping or overlap at 125% and 150% zoom. Side gutters use `clamp(16px, 4vw, 48px)`. Images use `width:100%` + `aspect-ratio` or intrinsic dimensions. |
| B6 | Component reuse | 0.50 | Uses existing `bdgsownv2-*` components — not duplicated or forked versions. Class names follow `bdgsownv2-block__element--modifier` pattern. Variants via modifier classes, not copy-paste. |
| B7 | Radius / shadow / z-index | 0.50 | Border radius values match the scale (4/6/8/10/12/16/20/50%). Shadows match the defined levels. Z-index values stay within the scale (base0, card1-6, sticky100, header1000). |
| B8 | Focus states | 0.50 | Every interactive element (button, link, input, select) has a visible `:focus-visible` ring (`outline: 2px solid var(--bdgs-coral)`). Mouse click suppressed via `:focus:not(:focus-visible)`. No `outline: none` without replacement. |
| B9 | Touch targets | 0.50 | All interactive elements are ≥ 44×44px on mobile. Icon-only buttons padded to meet minimum. No 32px close buttons or tiny tap zones. |
| B10 | Animation / motion | 0.50 | Hover transitions ≤ 300ms. All animations have `@media (prefers-reduced-motion: reduce)` fallback. No decorative loops. Lifts use `translateY(-1px)` or `(-2px)`. |

**⚠️ Critical:** B2 (typography tokens) is critical — wrong heading sizes are visible on every viewport and break design consistency site-wide.

---

## Section C: Shared Component Parity (5.0 pts)

**5 checkpoints → 1.0 each**

| # | Checkpoint | Weight | What to verify |
|---|-----------|--------|----------------|
| C1 | Layout + header | 1.0 | Page `@extends('layouts.bdgs')`. No inline `<header` in `content.blade.php`. `$activeNav` correct (or omitted). Partial `header.blade.php` has mega-menu, dropdowns, CTA. |
| C2 | Footer via layout | 1.0 | No inline `<footer` in `content.blade.php`. Partial has 4 columns + `bdgsownv2-footer-badge` badges. |
| C3 | Mobile nav via layout | 1.0 | No inline mobile nav in content. Partials have `bdgs-mob-hamburger` + `bdgs-mob-panel`. |
| C4 | Inquiry modal | 1.0 | No inline inquiry modal in content. `bdgsOpenInquiryModal()` in `shell-scripts.blade.php` — not `bdgsOpenModal`. |
| C5 | FAB via layout | 1.0 | Layout includes FAB partials (`cpb-fab__icon-open`). **Exception:** Homepage `$showFab = false` — score C5 as N/A (full 1.0). |

**Verification method:** Grep partials once for shell markers. Per-page: confirm `@extends('layouts.bdgs')` and no inline shell in `content.blade.php`.

**Allowed deltas (not failures):**
- `$activeNav` / `active-page` placement per page
- Logo `loading` attribute may differ
- Homepage omits FAB

**⚠️ Critical:** C1 (layout extends) and C4 (modal function name) are critical.

### Quick grep checks (partials — run once when partials change):

```
bdgsownv2-dropdown-mega    → header partial
bdgs-mob-hamburger         → mobile-nav partial
bdgs-mob-panel             → mobile-nav partial
cpb-fab__icon-open         → copy-page-fab partial
bdgsOpenInquiryModal(      → shell-scripts partial
bdgsOpenModal(             → must be ABSENT
bdgsownv2-footer-badge     → footer partial
```

### Per-page grep (content.blade.php):

```
@extends('layouts.bdgs')   → in index.blade.php (not content)
<header class="bdgsownv2-header  → must be ABSENT in content
<footer class="bdgsownv2-footer  → must be ABSENT in content
```

---

## Section D: Brand Voice & Copy (5.0 pts)

**10 checkpoints → 0.50 each**

| # | Checkpoint | Weight | What to verify |
|---|-----------|--------|----------------|
| D1 | "Brilliant Directories" usage | 0.50 | Full name appears 3–5 times naturally in body content. Never abbreviated to "BD" in customer-facing copy. |
| D2 | No banned terms | 0.50 | None of: "buy", "install", "users" (use "directory owners"), "affordable"/"cheap", "seamless", "comprehensive", "robust", "cutting-edge", "leverage", "game-changer", "delve", "elevate", "unlock", "moreover", "furthermore", "in conclusion". |
| D3 | No AI-tell patterns | 0.50 | No "In today's fast-paced world", no em-dash triplets (—...—...—), no "Whether you're X or Y", no rhetorical question openings. |
| D4 | CTA copy pattern | 0.50 | CTAs use verb + benefit + → ("Get Started →", "Explore Services →"). No "Submit", "Click Here", "Read More". At least one prominent CTA per scroll viewport. |
| D5 | Locked product names | 0.50 | Uses "Founder's Track" (not "Growth Plans"), "Express Setup" (not "Setup by Devs"), "Founder Concierge" (not "Setup with Yakin"), "Solutions (Done-For-You)" (not "61 tools"). |
| D6 | Locked verbatim lines | 0.50 | If the page includes team/about content: mantra present verbatim. If AI section: AI narrative present verbatim. Score N/A if neither context applies. |
| D7 | Persuasion arc | 0.50 | Page follows the flow: Hook → Proof → Pain → Solution → Evidence → CTA. Not every section needs all six; the page as a whole follows this order. |
| D8 | Anti-fluff voice | 0.50 | Every sentence earns its place with specifics — numbers, names, outcomes. No vague marketing speak ("we help businesses grow", "comprehensive solutions"). Check 3 random paragraphs. |
| D9 | Lead capture present | 0.50 | At least one inquiry modal trigger on the page. Global trigger text is "Get Started" or "Tell Us About Your Project". No WhatsApp as public CTA. |
| D10 | Zoom Clinics CTA (if present) | 0.50 | If Zoom Clinics are mentioned: green pulse indicator present + "Free" signal. Score N/A if Zoom Clinics not on this page. |

---

## Scoring Calculation

```
Section A (SEO):       {X.XX}/5.0   (normalized from seo-page-audit 12.0 scale)
Section B (Design):    {X.XX}/5.0
Section C (Parity):    {X.XX}/5.0
Section D (Brand):     {X.XX}/5.0
────────────────────────────────
TOTAL:                 {XX.XX}/20.0

Grade:
  19.0–20.0  →  EXCELLENT (lock immediately)
  18.0–18.9  →  PASS (lockable with minor notes)
  16.0–17.9  →  CONDITIONAL (fix fails, re-audit)
  14.0–15.9  →  NEEDS WORK (multiple fixes required)
   < 14.0    →  FAIL (significant gaps — rebuild section or page)

SHIP requires:
  ✓ Total ≥ 18.0/20.0
  ✓ Every section ≥ 4.0/5.0 individually
  ✓ Zero critical fails (⚠️)
  ✓ Two consecutive passes
```

A page that scores 19.0 total but has Section C at 3.5 does NOT ship — every section must individually clear 4.0.

---

## Output Format

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FULL LOCK AUDIT: /{page-slug}
Audited: {YYYY-MM-DD HH:MM}
Primary keyword: "{keyword phrase}"
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

VERDICT: {SHIP ✓ | NOT READY ✗}
TOTAL SCORE: {XX.XX}/20.0 — {GRADE}

━━━ SECTION SCORES ━━━

A  SEO & AI Readiness        {X.XX}/5.0  (SEO audit: {X.XX}/12.0)
B  Design System             {X.XX}/5.0
C  Shared Components         {X.XX}/5.0
D  Brand Voice & Copy        {X.XX}/5.0

━━━ CRITICAL FAILS (blocks ship) ━━━

⚠️ {id} — {what failed} → {exact fix: file, line/section, what to change}

━━━ FAILS (exact fixes) ━━━

✗ {id} (-{weight}) — {what failed} → {exact fix}
✗ {id} (-{weight}) — {what failed} → {exact fix}

━━━ WARNINGS (non-blocking) ━━━

⚡ {item} — {observation} → {suggested fix}

━━━ PASS TRACKER ━━━

Pass {N}/2 — {status}
{If pass 1: "Fix fails above, then re-audit for pass 2."}
{If pass 2 with SHIP: "✓ LOCKED — two consecutive passes. Page is production-ready."}
```

---

## Multi-Page Audit ("audit all pages")

When auditing all live pages:

1. Read `data/seo-pages.json` — get all pages with `"status": "live"`.
2. Run the full lock audit on each page.
3. After individual reports, output a summary table:

```
━━━ SITE-WIDE SUMMARY ━━━

| Page | Score | SEO | Design | Parity | Brand | Verdict |
|------|-------|-----|--------|--------|-------|---------|
| /             | 19.2 | 4.8 | 4.9 | 4.8 | 4.7 | SHIP ✓  |
| /services/    | 18.5 | 4.6 | 4.7 | 4.8 | 4.4 | SHIP ✓  |
| /webinars/    | 17.1 | 4.3 | 4.2 | 4.6 | 4.0 | FIX ✗   |
| /blabs-review/| 18.8 | 4.7 | 4.8 | 4.5 | 4.8 | SHIP ✓  |

Site status: 3/4 pages lockable, 1 needs fixes
Top priority: /webinars/ — fix B2 (heading tokens) + A (meta description)
```

---

## Execution Rules

1. **Read every file** before scoring — never score from memory or assumption.
2. **Count precisely** — "3–5 mentions" means count them. "44×44px" means measure the element.
3. **N/A items score full weight** — if a checkpoint doesn't apply (e.g. no Zoom Clinics → D10 = full), note as N/A.
4. **Fail messages must be actionable** — always include: which file, which section/line, what exact change to make.
5. **Two-pass rule** — after fixes, run the full audit again. Only `Pass 2/2 ≥ 18.0` = LOCKED.
6. **Never round up** — 17.99 is not 18.0. Score what you measured.
7. **Section minimum is hard** — a 19.5 total with one section at 3.9 = NOT READY.
8. **Grep first for parity** — run the quick grep checks in Section C before deep comparison. Fast fails save time.
9. **For "audit all"** — don't stop after the first failing page. Audit every live page and report the full picture.

---

## When to Use This vs Individual Skills

| Scenario | Use |
|----------|-----|
| Quick SEO check during editing | `seo-page-audit` alone |
| Speed issue investigation | `seo-pagespeed` alone |
| Copy writing/rewriting | `seo-copywriter` alone |
| **Before locking a page** | **`full-lock-audit`** (this skill) |
| **After major edits** | **`full-lock-audit`** |
| **Periodic recheck of all pages** | **`full-lock-audit` → "audit all pages"** |
| **Before deploying to production** | **`full-lock-audit` → "audit all pages"** |

---

## Related

- SEO detail: `seo-page-audit` skill (the 12-checkpoint audit this wraps)
- Design tokens: `.cursor/rules/design-system.mdc`
- Parity rules: `.cursor/rules/shared-components-parity.mdc`
- Brand copy: `.cursor/rules/brand-voice-and-copy.mdc`
- Visual reference: `docs/design-guidelines.html`
- Page registry: `data/seo-pages.json`
- Keyword map: `docs/seo-keyword-map.md`
