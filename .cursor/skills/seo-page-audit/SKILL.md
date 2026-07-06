---
name: seo-page-audit
description: Pass/fail SEO + AI-readiness checklist for a single BD Growth Suite page. No soft language — each fail names the exact fix (which file, what to add). Use after building or editing any public page, and before calling a page "done". Triggered by "audit /page", "run page audit", "is this page done", or finishing page work.
---

# SEO Page Audit — Advanced Scoring

Hard pass/fail at sub-item level. Never "looks good" — either it passes a sub-item or you give the exact fix.

**Scoring system:** 12 checkpoints × 1.0 point each = **12.0 max**. Each checkpoint subdivides its 1.0 point into fractional weights based on the number of verifiable sub-items. A sub-item either scores its full weight (PASS) or 0 (FAIL). Final score = sum of all earned sub-item weights.

**Ship threshold:** ≥ 11.0/12.0 AND zero critical fails (marked ⚠️). Re-audit after fixes. **Two consecutive passes ≥ 11.0 = shippable.**

---

## Before Auditing

1. Read `resources/views/pages/{slug}/index.blade.php` (meta/title/schema), `content.blade.php` (body), and `resources/views/layouts/bdgs.blade.php` (shared shell).
2. Read `data/seo-pages.json` — confirm page entry, status, primary keyword, schema types.
3. Read `docs/seo-keyword-map.md` — locked keyword phrases for this page.
4. Read `public/sitemap.xml` and `public/llms.txt` — confirm page is listed.
5. Identify the page's primary keyword from the above sources.

---

## Checkpoint 1: Indexing & Discovery (1.0 pt)

**3 sub-items → 0.34 + 0.33 + 0.33**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 1a | seo-pages.json entry | 0.34 | Page row exists with status `live` and correct slug |
| 1b | sitemap.xml entry | 0.33 | `<url><loc>` present with correct slug + `<lastmod>` = today or last-edit date |
| 1c | llms.txt entry | 0.33 | Page block exists with matching url, title, content (not stale) |

---

## Checkpoint 2: Meta Tags (1.0 pt)

**10 sub-items → 0.10 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 2a | `<title>` present | 0.10 | Exists in `<head>` |
| 2b | Title format | 0.10 | Matches `{Page Title} — BD Growth Suite` pattern |
| 2c | `<meta description>` present | 0.10 | Exists with non-empty `content` |
| 2d | Description length | 0.10 | ≤160 characters (count the `content` value) |
| 2e | Description contains keyword | 0.10 | Primary keyword phrase appears in description text |
| 2f | `<link canonical>` | 0.10 | Present with correct production URL (`https://bdgrowthsuite.com/{path}`) |
| 2g | Open Graph core (og:type + og:title + og:url) | 0.10 | All three present with correct values |
| 2h | Open Graph content (og:description + og:image) | 0.10 | Both present; og:image is valid URL |
| 2i | Twitter Card (all 4: card + title + description + image) | 0.10 | All four present; card = `summary_large_image` |
| 2j | Favicon | 0.10 | `<link rel="icon">` present with valid href |

---

## Checkpoint 3: Heading & Keyword Placement (1.0 pt)

**5 sub-items → 0.20 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 3a | Exactly one `<h1>` | 0.20 | Count all H1 tags — must be exactly 1 |
| 3b | Primary keyword in H1 | 0.20 | Target phrase appears naturally in the H1 text |
| 3c | Keyword in first paragraph | 0.20 | Primary keyword within first 100 words of body content |
| 3d | H2→H3 hierarchy (no skips) | 0.20 | No H1→H3 without H2 between; no orphaned heading levels |
| 3e | H2s written as real questions | 0.20 | Question-style phrasing people actually search (not "Overview" / "Details") |

---

## Checkpoint 4: AEO — Answer Engine Optimization (1.0 pt)

**5 sub-items → 0.20 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 4a | Question-style H2s answer-first | 0.20 | Every H2 phrased as a question has a direct, quotable answer as its first sentence |
| 4b | Page core question answered in first paragraph | 0.20 | The page's primary question is answered in 1–2 sentences at the top of body content |
| 4c | FAQ section present (if applicable) | 0.20 | Pages with ≥3 Q&A pairs have a dedicated FAQ section |
| 4d | FAQ uses `FAQPage` schema | 0.20 | JSON-LD `FAQPage` block with `mainEntity` array (if FAQ exists) |
| 4e | FAQ answers are self-contained + snippet-friendly | 0.20 | Each answer makes sense standalone; ≤300 chars for featured snippet eligibility |

**Scoring note:** If the page has NO FAQ section and no question-style H2s, award 4a = 0.20, 4b = 0.20, 4c–4e = N/A (score as full 1.0). If FAQ exists, all 5 apply.

---

## Checkpoint 5: Schema / JSON-LD (1.0 pt)

**5 sub-items → 0.20 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 5a | `<script type="application/ld+json">` present | 0.20 | At least one JSON-LD block exists in the page |
| 5b | Organization schema | 0.20 | `@type: Organization` with name, url, logo |
| 5c | WebPage schema | 0.20 | `@type: WebPage` with name, url, description |
| 5d | BreadcrumbList schema | 0.20 | `@type: BreadcrumbList` with correct hierarchy (Home → current page) |
| 5e | Valid JSON (no syntax errors) | 0.20 | Parseable JSON; no trailing commas, unclosed braces, or wrong value types |

**Additional schema (adds to 5b if applicable):** Service pages → `Service` type; FAQ pages → `FAQPage`; Reviews → `aggregateRating`. Incorrect type for the page = 5b FAIL.

---

## Checkpoint 6: MD Mirror (1.0 pt)

**5 sub-items → 0.20 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 6a | Middleware on route | 0.20 | Page route is inside `ProvideMarkdownResponse` middleware group in `routes/web.php` |
| 6b | `.md` URL works | 0.20 | `/{slug}.md` returns HTTP 200 with `text/markdown` (home: `/index.md`) |
| 6c | Content is current | 0.20 | Markdown output reflects current Blade content (headings, body, CTAs) — not stale |
| 6d | Listed in llms.txt | 0.20 | `public/llms.txt` links to the `.md` URL for this page |
| 6e | No manual index.md file | 0.20 | No hand-maintained `public/{slug}/index.md` — middleware is the source |

---

## Checkpoint 7: CopyPageButton / FAB (1.0 pt)

**4 sub-items → 0.25 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 7a | Via layout | 0.25 | Page `@extends('layouts.bdgs')`; FAB from `partials/bdgs/copy-page-fab` (homepage: `$showFab = false` = N/A, full 0.25) |
| 7b | All actions functional | 0.25 | Copy MD, View MD, Open in ChatGPT, Open in Claude — wired in `copy-page-fab-scripts` |
| 7c | Keyboard accessible | 0.25 | Activatable via keyboard; Escape closes; focus management correct |
| 7d | `prefers-reduced-motion` respected | 0.25 | Animations/transitions have reduced-motion fallback |

---

## Checkpoint 8: Images (1.0 pt)

**5 sub-items → 0.20 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 8a | Every `<img>` has `alt` text | 0.20 | No empty or missing `alt` attributes (decorative images use `alt=""` explicitly) |
| 8b | At least one alt contains keyword | 0.20 | Primary keyword appears naturally in at least one image alt |
| 8c | Below-fold images: `loading="lazy"` | 0.20 | All images not in first viewport have lazy loading |
| 8d | Dimensions declared | 0.20 | `width`/`height` attributes OR `aspect-ratio` in CSS (prevents CLS) |
| 8e | OG image set | 0.20 | `og:image` meta points to a valid, page-relevant image URL |

**Scoring note:** If page has zero images, award 8a–8d full (N/A); 8e still applies (OG image required regardless).

---

## Checkpoint 9: Internal Linking (1.0 pt)

**4 sub-items → 0.25 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 9a | 3–5 internal links in body | 0.25 | Count links to other BDGS pages within main content (not nav/footer) |
| 9b | Descriptive anchor text | 0.25 | No "click here" / "read more" — anchors describe the destination |
| 9c | Links to sibling/related pages | 0.25 | At least links to home + services + one other relevant page |
| 9d | No broken internal links | 0.25 | Every internal href resolves to an existing page (no 404 targets) |

---

## Checkpoint 10: Brand Language (1.0 pt)

**5 sub-items → 0.20 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 10a | "Brilliant Directories" 3–5× | 0.20 | Full name appears 3–5 times naturally (count occurrences) |
| 10b | Never abbreviated "BD" in copy | 0.20 | No customer-facing "BD" — always full "Brilliant Directories" |
| 10c | No banned terms | 0.20 | None from banned list: "buy", "install", "users", "affordable", "seamless", "comprehensive", "robust", "cutting-edge" |
| 10d | CTA copy follows pattern | 0.20 | CTAs use verb + benefit + → (not "Submit", "Click Here") |
| 10e | Anti-fluff voice | 0.20 | Every sentence earns its place with specifics — no vague marketing speak |

---

## Checkpoint 11: AI Summary Div (1.0 pt)

**4 sub-items → 0.25 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 11a | Div present | 0.25 | `<div class="bdgs-ai-summary"` exists in the page |
| 11b | `aria-hidden="true"` | 0.25 | Hidden from screen readers (for AI agents only) |
| 11c | `style="display:none"` or equivalent | 0.25 | Not visible to human users |
| 11d | Content is accurate TL;DR | 0.25 | Summary matches current page content (services, pricing, CTAs) — not stale |

---

## Checkpoint 12: Page Load & Technical (1.0 pt)

**4 sub-items → 0.25 each**

| # | Sub-item | Weight | What to verify |
|---|----------|--------|----------------|
| 12a | No HTML syntax errors | 0.25 | Well-formed HTML; no unclosed tags, no duplicate IDs |
| 12b | No console JS errors | 0.25 | Page JavaScript executes without throwing errors |
| 12c | Fast on mobile | 0.25 | No render-blocking resources above fold; critical CSS available |
| 12d | `<html lang="en">` set | 0.25 | Language attribute present on root element |

---

## Scoring Calculation

```
Total = Σ(all earned sub-item weights across CP1–CP12)
Max = 12.0

Grade:
  11.5–12.0  →  EXCELLENT (ship immediately)
  11.0–11.4  →  PASS (shippable with minor notes)
  10.0–10.9  →  CONDITIONAL (fix fails, re-audit)
   9.0–9.9   →  NEEDS WORK (multiple fixes required)
   < 9.0     →  FAIL (significant gaps — rebuild/rework)
```

**Critical fails (⚠️):** Any fail in 1a (not in seo-pages.json), 2a (no title), 3a (wrong H1 count), 5e (broken JSON-LD), or 12a (HTML errors) blocks shipping regardless of score.

---

## Output Format

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
SEO PAGE AUDIT: /{page-slug}
Audited: {YYYY-MM-DD HH:MM}
Primary keyword: "{keyword phrase}"
Schema type: {detected type}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

SCORE: {X.XX}/12.0 — {GRADE}

━━━ CHECKPOINT BREAKDOWN ━━━

CP1  Indexing & Discovery      {0.00–1.00}/1.0
CP2  Meta Tags                 {0.00–1.00}/1.0
CP3  Heading & Keywords        {0.00–1.00}/1.0
CP4  AEO                       {0.00–1.00}/1.0
CP5  Schema / JSON-LD          {0.00–1.00}/1.0
CP6  MD Mirror                 {0.00–1.00}/1.0
CP7  CopyPageButton / FAB      {0.00–1.00}/1.0
CP8  Images                    {0.00–1.00}/1.0
CP9  Internal Linking          {0.00–1.00}/1.0
CP10 Brand Language            {0.00–1.00}/1.0
CP11 AI Summary Div            {0.00–1.00}/1.0
CP12 Page Load & Technical     {0.00–1.00}/1.0

━━━ FAILS (exact fixes) ━━━

⚠️ {critical-id} — {what failed} → {exact fix: which file, what to add/change}
✗ {sub-item-id} (-{weight}) — {what failed} → {exact fix}
✗ {sub-item-id} (-{weight}) — {what failed} → {exact fix}

━━━ WARNINGS (non-blocking) ━━━

⚡ {item} — {observation} → {recommended fix}

━━━ PASS TRACKER ━━━

Pass {N}/2 — {status message}
{If pass 1: "Fix fails above, then re-audit for pass 2."}
{If pass 2 with ≥11.0: "✓ SHIPPABLE — two consecutive passes."}
```

---

## Utility / Login / Admin Pages

These pages are **anti-indexed**. The audit flips — presence of SEO items is a FAIL:

| Check | Must be TRUE |
|--------|-------------|
| NOT in sitemap.xml | ✓ |
| NOT in llms.txt | ✓ |
| Has `<meta name="robots" content="noindex, nofollow">` | ✓ |
| No `.md` mirror file | ✓ |
| No CopyPageButton | ✓ |
| No AI summary div | ✓ |
| No JSON-LD schema | ✓ |

Score: 7 checks → each worth 0.14 (total ~1.0). Report as `ADMIN AUDIT: {X}/1.0`.

---

## Agent-Readiness Warnings (non-blocking, reported separately)

These do not affect the score but are flagged as ⚡ warnings:

- Accessibility tree: every interactive element has an accessible name
- Low CLS: width/height declared on all media elements
- Forms: labeled fields + stable URL (no JS-only routing)
- Touch targets ≥ 44×44px on mobile
- `prefers-reduced-motion` respected on animations

---

## Execution Rules

1. **Read the full page** before scoring — never score from memory or assumption.
2. **Count precisely** — "3–5 internal links" means count them. "≤160 chars" means measure the string.
3. **N/A items score full weight** — if a sub-item doesn't apply (e.g. no images → CP8a–d = full), note as N/A.
4. **Fail messages must be actionable** — always include: which file, which line/section, what exact text/tag to add.
5. **Two-pass rule** — after fixes, run the full audit again. Only `Pass 2/2 ≥ 11.0` = shippable.
6. **Never round up** — 10.99 is not 11.0. Score what you measured.

---

## Meta Tag Template (paste + fill)

```html
<title>{Page Title} — BD Growth Suite</title>
<meta name="description" content="{<=160 char summary with primary phrase}">
<link rel="canonical" href="https://bdgrowthsuite.com/{path}">
<meta property="og:type" content="website">
<meta property="og:title" content="{same as title}">
<meta property="og:description" content="{same as description}">
<meta property="og:url" content="https://bdgrowthsuite.com/{path}">
<meta property="og:image" content="https://bdgrowthsuite.com/og/{page}.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{same as title}">
<meta name="twitter:description" content="{same as description}">
<meta name="twitter:image" content="{same as og:image}">
<link rel="icon" href="/favicon.png">
```

## JSON-LD Minimum (one `<script type="application/ld+json">` per block)

```json
{ "@context": "https://schema.org", "@type": "Organization",
  "name": "BD Growth Suite", "url": "https://bdgrowthsuite.com",
  "logo": "https://bdgrowthsuite.com/logo.png" }
```
```json
{ "@context": "https://schema.org", "@type": "WebPage",
  "name": "{Page Title}", "url": "https://bdgrowthsuite.com/{path}",
  "description": "{meta description}" }
```
```json
{ "@context": "https://schema.org", "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://bdgrowthsuite.com/" },
    { "@type": "ListItem", "position": 2, "name": "{Page}", "item": "https://bdgrowthsuite.com/{path}" }
  ] }
```
FAQ pages add:
```json
{ "@context": "https://schema.org", "@type": "FAQPage",
  "mainEntity": [ { "@type": "Question", "name": "{Q}",
    "acceptedAnswer": { "@type": "Answer", "text": "{A}" } } ] }
```

---

## Related

- Keyword map: `docs/seo-keyword-map.md`
- Page registry: `data/seo-pages.json`
- Copy issues → hand to `seo-copywriter` skill
- Speed issues → hand to `seo-pagespeed` skill
- Keyword verification → hand to `seo-keyword-research` skill
