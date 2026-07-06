# How to Work With This Project — Operator's Playbook

Step-by-step instructions for every scenario you'll face. Follow the steps in order. The skills and rules handle the complex thinking — you follow the steps and approve the output.

**Stack:** Laravel 12 + Blade templates. Web root = `public/`. SEO data and docs live at project root (`data/`, `docs/`).

---

## Quick Reference: What to Say in Cursor

| I want to... | Type this in Cursor chat |
|---|---|
| **Drive a page from built → locked (full lifecycle)** | **"conductor /customization"** or **"drive /customization to lock"** |
| **Full check before locking a page** | **"full audit /services"** or **"lock check /services"** |
| **Recheck all live pages** | **"audit all pages"** or **"lock check all"** |
| Resume where I left off on a page | "resume /customization" or "what's next for /customization" |
| Check a page's current lifecycle state | "check state /customization" |
| Get SEO priorities for this week | "hi SEO guru" |
| Quick SEO-only check | "audit /services" (replace with your page path) |
| Write or rewrite page copy | "write copy for /setup" |
| Check page speed | "check pagespeed for /services" |
| Plan blog posts | "what blog posts should we write" |
| Run keyword research (rare — only once or for new markets) | "run keyword research for BDgrowthsuite" |

---

## Scenario 1: Creating a Brand New Page

### Before you start

1. Open `data/seo-pages.json` — find the row for this page (it should already be listed as `"planned"`). Note:
   - `primary_keyword` — the main phrase to use in H1 and first paragraph
   - `funnel` — awareness / consideration / conversion / trust
   - `schema` — which JSON-LD types to include
2. Open `docs/seo-keyword-map.md` — find the locked keywords for this page. Use only these. Do not invent new ones.
3. Create a git branch for the work (e.g. `feature/setup-page`).

### Build the page (Laravel)

4. **Route** — add to `routes/web.php` inside the `ProvideMarkdownResponse` middleware group:
   ```php
   Route::get('/setup', [SetupController::class, 'index'])->name('setup');
   ```
5. **Controller** — create `app/Http/Controllers/SetupController.php`:
   ```php
   return view('pages.setup.index');
   ```
6. **Blade views** — create:
   - `resources/views/pages/setup/index.blade.php` — extends layout, sets meta/title/schema
   - `resources/views/pages/setup/content.blade.php` — page body only
7. **Page CSS** — `public/css/bdgs-setup.css` (if needed beyond shell styles)
8. **Clone an existing page** as a template — copy `resources/views/pages/services/` and adapt. Every page must:
   - `@extends('layouts.bdgs')`
   - Set `@php($activeNav = 'services')` only if the page matches a top-nav item; omit for pages not in nav (e.g. `/webinars`)
   - Use `@section('title')`, `@section('meta')`, `@push('page-styles')`, `@section('content')`, `@push('page-schema')`
   - Homepage only: `@php($showFab = false)` — no FAB on home
9. **Do NOT** embed header, footer, mobile nav, inquiry modal, or FAB inline. The layout includes shared partials from `resources/views/partials/bdgs/`.

### What the page must have

10. Check every item:
    - [ ] Unique `<title>` with primary keyword (in `index.blade.php` `@section('title')`)
    - [ ] Meta description ≤ 160 chars with the primary phrase
    - [ ] `<link rel="canonical" href="https://bdgrowthsuite.com/{slug}/">`
    - [ ] OG + Twitter tags (title, description, image, url)
    - [ ] Exactly one `<h1>` containing primary keyword
    - [ ] Primary keyword in the first paragraph
    - [ ] `<h2>`s written as real questions people search — first sentence under each is a direct answer
    - [ ] JSON-LD matching the schema types from `seo-pages.json`
    - [ ] Alt text on every image; `loading="lazy"` on below-fold images
    - [ ] 3–5 internal links to other pages
    - [ ] AI summary: `@section('ai-summary')` or default from layout
    - [ ] FAB present via layout (verify on non-home pages)
    - [ ] `bdgsOpenInquiryModal()` for "Get Started" CTAs (never rename)

### After the page looks good

11. **No manual .md file needed.** The `ProvideMarkdownResponse` middleware auto-converts HTML to markdown at `/{slug}.md` (home: `/index.md`).
12. **Run the full lock audit.** In Cursor: "full audit /{slug}"
    - Checks SEO + design system + shared components + brand voice — all at once.
    - Scores out of 20.0 across 4 sections. Fix every FAIL item it reports.
    - Run again. You need **two consecutive passes ≥ 18.0/20.0** with every section ≥ 4.0/5.0 and zero critical fails.
    - (For quick SEO-only checks during building, use "audit /{slug}" — the smaller 12-point check.)

### Make the page live

13. Open `data/seo-pages.json`. Change this page's status from `"planned"` to `"live"`. Add `"md_mirror": "/{slug}.md"` (home: `/index.md`).
14. Run in terminal: `python scripts/build_seo_files.py`
    - Regenerates `public/sitemap.xml` and `public/llms.txt` to include your new page.
15. **Add internal links FROM other pages TO this page** — update 3–5 related existing Blade content files.
16. Merge branch to main after approval.

### Verify

17. `php artisan serve` — open `http://127.0.0.1:8000/{slug}/` — header, footer, FAB, modal all work. No console errors.
18. Open `http://127.0.0.1:8000/{slug}.md` — returns markdown (`Content-Type: text/markdown`).
19. Open `public/sitemap.xml` — your new URL should be listed.
20. Open `public/llms.txt` — your new page should be listed with its `.md` URL.

---

## Scenario 2: Editing an Existing Page

### Small changes (text fix, stat update, typo)

1. Edit the Blade content file: `resources/views/pages/{slug}/content.blade.php` (or `index.blade.php` for meta/schema).
2. **No .md mirror update needed** — middleware regenerates markdown from live HTML on each request.
3. Open `data/seo-pages.json` — no changes needed unless title/keyword changed.
4. Run: `python scripts/build_seo_files.py` (updates the `lastmod` date in sitemap).

### Bigger changes (new section, heading restructure, FAQ added)

1. Edit the Blade views.
2. **Check:** did you change any headings that other pages link to with anchors (`#section-id`)? If yes, update those links on the other pages too.
3. **Check:** did you add an FAQ section? If yes, add `FAQPage` JSON-LD schema.
4. **Check:** did you change pricing or offers? Update the `offers` block in JSON-LD.
5. Run: `python scripts/build_seo_files.py`
6. Run: "audit /{slug}" — make sure the page still passes.

### If you touched shared components (header, footer, FAB, modal, mobile nav)

**Stop.** These live in `resources/views/partials/bdgs/` and are included by `layouts/bdgs.blade.php`. Edit the partial once — every page updates automatically.

| Component | Canonical partial |
|-----------|-------------------|
| Header + nav | `partials/bdgs/header.blade.php` |
| Footer | `partials/bdgs/footer.blade.php` |
| Mobile nav | `partials/bdgs/mobile-nav.blade.php` + `mobile-nav-scripts.blade.php` |
| Inquiry modal | `partials/bdgs/inquiry-modal.blade.php` |
| FAB | `partials/bdgs/copy-page-fab.blade.php` + `copy-page-fab-scripts.blade.php` |
| Shared JS | `partials/bdgs/shell-scripts.blade.php` |

After updating a partial, run "audit all pages" or spot-check 2–3 pages.

---

## Scenario 3: Deleting a Page

1. Find every page that links TO this page — update or remove those links.
2. Remove the route from `routes/web.php`, delete the controller, and delete `resources/views/pages/{slug}/`.
3. Delete `public/css/bdgs-{slug}.css` if it exists.
4. In `data/seo-pages.json` — either remove the row or set status to `"planned"`.
5. Run: `python scripts/build_seo_files.py` — removes it from sitemap + llms.txt.
6. Check nav/footer partials — if this page was listed there, remove it.

---

## Scenario 4: Renaming or Moving a Page URL

1. In `data/seo-pages.json` — update the URL.
2. Rename route, controller, and view folder (e.g. `old-name` → `new-name`).
3. Inside the page: update canonical URL, OG URL, any self-referencing links.
4. Find every page that links to the old URL — update all of them.
5. Run: `python scripts/build_seo_files.py`

---

## Scenario 5: Weekly SEO Check-In

Do this once a week (or whenever Yakin asks "what's next"):

1. Open Cursor chat. Type: **"hi SEO guru"**
2. SEO Guru reads your page list, keyword map, todo list, and last audit results.
3. It gives you a prioritized list:
   - Pages to build next
   - Audit fails to fix
   - Blog posts to write
   - Speed issues to address
4. **You approve, comment, or reject each item.** Nothing happens without your OK.
5. SEO Guru updates `docs/seo-todo.md` with new items.

You can also add items to `docs/seo-todo.md` yourself anytime:
```
- Audit /setup — run page audit
- Write blog: "How to launch a directory site" — copywriter
- Fix slow images on homepage — pagespeed
```

---

## Scenario 6: Writing or Rewriting Copy

1. In Cursor: "write copy for /{slug}" or "rewrite the FAQ section on /services"
2. The copywriter skill:
   - Reads the locked keyword map for that page
   - Writes copy using only mapped keywords
   - Follows AEO pattern (direct answers under question headings)
   - Avoids banned words (seamless, robust, leverage, game-changer, etc.)
   - Uses "Brilliant Directories" in full — never "BD" in customer-facing copy
3. Review the output. If good, paste it into the Blade content file.
4. Run: "audit /{slug}" to verify.

---

## Scenario 7: Checking Page Speed

1. In Cursor: "check pagespeed for /{slug}"
2. Or manually: paste the live URL at [pagespeed.web.dev](https://pagespeed.web.dev)
3. The skill reports: Performance score, LCP (should be < 2.5s), CLS (should be < 0.1), and exact fixes.
4. Common fixes:
   - Compress images / use WebP
   - Add `width` and `height` to images (prevents layout shift)
   - Add `loading="lazy"` to below-fold images
   - Defer non-critical JS
   - Use `font-display: swap` on fonts

---

## Scenario 8: Driving a Page to Lock (Page Conductor)

The Page Conductor handles the full lifecycle in one interactive session.

### Start it

1. In Cursor: **"conductor /customization"** (or any page slug).
2. The conductor reads the page's current state and tells you where it is:
   - PLANNED → not built yet
   - IN-PROGRESS → being built
   - NEEDS-AUDIT → ready for audit
   - FIXING → has fails from last audit
   - PASS 1/2 → one clean pass, needs a second
   - PENDING-APPROVAL → waiting for Yakin
   - LOCKED → done

### What it does at each state

3. **If not built** — asks if you want to start building (create route + controller + Blade views).
4. **If ready for audit** — runs the full lock audit automatically.
5. **If fails exist** — groups them (SEO / Design / Copy / Parity / Speed), asks which to fix first, then dispatches the right sub-skill.
6. **After fixing** — re-audits. Repeats until score >= 18.0.
7. **After two passes** — asks to mark pending-approval.
8. **At approval gate** — asks if Yakin approved. If yes, runs make-live steps and locks.
9. **If you say "hold"** at any point — it exits cleanly. Next time you say "conductor /page", it picks up where you left off.

### Resume after a break

10. You can come back hours or days later. Say **"conductor /customization"** again.
11. It re-reads the state files and picks up from the correct step — no context is lost.

### Key rules

- Nothing happens without your confirmation.
- It never approves on your behalf — the approval gate always requires your explicit "yes".
- One page at a time (for batch checks, use "audit all pages").

---

## File Map — Where Everything Lives

```
BD Growth Suite/                         ← Laravel project root
├── app/Http/Controllers/                ← one controller per page (for now)
├── app/Http/Middleware/
│   └── ProvideMarkdownResponse.php      ← auto .md mirrors at /{slug}.md
├── routes/web.php                       ← all public URLs + middleware group
├── resources/views/
│   ├── layouts/bdgs.blade.php           ← master layout (header, footer, FAB, modal)
│   ├── partials/bdgs/                   ← shared shell components (edit once, all pages update)
│   └── pages/{slug}/                    ← per-page index.blade.php + content.blade.php
├── public/                              ← web root (DocumentRoot)
│   ├── css/bdgs-*.css                   ← page + shell stylesheets
│   ├── snippets/*.js                    ← review count, YouTube player, etc.
│   ├── robots.txt
│   ├── sitemap.xml                      ← auto-generated
│   └── llms.txt                         ← auto-generated
├── data/
│   └── seo-pages.json                   ← MASTER PAGE LIST — source of truth
├── scripts/
│   └── build_seo_files.py               ← regenerates public/sitemap.xml + public/llms.txt
├── docs/
│   ├── seo-keyword-map.md               ← locked keywords → page URL map
│   ├── seo-todo.md                      ← SEO backlog
│   ├── seo-system-guide.md              ← this file
│   ├── design-guidelines.html           ← visual design system reference
│   ├── progress.md                      ← what's done / in progress
│   └── archive/                         ← old reference docs
├── .cursor/
│   ├── skills/                          ← page-conductor, full-lock-audit, seo-*, etc.
│   └── rules/                           ← seo-standards, design-system, parity, etc.
├── composer.json                        ← Laravel + league/html-to-markdown
└── .env                                 ← secrets (NEVER commit)
```

---

## Rules vs Skills — What's the Difference?

**Rules** run automatically. When you edit a `.blade.php` file, Cursor reads the matching rules and enforces them in the background. You don't need to ask for them.

**Skills** run when you ask. You type a trigger phrase and Cursor reads the skill file and follows its instructions.

| Type | When it runs | Example |
|------|-------------|---------|
| Rule | Automatically on every `.blade.php` edit | `seo-standards.mdc` checks meta tags while you edit |
| Skill | When you type a trigger phrase | "audit /services" runs the `seo-page-audit` skill |

---

## The 8 Skills — When to Use Each

| # | Skill | When to use | How often |
|---|-------|-------------|-----------|
| 1 | **Page Conductor** | Drive a page through the full lifecycle (audit → fix → approve → lock) | Every page, start to finish |
| 2 | **Full Lock Audit** | Before locking a page, after major edits, rechecking all pages | Every time before lock |
| 3 | **SEO Guru** | "What should I work on next?" | Weekly, or when stuck |
| 4 | **Keyword Research** | Setting up a new page type or market | Once at start; rarely again |
| 5 | **Page Audit** | Quick SEO-only check during editing | During page building |
| 6 | **PageSpeed** | Page feels slow, or before launch | Per page, before go-live |
| 7 | **Copywriter** | Writing new copy or rewriting existing | When building page content |
| 8 | **Content Topics** | Planning blog posts or content calendar | Monthly or when Yakin asks |

### The standard flow for building one page:

```
Keyword Map (already locked) 
    → Build route + controller + Blade views 
    → Page Audit (fix SEO fails during building) 
    → PageSpeed (fix speed) 
    → Copywriter (if copy needs rewriting) 
    → Full Lock Audit (SEO + design + parity + brand — all at once) 
    → Fix any fails 
    → Full Lock Audit again 
    → Two passes ≥ 18.0 = LOCKED
```

**Or use the Page Conductor** — it runs the entire flow above as an interactive loop:
```
"conductor /page" → reads state → audits → asks what to fix → fixes → re-audits → repeats → locks
```

### Page Conductor vs doing it manually:

| Approach | When to use |
|----------|-------------|
| **"conductor /page"** | You want the full lifecycle driven for you with questions at each step |
| **"full audit /page"** | You just want the score/report — you'll fix things yourself |
| **"audit /page"** | Quick SEO-only check mid-build — not ready for full lock check yet |

### Full Lock Audit vs Page Audit — when to use which:

| Situation | Use |
|-----------|-----|
| Quick check while building | `"audit /page"` (SEO only, 12 checkpoints) |
| **Ready to lock / done building** | **`"full audit /page"` (all 4 domains, 20 pts)** |
| **Recheck all pages at once** | **`"audit all pages"` (runs full audit on every live page)** |
| Before deploying to production | `"audit all pages"` |

---

## Glossary

| Term | What it means |
|------|--------------|
| **AEO** | Answer Engine Optimization — write so AI can quote your first sentence under each heading |
| **.md mirror** | Auto-generated markdown at `/{slug}.md` via `ProvideMarkdownResponse` middleware — no hand-maintained files |
| **FAB / CopyPageButton** | The floating button: Copy page, View MD, Open in ChatGPT, Open in Claude — in `partials/bdgs/copy-page-fab.blade.php` |
| **JSON-LD** | Invisible data block in your page that tells Google what the page is about |
| **Keyword map** | Locked file (`docs/seo-keyword-map.md`) — search phrases mapped to page URLs |
| **seo-pages.json** | Master list of every page: URL, title, keyword, status (live/planned) |
| **North star** | The business metric (leads, signups) — set in `seo-pages.json` |
| **Donor page** | `resources/views/pages/services/` — clone this folder structure to start a new page |

---

## Production Deployment

1. `composer install --no-dev --optimize-autoloader`
2. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
3. Web server `DocumentRoot` = `public/`
4. `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://bdgrowthsuite.com`
5. Static assets (`/css/`, `/snippets/`) served directly by the web server
6. No `index.html` files — Laravel routing handles all pages
7. SEO files (`sitemap.xml`, `llms.txt`, `robots.txt`) served from `public/`

---

## Things That Must Never Change (Without Yakin's OK)

These are locked in `.cursor/rules/locked-resources.mdc`:

- Primary color: `#E74D56` (coral)
- Secondary color: `#95256E` (purple)
- Font: DM Sans
- CSS prefix: `bdgsownv2-`
- Framework: Bootstrap 3 (not 4 or 5)
- Header nav structure (6 items + CTA)
- Footer 4-column layout
- "Get Started" = opens inquiry modal (not a page)
- Modal function name: `bdgsOpenInquiryModal()` (never rename)
- Service taxonomy: Services / Solutions / Tools / Themes

---

## Owner Tasks (Need Yakin — Cursor Can't Do These)

- [ ] Lock the keyword map (`docs/seo-keyword-map.md`) — review and approve
- [ ] Google Search Console verification (HTML meta tag or DNS)
- [ ] Real 1200x630 social share image (for OG tags)
- [ ] Google Analytics ID
- [ ] Final approval on each page before it goes live

---

*Original email from Yakin: `docs/archive/seo-ai-agents-guide-2026-07-01.md`*
*Last updated: 2026-07-06 — Laravel Blade migration*
