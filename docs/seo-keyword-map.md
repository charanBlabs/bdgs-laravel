# BD Growth Suite — Keyword Map

STATUS: DRAFT (not locked).

Run the `seo-keyword-research` skill in a dedicated chat to fill this with real Google and AI-tool research, then have Yakin review and set `STATUS: LOCKED {date}`. After lock, page building uses only the phrases mapped to each page.

> Every phrase must map to a page URL in `data/seo-pages.json`.

---

## Target map — Homepage / brand (awareness)

### Primary keywords (high intent, confirmed)
- "brilliant directories developers" — TO CONFIRM via autosuggest — maps to /
- "directory website expert" — TO CONFIRM — maps to / and /consultation

### Question targets (for FAQs and blog) — TO RESEARCH
- (run seo-keyword-research: Google autosuggest + People Also Ask)

---

## Target map — Setup (consideration -> conversion)

### Primary keywords
- "brilliant directories setup" — TO CONFIRM — maps to /setup
- "brilliant directories setup cost" — TO CONFIRM — maps to /setup

### Question targets — TO RESEARCH
- "how much does brilliant directories cost" — TO CONFIRM — /setup FAQ

---

## Target map — Hire a developer (conversion)

### Primary keywords
- "hire brilliant directories developer" — TO CONFIRM — maps to /hire-developer

---

## Target map — Reviews / social proof (trust)

Re-researched 2026-07-10 (pass 3) via Google SERP validation, Marketplace partner-page review, related searches, PAA-style buyer questions, and AI-question patterns. Scope: `/reviews` only. **Brand fact locked:** client reviews on this page are for **Business Labs** (Marketplace listing name) — presented publicly as **Business Labs by BD Growth Suite** — sourced from the **Brilliant Directories Marketplace**, not self-published testimonials.

### Primary keywords (high intent, confirmed)
- "bd growth suite reviews" — brand + reviews intent; SERP #1 is `bdgrowthsuite.com/reviews` (historically titled "BusinessLabs Reviews") — maps to /reviews
- "verified client reviews" — on-page proof language; pairs with dynamic review count in title/meta — maps to /reviews (secondary primary, same page)

### Secondary keywords (body + AEO — not separate ranking primaries)
- "Brilliant Directories Marketplace reviews" — SERP intent = hire/read **partner** reviews on marketplace.brilliantdirectories.com — maps to /reviews as **aggregated** proof (our page summarizes; each card links to marketplace verify URL)
- "Business Labs by BD Growth Suite" — brand disambiguation phrase; use in verification H2 answer + first supporting paragraph — maps to /reviews
- "Gold Certified Brilliant Directories Partner" — Marketplace badge language (Business Labs listing) — maps to /reviews (trust strip, verification block)
- "verified marketplace reviews" — buyer trust wording; maps to /reviews (AEO H2 + lead)

### Do not primary-target on /reviews (intent mismatch)
- "brilliant directories reviews" — autosuggest confirmed; SERP is BD's own software reviews page, Trustpilot, Capterra, G2, Reddit, YouTube — **platform** reviews, not partner agency proof
- "brilliant directories reviews reddit" / "… trustpilot" / "… bbb" — related searches; third-party **software** review sites
- "brilliant directories developers reviews" — related search; SERP still **platform-dominated** — secondary body mention only if copy means reviews of **our developer team**
- "businesslabs reviews" / "business labs reviews" (alone) — autosuggest; SERP mixes **employer reviews** (AmbitionBox/Glassdoor), local Hyderabad listings, and mid-SERP Marketplace partner URLs — do **not** chase as primary; disambiguate in copy ("Marketplace client reviews, not employer reviews")
- "business labs brilliant directories" — navigational to Marketplace partner profile — OK as supporting CTA link to `marketplace.brilliantdirectories.com/india/partner/business-labs`, not a ranking H1
- "bd growth suite" (alone) — brand home maps to / , not /reviews
- "brilliant directories developers" — Marketplace hire listings + agencies — maps to / and /hire-developer, not /reviews
- Individual Marketplace review URLs (e.g. `…/business-labs/reviews/1541`) — long-tail proof pages; /reviews owns the **aggregated** narrative; do not duplicate full review text

### Question targets (for FAQs and blog)
- "Are these Brilliant Directories client reviews verified?" — on-page AEO H2 (live) — answer on /reviews
- "Are BD Growth Suite reviews real?" — AI-style buyer question — /reviews FAQ when added
- "Are Business Labs reviews on the Brilliant Directories Marketplace?" — buyer disambiguation — /reviews verification block + FAQ
- "What do clients say about BD Growth Suite?" / "What do clients say about Business Labs?" — same answer on /reviews (alias the Marketplace listing name in FAQ)
- "Should I hire Business Labs for Brilliant Directories?" — hire intent — /reviews proof → CTA to /services or /hire-developer
- "How do I verify a Brilliant Directories partner review?" — explain per-card "Verified on Marketplace" link — /reviews FAQ
- "Is brilliant directories good for beginners?" — PAA under developers-reviews SERP — /blog or /setup (platform), not /reviews
- "What is the most trusted review site?" / generic review-site PAA — noise; do not answer on /reviews

### Related searches (supporting phrases)
- "brilliant directories marketplace" — autosuggest — supporting context (where reviews are sourced), not a ranking primary
- "brilliant directories marketplace partner reviews" — hire/trust intent — secondary mention on /reviews; primary hire flow stays Marketplace profile + /hire-developer
- "brilliant directories success stories" — autosuggest — maps to /case-studies when live; optional secondary on /reviews until then
- "bd growth suite zoom clinic review" — related search — secondary mention on /zoom-clinics; trust proof link to /reviews

### Copywriter brief
- **H1:** keep brand voice — **"We Made These Brilliant Directories Sites VERY HAPPY"** (strikethrough on "Happy"); do **not** force platform-review phrases into H1
- **Title / meta / first paragraph:** own **"bd growth suite reviews"** + **"verified client reviews"**; lead with live review count + brand (e.g. "{count} Verified Client Reviews — BD Growth Suite")
- **Verification block (required):** H2 **"Are these Brilliant Directories client reviews verified?"** — answer-first lead must state: reviews are imported from the **Brilliant Directories Marketplace**, each links to a verified site owner, and **Business Labs by BD Growth Suite** is the Gold Certified partner those reviews describe
- **Exact phrases for headings + body:** "bd growth suite reviews", "verified client reviews", "Brilliant Directories Marketplace", "Brilliant Directories site owners", "Business Labs by BD Growth Suite", "Gold Certified Brilliant Directories Partner"
- **Count rule:** use dynamic `{{ $reviewCount }}` — **never** append "+" when the live DB count is exact (e.g. 176, not 176+)
- **Disambiguate from:** Brilliant Directories **the software** (Trustpilot/Reddit/platform reviews); **employer** reviews of "Business Labs" on Glassdoor/AmbitionBox; other Marketplace partners' review pages; self-published testimonial pages with no verify link
- **Marketplace naming:** Marketplace lists **"Business Labs"**; public site brand is **BD Growth Suite** — always connect them once per page: "Business Labs by BD Growth Suite"
- **FAQ questions to add (verbatim buyer wording):**
  - "Are these Brilliant Directories client reviews verified?"
  - "Are BD Growth Suite reviews real?"
  - "Are Business Labs reviews on the Brilliant Directories Marketplace?"
  - "What do clients say about Business Labs?"
  - "Should I hire Business Labs for Brilliant Directories?"
  - "How do I verify a partner review on the Marketplace?"
- **Internal links:** /, /services, /webinars, /zoom-clinics, /customization, /hire-developer (when live)
- **External trust link (one per page max in body):** Marketplace partner profile `https://marketplace.brilliantdirectories.com/india/partner/business-labs`

### Gaps / notes (reviews cluster)
- Slug migrated `/blabs-review` → `/reviews` (2026-07-10); 301 redirects live — update any stale docs/links on lock.
- Google may still show legacy title "BusinessLabs Reviews" until re-crawl; title tag uses "BD Growth Suite" — confirm with Yakin whether SERP should emphasize Business Labs, BD Growth Suite, or both.
- Marketplace partner page shows **Rated 5/5 (176 Reviews)** and **Verified Reviews (176)** — keep site count in sync with `bdgs_blabs_reviews` sync job.
- Partner-review autosuggest is thin; winnable play = **brand query** ("bd growth suite reviews") + **verified marketplace proof** narrative, not generic "business labs reviews".
- Pass 2 incorrectly kept `/blabs-review` slug references — corrected in pass 3.
- `docs/seo-keyword-map.md` zoom-clinics cluster still references `/blabs-review` for trust cross-link — update to `/reviews` on next zoom-clinics pass.

---

## Target map — Zoom Clinics (awareness / live events)

Researched 2026-07-10 via Google autosuggest (seeds + related searches), SERP intent validation, People Also Ask, and AI-question patterns. Scope: `/zoom-clinics` only.

### Primary keywords (high intent, confirmed)
- "brilliant directories zoom clinics" — related searches under `free brilliant directories zoom clinics` + `brilliant directories zoom clinic` (low competition; no dominant owner SERP) — maps to /zoom-clinics
- "bd growth suite zoom clinics" — related searches (`bd growth suite zoom clinic free`, `… review`, `… cost`) — maps to /zoom-clinics (brand + product)

### Do not primary-target on /zoom-clinics (intent mismatch)
- "brilliant directories help" — autosuggest + related searches (`… help chat`, `… help phone number`, `… help email`, `… help contact`); SERP is Brilliant Directories official support docs, contact page, Admin ChatBot, and YouTube getting-started guides — **platform vendor support**, not partner live Q&A
- "brilliant directories support" — related searches (`… support phone number`, `… support chat`, `… support email`); same official BD support/contact SERP
- "free brilliant directories help" — related searches (`… reddit`, `… chat`, `… phone number`); SERP is BD docs, free trial, Capterra/Software Advice pricing — **free trial / vendor help**, not Zoom Clinics
- "brilliant directories live help" — related searches mirror contact/support modifiers; SERP is BD one-hour paid support call + platform docs — not free drop-in clinics
- "how to get help with brilliant directories" — PAA (`Is Brilliant Directories good for beginners?`, `How much does Brilliant directories cost?`, platform FAQs); SERP is BD homepage, YouTube tutorials, software review sites — **general onboarding**, not clinic registration
- "free brilliant directories zoom clinics" — related searches exist but SERP is BD homepage, YouTube Q&A livestreams, Capterra pricing, ZoomInfo noise — **platform + generic Zoom**, not our product page; use as secondary body phrase only after primary is established

### Question targets (for FAQs and blog)
- "How do I get help with my Brilliant Directories website?" — AI-style buyer question — answer on /zoom-clinics FAQ (live tactical help vs strategy)
- "Are BD Growth Suite Zoom Clinics free?" — related search `bd growth suite zoom clinic free` — /zoom-clinics FAQ
- "When are Brilliant Directories Zoom Clinics?" — schedule intent — /zoom-clinics FAQ + hero
- "What can I ask at a Brilliant Directories Zoom Clinic?" — scope/tactics (widgets, CSS, search, email) — /zoom-clinics FAQ
- "How do I register for a Zoom Clinic?" — conversion intent — /zoom-clinics FAQ
- "Is there free live help for Brilliant Directories?" — AI-style — /zoom-clinics FAQ (disambiguate from BD official support)
- PAA from `how to get help with brilliant directories` — route elsewhere, not /zoom-clinics primary:
  - "Is Brilliant Directories good for beginners?" — /setup or /blog when live
  - "How much does Brilliant directories cost?" — /setup when live
  - "What is Brilliant Directories used for?" — /
  - "Does Brilliant Directories offer SEO tools?" — /seo-growth when live

### Related searches (supporting phrases)
- "bd growth suite zoom clinic free" — related search — maps to /zoom-clinics (body + FAQ, emphasize $0)
- "bd growth suite zoom clinic cost" — related search — maps to /zoom-clinics FAQ (answer: free)
- "bd growth suite zoom clinic review" — related search — maps to /reviews (trust proof), secondary mention on /zoom-clinics
- "brilliant directories help chat" / "… phone number" / "… email" — related under `brilliant directories help` — **do not chase**; optional sidebar disambiguation ("Not official BD support — partner-run free clinics")
- "brilliant directories zoom clinic" (singular) — related/noisy SERP (ZoomInfo, Marketplace Zoom tool) — secondary H1 variant only; prefer plural "zoom clinics"
- "brilliant directories documentation" — autosuggest under help cluster — maps to external BD docs; link only if helpful, not a ranking target

### Copywriter brief
- H1: **Brilliant Directories Zoom Clinics** (keep product name; do not force "help" into H1 — SERP intent is wrong)
- Title / meta / first paragraph: own **"brilliant directories zoom clinics"** + **"free"** + **"BD Growth Suite"**; lead with drop-in live Q&A (Tues/Thu, $0)
- Use these exact phrases in headings + body: "Brilliant Directories Zoom Clinics", "free Zoom Clinics", "Brilliant Directories site owners", "live Q&A", "BD Growth Suite developers"
- Supporting (not primary): "brilliant directories help" — OK once in body when clarifying *tactical* help vs official BD support ticket/chat
- Disambiguate from: Brilliant Directories **official support** (docs, phone, Admin ChatBot, paid one-hour training call); **CEO webinars** on /webinars; **paid setup** on /setup and Express Setup; Zoom the **video app** / ZoomInfo company pages
- FAQ questions to add (verbatim buyer wording):
  - "How do I get help with my Brilliant Directories website?"
  - "Are BD Growth Suite Zoom Clinics free?"
  - "When are Brilliant Directories Zoom Clinics?"
  - "What can I ask at a Brilliant Directories Zoom Clinic?"
  - "How do I register for a Zoom Clinic?"
- Internal links: /, /services, /webinars, /reviews, /customization; /setup when live (sidebar already references Express Setup)

### Gaps / notes (zoom-clinics cluster)
- `data/seo-pages.json` lists primary `"brilliant directories help"` — **SERP intent fails validation**; recommend Yakin updates registry to `"brilliant directories zoom clinics"` on lock.
- `site:bdgrowthsuite.com brilliant directories zoom clinics` returned **zero Google results** (2026-07-10) — page is live locally but not indexed yet; expect ranking lag until deploy + Search Console.
- Search volume for "zoom clinics" is thin/niche; winnable play is **owning the branded product phrase** + **bd growth suite zoom clinic** related searches, not generic "help" head terms.
- Competitor content: membershipwebsites.net "Brilliant Directories Zoom Support" — different product; do not mirror their positioning.
- `/webinars` owns CEO/on-demand webinar intent; `/zoom-clinics` owns recurring free developer Q&A — keep cross-links explicit in copy.

---

## Target map — Solutions / SEO & Schema hub (consideration)

Researched 2026-07-11 via Google autosuggest, SERP intent validation (US/EN), People Also Ask, People also search for, Marketplace product SERPs, and AI-question patterns. Scope: `/solutions/seo` only (category hub / listing of done-for-you SEO & schema solutions). Individual solution URLs (`/solutions/[slug]`) own product long-tails when shipped.

### Primary keywords (high intent, confirmed)
- "brilliant directories schema solutions" — branded **done-for-you catalog** phrase (mirrors Zoom Clinics play: own a thin, partner-owned phrase rather than platform head terms); SERP for shorter "brilliant directories schema" is vendor-dominated — maps to /solutions/seo
- "SEO & Schema Solutions for Brilliant Directories" — registry title phrase; use in title/H1; maps to /solutions/seo

### Do not primary-target on /solutions/seo (intent mismatch)
- "brilliant directories schema" — autosuggest confirmed (`brilliant directories schema` + typo `… scheme`); SERP #1–#2 = official BD docs ("Understanding and Editing Schema Markup") + BD marketing page `brilliantdirectories.com/schema-seo` + BD YouTube — **platform feature / how-to**, not a partner solutions hub. Secondary body mention only when disambiguating done-for-you vs DIY
- "brilliant directories seo" — autosuggest confirmed; SERP = BD SEO 101 blog, "Where to Make SEO Edits", All-In-One SEO Pack, agency explainers — **platform DIY SEO**. Maps to `/seo-growth` when live (registry primary), not this hub
- "brilliant directories seo solutions" — related/noisy; SERP still BD blogs + All-In-One SEO Pack + Trustpilot — not a partner catalog
- "how to add schema to brilliant directories" / "how to add schema markup to brilliant directories" — AI/how-to intent; SERP = BD support docs — answer briefly in FAQ then CTA to solutions; do not rank the hub as a tutorial
- "what is schema in SEO" / "what is an organization schema" / "how many types of schema are in SEO" — PAA under schema SERP; **generic SEO education** — optional FAQ one-liners or /blog, not hub primaries
- "brilliant directories schema login" / "… email" / "… phone number" — related searches under schema SERP; **navigational/noise**
- "schema validator" / "google schema validator" / "schema checker" / "schema generator" — generic tool autosuggest; not BD-specific
- Individual Marketplace product queries (e.g. "schema for home page brilliant directories", "essential seo audit brilliant directories") — SERP can surface Marketplace/partner plugin pages; **map to the matching `/solutions/[slug]` detail page**, not the hub H1

### Question targets (for FAQs and blog)
- "How do I add schema markup to Brilliant Directories?" — AI-style; FAQ on /solutions/seo (DIY pointer to BD docs + done-for-you catalog CTA)
- "Does Brilliant Directories include schema markup by default?" — AI/docs-derived; FAQ on /solutions/seo (yes, baseline; hub sells upgrades/audits/custom JSON-LD)
- "What Brilliant Directories schema solutions do you offer?" — catalog intent — answer-first on /solutions/seo (list homepage, member, event, job, blog schemas + SEO audits)
- "Do I need a schema plugin if Brilliant Directories already has Schema & SEO?" — disambiguation FAQ — /solutions/seo
- "What is an Essential SEO Audit for Brilliant Directories?" — product FAQ — link to `/solutions/essential-seo-audit` when live; summary on hub
- "What is a Strategic SEO Audit for Brilliant Directories?" — same pattern → `/solutions/strategic-seo-audit-advanced` when live
- "Is Brilliant Directories good for SEO?" — PAA/AI common; route to `/seo-growth` or /blog when live — not hub primary
- "How much does Brilliant Directories cost?" — PAA under schema SERP — maps to /setup when live, not /solutions/seo

### Related searches (supporting phrases)
- "brilliant directories schema reviews" — related under schema SERP — secondary trust mention; proof lives on /reviews
- "brilliant directories marketplace" — related under product SERPs — supporting context (where plugins are sold); hub should still sell on-site
- "brilliant directories integrations" — related under product SERP — maps to /solutions/integrations (sibling hub), not this page
- "schema for home page" — Marketplace product name — secondary hub card language; primary ranking target = `/solutions/schema-for-home-page` (templated solution page)
- "event schema" / "job schema" / "member profile schema" / "blog schema" — product cluster language — secondary on hub; primaries on respective solution detail URLs
- "structured data" / "JSON-LD" — supporting body phrases on hub + detail pages
- "brilliant directories seo tutorial" — related under seo SERP — DIY; do not chase on hub

### Copywriter brief
- **H1:** **SEO & Schema Solutions for Brilliant Directories** (own the catalog phrase; do **not** force bare "brilliant directories schema" into H1 — SERP intent is vendor docs)
- **Title / meta / first paragraph:** lead with **brilliant directories schema solutions** + done-for-you + audits/plugins (homepage, member, event, job, blog schema + Essential/Strategic SEO audits)
- **Exact phrases for headings + body:** "Brilliant Directories schema solutions", "SEO & Schema Solutions", "schema markup", "structured data", "JSON-LD", "Essential SEO Audit", "Strategic SEO Audit", "done-for-you"
- **Supporting (not primary):** "brilliant directories schema" once when clarifying DIY platform schema vs partner upgrades
- **Disambiguate from:** Brilliant Directories **built-in Schema & SEO** (`/schema-seo`, All-In-One SEO Pack); **DIY widget/SEO-template editing** in BD docs; ongoing **SEO growth retainer** on `/seo-growth`; generic schema-validator tools; sibling solution categories (lead-gen, integrations, etc.)
- **FAQ questions to add (verbatim buyer wording):**
  - "How do I add schema markup to Brilliant Directories?"
  - "Does Brilliant Directories include schema markup by default?"
  - "What Brilliant Directories schema solutions do you offer?"
  - "Do I need a schema plugin if Brilliant Directories already has Schema & SEO?"
  - "What is an Essential SEO Audit for Brilliant Directories?"
- **Internal links:** /, /solutions, /services, /customization, /reviews, /seo-growth (when live); deep links to each listed `/solutions/[slug]` card

### Gaps / notes (solutions-seo cluster)
- `data/seo-pages.json` lists primary `"brilliant directories schema"` — **SERP intent fails validation** for a partner hub; recommend Yakin updates registry primary to `"brilliant directories schema solutions"` on lock.
- Category volume is thin vs platform head terms; winnable play = **own the solutions-hub phrase** + rank individual product pages for Marketplace-style long-tails (same pattern as Zoom Clinics brand phrase).
- AI Overview already cites Marketplace "Schema for Home Page" as a path — confirms commercial demand for partner plugins; hub should aggregate those products on-site.
- `/seo-growth` (planned) owns broader "brilliant directories seo" service intent; `/solutions/seo` owns **buyable schema/audit products**. Keep cross-links explicit.
- Remaining `/solutions/*` category hubs still need their own research passes (lead-gen, member-profiles, search, page-design, content, integrations, member-management).

---

## Gaps / notes
- Confirm the north-star metric with Yakin before locking (see `data/seo-pages.json` -> site.north_star).
- `/solutions/seo` cluster researched 2026-07-11 — ready for Yakin to review; recommends changing seo-pages.json primary from `brilliant directories schema` to `brilliant directories schema solutions`.
- Remaining `/solutions/*` hubs still need research after this cluster is reviewed.
- Reviews cluster re-researched 2026-07-04 (pass 2, SERP intent validated) — ready for Yakin to review; full map STATUS remains DRAFT until all clusters are locked.
- Zoom Clinics cluster researched 2026-07-10 — ready for Yakin to review; recommends changing seo-pages.json primary from `brilliant directories help` to `brilliant directories zoom clinics`.
