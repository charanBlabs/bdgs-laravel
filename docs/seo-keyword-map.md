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

Re-researched 2026-07-04 (pass 2) via Google autosuggest (seeds + a–z letter append + modifiers), SERP intent validation, People Also Ask, related searches, and AI-question patterns. Scope: `/blabs-review` only (other clusters still draft).

### Primary keywords (high intent, confirmed)
- "bd growth suite reviews" — brand + reviews intent; no autosuggest volume, but SERP #1 is `bdgrowthsuite.com/blabs-review` (BusinessLabs Reviews) — maps to /blabs-review

### Do not primary-target on /blabs-review (intent mismatch)
- "brilliant directories reviews" — autosuggest confirmed; SERP is BD's own reviews page, Trustpilot, Capterra, G2, Reddit, YouTube software reviews — **platform** reviews, not partner agency proof
- "brilliant directories reviews reddit" — autosuggest + related search; SERP is Reddit/platform discussion
- "brilliant directories reviews trustpilot" / "brilliant directories reviews bbb" — related searches; third-party software-review sites
- "brilliant directories developers reviews" — related search under "brilliant directories developers"; **SERP still platform-dominated** (Trustpilot, BD reviews, software YouTube) with only a lower Marketplace partner card — do not chase as primary
- "brilliant directories developers reviews reddit" / "… bbb" — related searches; same platform-review intent
- "brilliant directories testimonials" / "brilliant directories ratings" / "brilliant directories complaints" — autosuggest collapses to "brilliant directories reviews" (platform)
- "businesslabs reviews" / "business labs reviews" — autosuggest; SERP is employer reviews (AmbitionBox/Glassdoor) and local Hyderabad "Business Labs" listings — not BD Growth Suite client proof (Marketplace partner page appears mid-SERP only)
- "bd growth suite" (alone) — autosuggest confirmed — brand home maps to / , not /blabs-review
- "brilliant directories developers" — autosuggest confirmed; SERP is Marketplace hire listings + competitor agencies + homepage — maps to / (primary) and /hire-developer, not /blabs-review

### Question targets (for FAQs and blog)
- "Is brilliant directories good for beginners?" — PAA under developers-reviews SERP — answer on /blog or /setup FAQ (platform), not /blabs-review
- "What are brilliant directories used for?" — PAA — /blog or /
- "Does Brilliant Directories offer SEO tools?" — PAA under platform-reviews SERP — /seo-growth or /blog
- "What is the most trusted review site?" / "Can I get paid for reviews on Google?" / "Which business directory is best?" — PAA noise (generic); do not answer on /blabs-review
- "So thoughts about Brilliant Directories?" — Reddit title in SERP — /blog only
- AI-style questions (how buyers ask ChatGPT/Claude about **partner** trust — answer on /blabs-review FAQ):
  - "What do clients say about BD Growth Suite?"
  - "Are BD Growth Suite reviews real?"
  - "Should I hire BD Growth Suite for Brilliant Directories?"

### Related searches (supporting phrases)
- "brilliant directories developers reviews" — related search — **secondary body mention only** if copy clearly means reviews of **our developer team**, never as a ranking primary (SERP intent fails validation)
- "brilliant directories success stories" — autosuggest — maps to /case-studies when live; until then optional secondary mention on /blabs-review
- "brilliant directories marketplace" — autosuggest — supporting context (reviews sourced from Marketplace), not a ranking target

### Copywriter brief
- H1: keep brand voice ("We Made These Directories VERY HAPPY"); do **not** force mismatched platform phrases into the H1
- Title / meta / first paragraph: own **"bd growth suite reviews"** and **"verified client reviews"**; lead with review count + brand (e.g. "176 Verified Client Reviews — BD Growth Suite")
- Use these exact phrases in headings + body: "bd growth suite reviews", "verified client reviews", "Brilliant Directories site owners", "BD Growth Suite"
- Supporting (not primary) proof language: "Brilliant Directories developers" (owned by /) — OK in body as who you are, not as the page's ranking keyword
- Disambiguate from: Brilliant Directories **the software** (Trustpilot/Reddit/platform reviews); employer reviews of "Business Labs" / "BD"; Marketplace pages for other partners
- FAQ questions to add (verbatim buyer wording):
  - "What do clients say about BD Growth Suite?"
  - "Are BD Growth Suite reviews real?"
  - "Should I hire BD Growth Suite for Brilliant Directories?"
- Internal links: /, /services, /webinars, /zoom-clinics, /hire-developer (when live)

### Gaps / notes (reviews cluster)
- Partner-review search demand is thin in autosuggest; the winnable primary is the **brand** query you already rank #1 for.
- Prior pass incorrectly listed "brilliant directories developers reviews" as primary — SERP intent validation rejects it (platform SERP). Corrected in pass 2.
- Google currently titles the live result "BusinessLabs Reviews" — confirm whether title/meta should say BD Growth Suite, BusinessLabs, or both for brand consistency.
- Individual Marketplace partner review URLs compete for "businesslabs reviews"; /blabs-review should own the **aggregated** BD Growth Suite proof narrative.

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
- "bd growth suite zoom clinic review" — related search — maps to /blabs-review (trust proof), secondary mention on /zoom-clinics
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
- Internal links: /, /services, /webinars, /blabs-review, /customization; /setup when live (sidebar already references Express Setup)

### Gaps / notes (zoom-clinics cluster)
- `data/seo-pages.json` lists primary `"brilliant directories help"` — **SERP intent fails validation**; recommend Yakin updates registry to `"brilliant directories zoom clinics"` on lock.
- `site:bdgrowthsuite.com brilliant directories zoom clinics` returned **zero Google results** (2026-07-10) — page is live locally but not indexed yet; expect ranking lag until deploy + Search Console.
- Search volume for "zoom clinics" is thin/niche; winnable play is **owning the branded product phrase** + **bd growth suite zoom clinic** related searches, not generic "help" head terms.
- Competitor content: membershipwebsites.net "Brilliant Directories Zoom Support" — different product; do not mirror their positioning.
- `/webinars` owns CEO/on-demand webinar intent; `/zoom-clinics` owns recurring free developer Q&A — keep cross-links explicit in copy.

---

## Gaps / notes
- Confirm the north-star metric with Yakin before locking (see `data/seo-pages.json` -> site.north_star).
- Add clusters for the /solutions/* hubs after the homepage and core pages are researched.
- Reviews cluster re-researched 2026-07-04 (pass 2, SERP intent validated) — ready for Yakin to review; full map STATUS remains DRAFT until all clusters are locked.
- Zoom Clinics cluster researched 2026-07-10 — ready for Yakin to review; recommends changing seo-pages.json primary from `brilliant directories help` to `brilliant directories zoom clinics`.
