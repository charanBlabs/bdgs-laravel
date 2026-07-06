# BDGS website review 1st July — MoM July 1, 2026

**Follow-up meeting** on prior BDGS work (Jun 13 v2 plan, Jun 28 services/reviews agreements). **No brand-new workstream today** — all items below roll forward from past meetings + today’s review.

_Attendees: Charan · Priyanka (CC) · Yakin (CC) · Started 18:51 IST_

---

## Charan — accountability snapshot (all BDGS meetings combined)

_Action board last updated: **Jul 4, 2026**_

| Metric | Count |
|--------|------:|
| **Action items — this email (Jul 1 review)** | 37 |
| **Done (☑)** | 27 |
| **In progress (◐)** | 2 (#3, #32) |
| **On hold (⏸)** | 1 (#18 — post-deploy PageSpeed) |
| **Not started (☐)** | 7 |
| **Due Jul 3** | 33 |
| **Due Jul 4 (platform + GitHub deploy)** | 4 (#34–#37) |
| **Post-deploy (after #34–#37)** | 1 (#18) |
| **Deferred (audit-on-build)** | #2 / #4 / #5 / #6 / #17 remainder — run when each new page ships |
| **Open (not closed)** | 10 |

**Prior meeting carry-over (still open)**

| Source | Theme | Status |
|--------|-------|--------|
| **Jun 28 agreement** | Services page design | **Approved** (#7 ☑ Jul 4) — [preview](https://businesslabshq.github.io/bdgs-website-v2-drafts/services/) |
| **Jun 13 BDGS v2 board** | Home sections 1–15, page inventory, responsive sign-off | **Incomplete** — design consistency not production-ready |
| **Jul 1 (earlier)** | SEO Part 1 skills + keyword/URL logs | **Skills locked** (#1 ☑) · **built-page SEO audit** (#2 ☑) · keyword map lock still open (#3 ◐) |
| **Jul 4** | SEO + responsive on built pages | **#2 / #4 ☑** for home, `/services`, `/blabs-review`, `/webinars` — remainder deferred until each page is built |
| **Jul 2 decision** | Explore Services hub | **Locked + on drafts** at `/services/` — [preview](https://businesslabshq.github.io/bdgs-website-v2-drafts/services/) |
| **Jul 3 push** | Reviews page | **LOCKED & FINAL Jul 3** — Card D trophy shine; lighter gold frame; seamless shimmer loop; CLS fix + title polish (#8–9 ☑) — [preview](https://businesslabshq.github.io/bdgs-website-v2-drafts/blabs-review/) |
| **Jul 3** | Webinars page | **LOCKED Jul 3** — custom player (facade, controls, mobile tap, quality Auto, end CTAs); video IDs #205/#213/#136; Yakin sign-off complete (#24–#26 ☑) — David source-file outreach **not needed** (nocookie embed clean) — [preview](https://businesslabshq.github.io/bdgs-website-v2-drafts/webinars/) |
| **Jul 3** | Zoom registration modal | **Done** on homepage (#19, #20 ☑) |
| **Jul 3 (eve)** | Platform pivot | **Decision locked** — site will **not** ship inside Brilliant Directories; custom stack on **Apache + PHP 8.1 + MySQL**, cPanel production, WAMP local (#34 ☐) |
| **Jul 4** | PageSpeed (#18) | **On hold** — no live BDGS domain yet (outside BD); run after cPanel deploy **or** on an equivalent public URL (staging/custom domain), never `github.io` |
| **Today review** | Home Sections 1–6 | **§1/§2 ☑ Jul 3** — Yakin sign-off; §6 teaser cards next (#31 overdue) |

**This week focus (Yakin) — as of Jul 3 (eve)**

1. **Reviews:** **LOCKED & FINAL Jul 3** — Card D trophy shine, lighter gold, seamless shimmer (#8–9 ☑)
2. **Webinars:** **LOCKED Jul 3** — custom player + mobile controls; David outreach closed (#24–#26 ☑)
3. **Home §1/§2:** **☑ signed off Jul 3** — hero + CEO endorsement; links → `/services`, `/blabs-review`, `/webinars` (#21–22 ☑)
4. **Home §6:** six teaser cards + connected pages (#31, overdue)
5. **Overdue:** ~~§4 border lock (#27)~~ **done** · §6 cards (#31) · partial Jul 2 gate (#32)
6. **Platform base:** WAMP + Apache/PHP 8.1/MySQL skeleton for cPanel (#34 ☐)
7. **Jul 4 — GitHub + deploy:** `main`/`develop` wiring, push rules, GitHub Actions → cPanel (#35–#37 ☐)
8. **PageSpeed (#18):** **⏸ hold** — after deploy (or equivalent public domain); not on BD site / not github.io
9. **SEO / responsive / type / CLS (#2, #4, #5, #6, #17):** **☑ built pages** — remaining inventory = audit-on-build when each page ships
10. **Services (#7):** **☑ approved Jul 4**

---

## Decisions Locked

- **SEO Part 1** (skills setup + on-page requirements) applies to the **home page**, **every existing page**, and **all new pages** going forward — no exceptions.
- **#2 / #4 scope (Jul 4):** closed for **built/locked pages** (home, `/services`, `/blabs-review`, `/webinars`). Remaining planned inventory is **deferred** — run SEO Part 1 audit + responsive audit **when each page is built** (not before).
- **Keyword research** is complete and **logged**.
- **Page names and URLs** are **logged**.
- **360° content lock:** all current BDGS content is planned; all design is **listed and locked**.
- **Deletion vs keep-live:** whatever must be **deleted** is logged; whatever stays **live** is locked.
- **Future content:** when new pages/content appear, their information can be **locked** into the same inventory.
- **Responsive design is mandatory:** every page and **every section** must self-adapt cleanly across screen sizes — not acceptable to ship breakpoint-inconsistent layouts.
- **Typography/title scale (#5):** production fluid scale applied on **built pages** Jul 4 (home, `/services`, `/blabs-review`, `/webinars`); remainder audit-on-build.
- **Services page design (#7):** **approved Jul 4** — locked at `/services/`.
- **Reviews page title copy is correct** — title **text/replacement** is approved; **title typography, sizing, and page header treatment must be production-great** (not just “OK”).
- **Three review design variants (v1 / v2 / v3)** to be built on Reviews page for comparison; **one variant locks** after review, then **same chosen design ships on Home page**.
- **Reviews v3 direction:** simplified original card (no certificate, no four corner squares) + **gold border** + **shimmer effect** (multiple shimmer styles demoed on one page).
- **Review card hover:** **no hover effect on the whole card** — only the **“Verified on Marketplace”** link inside the card gets a CSS hover effect.
- **Link label locked:** **“View on Marketplace”** → **“Verified on Marketplace”** everywhere (replace both duplicate links).
- **Demo copy = production copy:** review-page demos must use **identical gold-standard text** across variants — no “Verify” / “View on Marketplace” drift; inconsistent labels shake confidence.
- **No title/font “dance” during or after page load** — layout shift (CLS) on title size is a defect; must load stable. Measure with **Google PageSpeed** on **every page** (not a sample) on the **real BDGS production/staging URL** (custom stack on cPanel — **not** Brilliant Directories, **not** `github.io`). **#18 on hold** until post-deploy or an equivalent public domain is available.
- **Registration complete modal (schedule done):** **shipped Jul 3** — removed “Please use the button below…” copy; compact success card with title + session/time + timezone; **Add to Google Calendar** uses IST anchor (6:30–7:30 PM Tue/Thu) + `ctz=` for selected timezone (verified).
- **Home page Section 1 (hero):** **☑ approved Jul 3** — Yakin sign-off; Explore Services → `/services/`; Zoom Clinic modal; link targets locked.
- **Home page Section 2 (CEO endorsement):** **☑ approved Jul 3** — Yakin sign-off; Explore Services, Read {x} reviews, webinars link; CEO embed unchanged.
- **Hero CTA copy:** **“Read {x} reviews”** — **x** = live review count (e.g. **176** today); not hard-coded wrong text.
- **Reviews destination page** — **built and pushed Jul 3** to drafts repo (`/blabs-review/`) for Yakin approval; Card D locked; **#8–9 polish shipped Jul 3** (static v3 skeletons, grid height reserve, title gradient + font preload).
- **Explore Services page** — **locked + on drafts** at `/services/` (Jul 3).
- **Webinar page(s):** **First session + Follow-up session** labels locked (Decision 0002) — **not** Part 1/2 or Season/Episode; page built on `/webinars/`; custom player + clean nocookie embed locked (#24–#26 ☑); David source-file fallback not needed.
- **YouTube embeds:** **no “Watch on YouTube”**, **no post-video YouTube UI**, **no ads** — users stay on BDGS; self-host / unlisted re-upload if public embed cannot be cleaned.
- **Home Sections 1 + 2 approval gate:** **Explore Services page first** (priority) — plus **all linked destination pages** (Webinars, Reviews, and every page Sections 1–2 link to) must be done before Sections 1 & 2 can be **approved**.
- **Home Section 3:** deferred — **work last**.
- **Home Section 4:** **card locked Jul 3** — v3 · **Card D trophy shine** (#13, #27 ☑); CTA **See {x} reviews** (#28 ☑); finalize section approval after destinations (#33).
- **Home Section 5 (statistics ribbon):** copy locked — **500+** directly served · **176** verified reviews (exact count, dynamic from API) · **10+ years** in directory ecosystem · **20+** developers (final stat line — verify wording with Charan).
- **Home Section 6:** **six teaser cards** — build alongside **all connected destination pages** (1–2 day sprint; Yakin + Charan).
- **Work order:** **Tomorrow (Jul 2):** Sections **1, 2, 4** + connected pages → **Section 6** six cards + connected pages in parallel → **then** close **Section 4** fully (same link targets). **Section 3** still last.
- **Platform pivot (Jul 3 eve):** BDGS redesign will **no longer** be implemented inside **Brilliant Directories**. Production target = **custom HTML/CSS/Bootstrap 3** (existing design system) + **PHP 8.1** + **MySQL** + **JS/AJAX** (+ **Redis** or other stack pieces as needed), hosted on **cPanel** (Apache). Local dev base = **WAMP** (Apache + PHP 8.1 + MySQL) — mirror cPanel constraints before deploy (#34).

## Action Board

| # | Owner | Action | Due | Status |
|---|-------|--------|-----|--------|
| 1 | Charan | Set up SEO-related Cursor skills for BDGS (Part 1 checklist + enforcement on new pages) | Jul 3 | ☑ |
| 2 | Charan | Audit home page + all existing pages against SEO Part 1; fix any gaps | Jul 3 | ☑ Built pages (home, `/services`, `/blabs-review`, `/webinars`) — H2 ids, schema, AI summary, MD mirrors. **Remainder deferred:** audit each new page when built |
| 3 | Charan | Confirm keyword research log + page-name/URL log are complete and linked in project docs | Jul 3 | ◐ |
| 4 | Charan | Audit every page + every section for responsive self-adaptation (mobile → desktop); document/fix breakpoint failures | Jul 3 | ☑ Built pages (home, `/services`, `/blabs-review`, `/webinars`) — fluid H2, footer/padding, webinars hero/CTA. **Remainder deferred:** responsive pass when each new page is built |
| 5 | Charan | Define and apply a single production-ready title/heading scale (H1–H6 + section titles) across all BDGS pages | Jul 3 | ☑ Built pages (home, `/services`, `/blabs-review`, `/webinars`) — fluid tokens H1–H4 + lead/body. **Remainder deferred:** apply when each new page is built |
| 6 | Charan | Reconcile pages/design items from last meeting notes — close gaps; raise quality to production-ready consistency | Jul 3 | ☑ Built pages — shared type tokens, letter-spacing, section H2/H3, modal titles aligned. **Remainder deferred:** when each new page is built |
| 7 | Charan | Redesign Services page to match Jun 28 agreed design — current version rejected | Jul 3 | ☑ Approved Jul 4 |
| 8 | Charan | Fix Reviews page “dancing cards” layout/animation issue | Jul 3 | ☑ |
| 9 | Charan | Polish Reviews page title + header typography (font, size, weight, spacing) to production-great standard — copy already approved | Jul 3 | ☑ |
| 10 | Charan | Build Reviews v1/v2/v3 on Reviews page — three side-by-side or stacked design options | Jul 3 | ☑ |
| 11 | Charan | Prototype Home-page single-review shimmer (gold border + pinkish-red logo tone); show multiple shimmer variants on one page | Jul 3 | ☑ |
| 12 | Charan | Build Reviews v3: strip certificate + four corner squares; gold border + shimmer (port from Home prototype) | Jul 3 | ☑ |
| 13 | Charan | Lock one review design (v1/v2/v3 pick) — then apply same to Home page single-review carousel | Jul 3 | ☑ (Card D locked final Jul 3 — Reviews + Home §4) |
| 14 | Charan | Rename both review links to **Verified on Marketplace**; remove all **View on Marketplace** / **Verify** variants | Jul 3 | ☑ |
| 15 | Charan | Remove card-level hover effects; apply CSS hover **only** on the Verified on Marketplace link | Jul 3 | ☑ |
| 16 | Charan | Unify review demo copy across v1/v2/v3 — same production-grade text on every variant | Jul 3 | ☑ |
| 17 | Charan | Fix title/font layout shift on load on **every page** (title dances during + after load); eliminate CLS site-wide | Jul 3 | ☑ Built pages — `display=swap` (not optional — keeps DM Sans), min-height title reserve, no media-query font-size overrides. **Remainder deferred:** when each new page is built |
| 18 | Charan | Run Google PageSpeed on **every BDGS page** on the **live BDGS domain** (custom stack — **not** BD site, **not** github.io); log scores + Core Web Vitals (esp. CLS); fix per page | Post-deploy (after #34–#37) | ⏸ Hold — no production/staging domain yet; unblock after cPanel deploy **or** equivalent public URL (staging/custom domain) that PSI can crawl like prod |
| 19 | Charan | Fix registration-complete / schedule-done modal: remove “please use the button below…”; title + timing + timezone with proper gap | Jul 3 | ☑ |
| 20 | Charan | Fix Add to Google Calendar URL so event time matches selected timezone (e.g. IST when user in India) | Jul 3 | ☑ |
| 21 | Charan | **Priority:** ship **Explore Services page** + all Section 1/2 link targets (Webinars, Reviews, connected pages) — then seek Sections 1 & 2 approval | Jul 3 | ☑ (Yakin sign-off Jul 3 — §1 Hero + §2 CEO; links pushed to home-hero-v8) |
| 22 | Charan | Complete Home **Section 2** — approve only after linked pages done (see #21) | Jul 3 | ☑ (Yakin sign-off Jul 3 — same pass as #21) |
| 23 | Charan | Hero CTA: **Read {x} reviews** with **x** = dynamic review count (e.g. 176); link must land on finished Reviews page | Jul 3 | ☑ |
| 24 | Charan | Webinar page(s): **First session + Follow-up session** on `/webinars/` (Decision 0002 — not Part 1/2); browser verify + Yakin sign-off | Jul 3 | ☑ (locked Jul 3 — custom player, mobile controls, end CTAs) |
| 25 | Charan | Strip YouTube embed chrome: no **Watch on YouTube**, no end-screen/suggested junk, **no ads** — keep users on site | Jul 3 | ☑ |
| 26 | Charan | If clean embed impossible: email **David** directly (skip Yakin + Cici) for source file; re-host unlisted/self-hosted embed | Jul 3 | ☑ (not needed — nocookie embed clean; #24–25 locked) |
| 27 | Charan | Home **Section 4:** lock review card **border** treatment (shimmer/gold) — target **Jul 2** | Jul 3 | ☑ |
| 28 | Charan | Home **Section 4:** rename **See all reviews** → **See {x} reviews**; remove **plus icon** from button | Jul 3 | ☑ |
| 29 | Charan | Home **Section 3** — schedule **last** (after Sections 1, 2, 4, 6 path clear) | Jul 3 | ☐ |
| 30 | Charan | Home **Section 5:** implement statistics ribbon with locked copy (500+ / **176 dynamic** / 10+ / 20+) | Jul 3 | ☑ |
| 31 | Charan | Home **Section 6:** build **six teaser cards** (1–2 days) — update **all connected pages** in same sprint | Jul 3 | ☐ |
| 32 | Yakin + Charan | **Jul 2:** Sections **1, 2, 4** + every connected destination page — SEO/skills baseline ready | Jul 3 | ◐ (§1–2 ☑ Jul 3; §4 border ☑; §6 + §4 final approval still open) |
| 33 | Charan | After Section 6 + connected pages done → **finalize Section 4** approval (links to same destinations) | Jul 3 | ☐ |
| 34 | Charan | **Platform base setup:** WAMP local (Apache + PHP 8.1 + MySQL) + project skeleton for cPanel deploy — HTML/CSS/Bootstrap 3 front-end, PHP back-end, JS/AJAX, Redis (or equivalent) as needed; **not** Brilliant Directories — document env, vhost, `.htaccess`, DB config, and folder layout so locked pages can migrate cleanly | Jul 4 | ☐ |
| 35 | Charan | **GitHub branches:** wire `main` + `develop` on BDGS repo — default branch `develop`, branch protection on `main`, remote/origin layout; mirror patterns from BusinessLabsHQ **automateflow** (work folder) | Jul 4 | ☐ |
| 36 | Charan | **Push / release rules:** all day-to-day work pushes to `develop` only; production = merge `develop` → `main` **or** run `deploy-prod-commands` workflow; document in repo README + `.github/` (same discipline as automateflow) | Jul 4 | ☐ |
| 37 | Charan | **GitHub → cPanel deploy:** GitHub Actions workflow(s) — deploy on `main` push (or `deploy-prod-commands` dispatch); cPanel SSH/FTP secrets, env, target path, post-deploy smoke check; full wiring so prod deploy is GitHub-handled, not manual | Jul 4 | ☐ |

_Status: ☐ Not started · ◐ In progress · ⏸ On hold · ☑ Done_

**Jul 3 notes (status detail)**

| # | Notes |
|---|--------|
| 1 | Six SEO skills + three rules locked in production workspace (`docs/progress.md`). |
| 2 | **☑ Jul 4** — SEO Part 1 closed for **built** pages only (home, `/services`, `/blabs-review`, `/webinars`). Planned inventory **deferred** — audit-on-build when each page ships (skills/rules already enforce on new pages). |
| 3 | `data/seo-pages.json` + `docs/seo-keyword-map.md` exist; map is **DRAFT** — Yakin lock still pending. |
| 4 | **☑ Jul 4** — responsive audit closed for **built** pages only (same four). Planned inventory **deferred** — responsive pass when each page is built. |
| 8–12 | **Locked Jul 3:** v3 · Card D trophy shine — promoted to live grid + Home §4 (Decision 0003). |
| 13 | Yakin locked **Card D — trophy shine**; applied to Reviews + Home §4 carousel. |
| 27 | Same lock — `rev-v3--shimmer-d` on Home §4 carousel + Reviews live grid. |
| 28 | Shipped Jul 3 — `#bdgs-proof-reviews-link` → “See {x} reviews →” via `bdgs-review-count.js`; no plus icon on button. |
| 23 | Shipped Jul 3 — `#bdgs-hero-reviews-btn` → “Read {x} reviews” (CEO section); same snippet + pushed to drafts. |
| 30 | Shipped Jul 3 — §5 stats ribbon: locked labels + `#bdgs-stat-reviews-count` dynamic; “Directories Directly Served” label. |
| 21–22 | **☑ Jul 3 (eve)** — Yakin sign-off Home §1 Hero + §2 CEO endorsement; CTAs → `/services`, `/blabs-review`, `/webinars`; dynamic review count via `bdgs-review-count.js`; canonical → home-hero-v8. |
| 24–26 | **☑ Jul 3–4** — Webinars locked (custom player, no YouTube chrome/ads). #26 contingency closed Jul 4: David source-file email **not required** — `youtube-nocookie` embed is clean. |
| 18 | **⏸ Hold Jul 4** — site ships **outside** Brilliant Directories (custom Apache/PHP/cPanel). No live BDGS domain yet; `github.io` invalid for sign-off. Resume **after #34–#37 deploy**, or earlier on an **equivalent public URL** (staging / custom domain) that Google PageSpeed can crawl like production. Local Lighthouse OK for interim CLS checks only — not a substitute for #18 sign-off. |
| 5–6, 17 | **☑ Jul 4** — Built pages only (home, `/services`, `/blabs-review`, `/webinars`): shared fluid type tokens (H1–H4, lead, body); section/card/modal headings wired to tokens; `font-display: swap` (optional reverted — mismatched system fonts); title `min-height` reserve; removed media-query `font-size` overrides that caused title dance. Remainder deferred until each new page is built. |
| 7 | **☑ Jul 4** — Services page **approved** (was rejected Jun 28). |
| 32 | Overdue Jul 2 — SEO baseline ready; §1–2 closed Jul 3; §6 + §4 final approval still open. |
| 34 | **Jul 3 (eve) — decision locked:** no Brilliant Directories implementation; custom Apache stack. WAMP local + cPanel prod; PHP 8.1, MySQL, Bootstrap 3 HTML/CSS, JS/AJAX, Redis optional. Base env + folder layout not started. |

## Context Bank (appendix)

### Platform pivot — custom stack (not Brilliant Directories)

**Decision (Jul 3 eve):** The BDGS redesign will **not** be deployed as Brilliant Directories theme/widget work. Locked pages built in `local-html/` migrate into a **standalone web app**.

| Layer | Choice |
|-------|--------|
| **Front-end** | HTML + CSS + **Bootstrap 3** (existing `bdgsownv2-*` design system) |
| **Back-end** | **PHP 8.1** |
| **Database** | **MySQL** |
| **Client / async** | **JavaScript**, **AJAX** |
| **Cache / queue (as needed)** | **Redis** or equivalent |
| **Local dev** | **WAMP** — Apache + PHP 8.1 + MySQL (match production constraints) |
| **Production host** | **cPanel** on Apache |

**Task #34 scope:** stand up the base perfectly — WAMP vhost, PHP/MySQL versions, project root layout, `.htaccess` / rewrite rules, env/config pattern, and a documented path from static `local-html/` pages → PHP includes/partials without losing shared-component parity.

**Out of scope for BDGS site build:** Brilliant Directories CMS embedding, BD widget PHP, BD dashboard routing. BD remains the *client platform context* for directory owners — not the BDGS marketing site runtime.

### SEO — Part 1 setup & skills

- Charan (continuing from prior meeting) owns **SEO-related skills setup** in Cursor so the team has repeatable guidance.
- **Scope:** home page + **all pages built so far** must be SEO-friendly; **every new page** must ship SEO-friendly from day one.
- **Part 1** = whatever is defined in the BDGS SEO Part 1 checklist (skills, meta, structure, etc.) — **done on all built pages** (#2 ☑ Jul 4: home, `/services`, `/blabs-review`, `/webinars`); every **new** page must ship SEO-friendly from day one (audit-on-build for remaining inventory).
- **Keyword research:** completed and **logged** (reference log in project — Charan to confirm location).
- **Page names & URLs:** logged (inventory of canonical names and routes).

### 360° content & design lock (BDGS)

- As of this review: **all current content on BDGS is planned**.
- **All design is listed and locked** (no orphan/unlisted design work).
- **Deletions:** pages/content marked for removal are **logged** (not silently dropped).
- **Keep-live:** pages that remain published are **locked** as the live set.
- **New content:** any additional pages/content that may still arrive — process is to **lock their information** into the same inventory when identified (same lock discipline as existing pages).

### Page load stability & PageSpeed (Yakin feedback)

**#18 status (Jul 4): ⏸ On hold — post-deploy**

Site ships on **custom stack** (Apache + PHP 8.1 + MySQL on cPanel), **not** inside Brilliant Directories. There is **no live BDGS domain** yet, so official PageSpeed sign-off cannot run.

| When | What counts |
|------|-------------|
| **Now** | Local Lighthouse / DevTools for interim CLS checks only — **not** #18 sign-off |
| **Unblock early (optional)** | Public **staging or custom domain** on the same stack (PSI-crawlable like prod) |
| **Default** | After **#34–#37** (WAMP base + GitHub → cPanel deploy) on the **real BDGS production URL** |

**Still invalid for sign-off:** `github.io` previews · any Brilliant Directories-hosted URL (site is not on BD).

- **Symptom:** on page load, the **title dances** — **during** load and **after** load the **font size shifts**. Whatever the cause, it looks **bad** / unstable.
- **Diagnosis:** classic **layout shift (CLS)** — likely web-font swap (FOUT/FOIT), missing size reservation, or late CSS applying final title size.
- **Requirement:** title/headings must render at their **final size immediately** and stay put — no visible resize during or post-load.
- **Measurement (when unblocked):** run **Google PageSpeed Insights** on **every page** in the BDGS inventory — not spot-check only; log **score + Core Web Vitals per URL**, especially **CLS**; fix flagged items on each page.
- **URL rule (Yakin, updated Jul 4):** PageSpeed must target the **actual BDGS website** (production or equivalent public staging on the custom stack) — **not** GitHub Pages **`github.io`**, **not** a BD-site URL.
- **Scope:** title/font stability and PageSpeed pass apply **site-wide** — home, services, reviews, and all other live routes on the **deployed BDGS domain**.
- **Likely fixes:** `font-display` strategy, preload critical font, reserve space / set explicit sizes, avoid late-loaded CSS overriding title size.
- **Related:** #17 (CLS fixes in code) can still proceed in `local-html/` anytime; #18 is the **measurement/sign-off** gate only.

### Responsive layout & typography — quality gap (Yakin feedback)

**#4 / #5 / #6 / #17 status (Jul 4):** ☑ for **built** pages (home, `/services`, `/blabs-review`, `/webinars`). Remainder **deferred** — apply when each new page is built.

- **Requirement:** on **every single page**, **every section** must **self-adapt** the view across screen sizes (mobile, tablet, desktop, large desktop).
- **Observed problems:**
  - On **large screens:** page titles and related typography feel **too thin / too small**.
  - On **small screens:** the **same titles feel too big** — scale does not feel balanced.
  - Same pattern likely affects other section headings, not only page titles.
- **Last-meeting follow-through:** pages and design items captured in the **prior meeting notes are not done well so far** — execution gap vs what was agreed.
- **Title consistency:** title sizes **across pages are not production-ready**; lack of a unified type scale is visible when moving between routes.
- **Overall design consistency:** Yakin **not happy at all** with current consistency — this is a **blocker** before calling BDGS design complete.
- **Fix direction:** one shared responsive type scale + section spacing rules; page-by-page QA at multiple breakpoints; tie back to locked design inventory from 360° review.

### Services page — approved Jul 4 (#7 ☑)

- **Verdict:** Services page design is **approved** (Jul 4) — prior Jun 28 rejection closed.
- **Live draft:** `/services/` — [preview](https://businesslabshq.github.io/bdgs-website-v2-drafts/services/)
- **Status:** treat as locked for built-page gates; further polish only if Yakin requests.

### Reviews page — typography, cards, three design variants

**Current state / bugs**
- **Title copy:** title **text replacement is correct** — keep the words.
- **Title styling:** font/size may be **wrong** — **yet to check** (audit needed).
- **Cards “dancing”:** review cards have unwanted movement/layout shift — **must be corrected**.

**Design programme — three variants (Reviews v1 / v2 / v3)**
- Goal: on the **Reviews page**, show **three review design options** for comparison (Review v2 track = three designs total on page).
- After pick, **lock one**; **same locked design** goes on the **Home page** single-review carousel (one review at a time).

**Home page — shimmer prototype (source for v3)**
- Home already has **single review, one at a time** (carousel/rotation).
- Border colour baseline = **pinkish-red logo colour**; direction is to shift toward **gold** with a **shimmering effect**.
- **Task:** copy/isolate that Home review section; build **multiple shimmer variants on one page** so Yakin can compare and **pick/block one shimmer style** as the standard.

**Reviews v3 spec (primary candidate)**
- Start from **original simpler review card** — **remove certificate** and **four squares at the four corners/sides**.
- **Gold border** (or gold-toned) + **shimmer effect** — same shimmer language as Home prototype.
- On Reviews page: show **variety of shimmer effects** on one page (same card, different shimmer treatments).
- **Lock one** shimmer + card treatment in v3 → promote **same component to Home page**.

**Reviews v2 note**
- v2 path uses shimmer from Home **design #2** direction: **without certificate**, original layout — port that treatment to Reviews page simplified form.

**Cross-page rule**
- Reviews page = **design lab** (3 variants + shimmer options).
- Home page = **production** single-review widget once locked.

**Reviews page — title & header (Yakin feedback)**
- **Bar:** Reviews page **title and related header typography** (font, size, weight, line-height, spacing) must look **great** — production-ready, not approximate.
- **Copy:** title **wording is already correct** — fix is **visual polish**, aligned with site-wide type scale once locked.

**Review card — hover & link copy (Yakin feedback)**
- **Card hover:** on mouseover, **nothing happens to the entire card** — no lift, glow, scale, or border change on the card container.
- **Link hover only:** the **“Verified on Marketplace”** link inside the card may have a **CSS hover effect** — scoped to that link element only.
- **Label change:** cards currently show **two links** both reading **“View on Marketplace”** — both must become **“Verified on Marketplace”**.
- **Banned labels:** **“View on Marketplace”**, **“Verify”**, and other variants — wrong copy; erodes trust.
- **Demo discipline:** on the next Reviews demo (v1/v2/v3), **body text and link labels must not differ between variants** — using different placeholder copy across demos **shakes confidence**. Even demos are **gold-standard** production copy, not lorem or alternate wording.

### Registration complete / schedule-done modal (Yakin feedback)

**Status: shipped Jul 3 (homepage)**

**Context:** modal shown when scheduling is complete / registration is complete.

**Copy**
- Remove **“Please use the button below…”** — **not needed**; drop that line entirely.

**Layout**
- Show **event title**, **date/time**, and **timezone** with **proper spacing/gap** between elements (readable hierarchy).

**Add to Google Calendar — timezone bug**
- User selects timezone in the flow (e.g. **India / IST**).
- On **Add to Google Calendar**, Google Calendar opens on the user’s machine but the **event time must reflect the selected timezone** — e.g. user in India sees **India time** when the event is added, not a wrong offset.
- **Fix:** Google Calendar add URL (`calendar.google.com/calendar/render?action=TEMPLATE&…`) must encode **start/end in the correct timezone** (or use UTC + `ctz=Asia/Kolkata` / equivalent so Google applies IST at add time).
- **Test:** select India timezone → Add to Google Calendar → verify event lands at correct **IST** slot in Google Calendar.

### Home page — Section 1 (hero) & Section 2 — approved Jul 3

**Section 1 = Hero — ☑**
- **Signed off Jul 3** (Yakin) — 0→$1M headline, badges, AI strip, Explore Services → `/services/`, Join Free Zoom Clinic modal.

**Section 2 = CEO endorsement — ☑**
- **Signed off Jul 3** (Yakin) — Jason CEO copy, Explore Services, Read {x} reviews → `/blabs-review/`, Check out all webinars → `/webinars/`; CEO embed unchanged (`pxKOMfuuPO8`).

**“Read {x} reviews” CTA (Section 2)**
- Copy pattern: **“Read {x} reviews”** — **x** = dynamic review count via `bdgs-review-count.js` (fallback 176).
- **Destination:** `/blabs-review/` — Reviews page locked final Jul 3.

**Explore Services page**
- **Locked + on drafts** at `/services/` (Decision 0001) — hero + §2 CTAs wired Jul 3.

**Approval gate — Sections 1 & 2 — closed Jul 3**
- All linked destinations locked: `/services/`, `/blabs-review/`, `/webinars/`.
- Yakin sign-off recorded Jul 3 (eve).

**Home Section 3**
- **Work last** — do not prioritize ahead of Sections 1, 2, 4 closure.

**Home Section 4 — pending approval (Yakin feedback)**
- **Status:** minute-level approval **still open**.
- **Blocker:** **review card border** (gold/shimmer treatment) — **lock tomorrow** ( **Jul 2, 2026** ).
- **CTA change:** bottom button **“See {x} reviews”** — **shipped Jul 3** (`#bdgs-proof-reviews-link` + `bdgs-review-count.js`); **x** = live count from API.
- **UI:** plus (+) icon removed — button is text + arrow only.

**Home Section 5 — statistics ribbon (Yakin feedback)**
- **Section 5** = the **statistics ribbon** on the home page.
- **Locked stat lines:**
  | Stat | Copy |
  |------|------|
  | 1 | **500+** directly site served *(confirm final wording — e.g. clients directly served)* |
  | 2 | **176** verified reviews (dynamic from API) |
  | 3 | **10+ years** in directory ecosystem |
  | 4 | **20+** email developers *(verify exact label with Charan — possible STT: embedded/elite developers)* |

**Home Section 6 — six teaser cards + sprint plan (Yakin feedback)**
- **Section 6** = **six teaser cards** on the home page.
- **SEO / Cursor skills:** Part 1 SEO skill setup is **all set** — connected pages are **ready for build** with skills in place.
- **Tomorrow (Jul 2):** focus **Sections 1, 2, and 4** plus **every connected destination page** those sections link to.
- **Section 6 sprint:** Yakin + Charan — **1–2 days** on the **six teaser cards**; while building Section 6, **all connected pages get worked in the same pass** (not home-only).
- **Sequence after Section 6:** once Section 6 cards **and** connected pages are done → **then fully close Section 4** (Section 4 CTAs/cards also link to those same connected pages — finalize after destinations exist).
- **Section 3** remains **last** in the home-page rollout order.

**Home section rollout order (summary)**
1. **Jul 2:** Sections 1, 2, 4 (border lock) + connected pages  
2. **Jul 2–4:** Section 6 six cards + connected pages (parallel)  
3. **After above:** Section 4 final approval  
4. **Last:** Section 3  
5. **Section 5** stats ribbon — implement with locked copy (can track with connected-page sprint)

### Webinar page — First / Follow-up session + naming (Yakin feedback)

**Structure**
- **`/webinars/`** includes both sessions in the **Your First 100 Members** series (#205 First session, #213 Follow-up session). Homepage CEO embed unchanged.

**Naming — locked (Decision 0002, Jul 2)**
- **First session** (#205) and **Follow-up session** (#213) — BD-aligned; matches how Brilliant Directories describes the pair in their blog.
- **Not used:** Part 1/Part 2, Season 1 · Episode 1/2 — neither BD nor marketplace uses those labels.

**Closed Jul 3–4**
- Browser verify: no YouTube end-screen junk, no ads (#25) — **done Jul 3** (#24–25 ☑).
- ~~Yakin sign-off on `/webinars/` before Home §1/§2 gate clears (#24).~~ **Gate cleared Jul 3** — §1/§2 signed off.
- David source-file outreach (#26) — **closed Jul 4, not needed** (nocookie embed clean).

### YouTube embed — keep users on site (Yakin feedback)

**Status: closed Jul 4 (#24–#26 ☑)** — custom player + `youtube-nocookie` embed meets requirements; David fallback **not activated**.

**Requirements (met)**
- **Remove “Watch on YouTube”** from the embed entirely — users must stay on **our website**.
- After the video ends: **no YouTube end-screen junk** — no suggested videos, subscribe prompts, or other YouTube action items (“all the crap” must not appear).
- **Ads must not run** on webinar/marketing embeds — **zero ads**, non-negotiable.

**Embed params / implementation**
- Privacy-enhanced / minimal-chrome embed (`youtube-nocookie`, `rel=0`, `modestbranding=1`, custom controls) — verified clean in browser.

**Fallback — clean source from David (Charan owns outreach) — NOT NEEDED**
- Contingency only if YouTube would not allow a clean, ad-free, chrome-free embed.
- **Closed Jul 4:** embed path is clean; **do not email David** unless a future regression forces self-host / unlisted re-upload.
- If ever reopened: Charan emails David directly (skip Yakin + Cici); request downloadable source; re-host unlisted or self-hosted `<video>`.

**Goal:** same video content, **zero leakage** to YouTube UI and **zero ads** — **achieved** via custom player + nocookie embed.
