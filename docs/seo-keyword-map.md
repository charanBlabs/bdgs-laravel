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

## Gaps / notes
- Confirm the north-star metric with Yakin before locking (see `data/seo-pages.json` -> site.north_star).
- Add clusters for the /solutions/* hubs after the homepage and core pages are researched.
- Reviews cluster re-researched 2026-07-04 (pass 2, SERP intent validated) — ready for Yakin to review; full map STATUS remains DRAFT until all clusters are locked.
