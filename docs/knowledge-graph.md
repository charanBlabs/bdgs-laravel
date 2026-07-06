> **Migration note.** Reference doc from the BD Growth Suite design phase. Historical paths like `local-html/` and `home-hero-v*` refer to the original working repo; in this project, finalized pages live at `/{slug}/index.html`. This is a **living source of truth** — update it in place and log changes in the Changelog at the bottom.

# BDGS Website Redesign — Titan Knowledge Graph

> **What this document is:** A fully optimized, sectionized record of all strategic and technical decisions made during the BDGS website redesign engagement. Brainstormed-but-rejected paths have been removed. Only finalized decisions remain. Use this as the single source of truth for anyone continuing this project.

---

## TITAN SUMMARY (Index)

| # | Section | One-Line Summary |
|---|---------|-----------------|
| [§1](#s1-brand-foundation) | Brand Foundation | Colors, fonts, CSS prefix, Bootstrap 3 — all locked |
| [§2](#s2-competitor-analysis) | Competitor Analysis | 9 competitors mapped; BDGS moat identified |
| [§3](#s3-navigation-architecture) | Navigation Architecture | Final header + footer + all dropdown structures |
| [§4](#s4-homepage-architecture) | Homepage Architecture | Locked 10-section homepage flow with rationale |
| [§5](#s5-service-taxonomy) | Service Taxonomy | Solutions vs Tools vs Themes — definitions locked |
| [§6](#s6-three-tier-pattern) | Three-Tier Service Pattern | Zoom Clinics / Express-or-Dedicated / Apply-Only — per card |
| [§7](#s7-sitemap) | Full Site Sitemap | All pages, Tier 1/2/3, sprint order |
| [§8](#s8-partner-program) | Partner Program | 3 tiers: Referral, White-Label, Directory Operator |
| [§9](#s9-zoom-clinics) | Zoom Clinics | Positioning, schedule, registration, timezone JS |
| [§10](#s10-ai-positioning) | AI Positioning & Products | AI-native strategy, AI Dev Fleet, BD Automation |
| [§11](#s11-copy-decisions) | Copy & Messaging | Hero, pain cards, team philosophy — approved copy |
| [§12](#s12-technical) | Technical Implementation | V7 HTML, stacking cards, known bugs, favicon/logo |
| [§13](#s13-parked) | Parked for Later | Items explicitly deferred, not dropped |
| [§14](#s14-sprints) | Build Sprints | Priority order for page builds |

---

## §1 Brand Foundation {#s1-brand-foundation}

### Visual Identity (LOCKED)

| Element | Value |
|---------|-------|
| Primary color | `#E74D56` (coral-red) |
| Secondary color | `#95256E` (deep purple) |
| Trust accent | BD blue (from Brilliant Directories brand) |
| Primary font | DM Sans (web-safe stand-in for Glacial Indifference) |
| Secondary font | Roboto |
| CSS prefix | `bdgsownv2-` on ALL custom classes |
| CSS framework | Bootstrap 3 (Brilliant Directories default; do NOT use v4/v5) |
| Logo URL | `https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png` |
| Favicon URL | `https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/favicon.png` |

### Brand Voice (LOCKED)

- Anti-fluff, specific, oppositional
- Pattern: "not a ticket queue," "not a slideshow" — contrast what others do
- Tone: Founder-savvy, outcome-focused, no marketing speak
- Core claim: "The only directory team that gets you from **0→1→10→100…$1M**"

### Brand Positioning (LOCKED)

- BDGS is the **service arm of BusinessLabs** that serves Brilliant Directories site owners
- **Not a freelancer, not an agency** — a growth partner
- BDGS keeps its own coral + purple identity; BD blue used as a trust accent only
- Display: **🏅 Gold Certified Brilliant Directories Partner** badge prominently
- Display: **🤖 Claude / Anthropic Service Partner** badge (unique moat — no competitor has this)
- No logo redesign needed — hybrid approach: keep BDGS brand, add BD badge

### Branding Decision: BDGS vs Brilliant Directories colors

**Decision: Option C — Hybrid.** BDGS coral + purple remains primary. BD blue as secondary trust accent. BD gold badge shown prominently. Zero logo work required. Reasoning: builds BDGS brand equity while signaling partnership.

---

## §2 Competitor Analysis {#s2-competitor-analysis}

### Competitor Map

| Competitor | Person | Location | Strength | Weakness |
|-----------|--------|----------|----------|----------|
| DirectoryToolkit (Ryan) | Ryan | USA | Social presence, plugins, video library, hourly billing | Too scattered, solo, no AI, no growth strategy |
| The Developer Club (Luis) | Luis Alvarado | Costa Rica | Claims ex-BD internal dev, 2100+ sites, clean brand | Solo-ish, no AI, no scale, pure tech labor |
| TreeHouse Developers | Team | USA | Plugin marketplace, 8-step launch process | No consulting, no AI, generic branding |
| BrilliantSetups | Unknown | Unknown | Setup-focused | Very narrow, no growth |
| Dev Bilal | Bilal | Lahore, Pakistan | Aggressive social presence, multi-platform, low price | Price competitor, 4yr exp vs 10+, no brand |
| BD itself | Official | USA | Platform, blog, webinars, support | Sells platform, not growth; their DFY is limited |
| BDExpert (Jonny) | Jonny | UK | Fixed pricing, UK market, 3-month warranty | Small, UK-only, no AI |
| Brilliant Developers | Team | India | Low cost, 10+ years | Generic, no positioning |
| Shaikh Taj | ex-BDGS | Unknown | Knows BDGS playbook | Solo, no brand |

### BDGS Moat (What NO competitor has)

| Differentiator | BDGS | Everyone else |
|---------------|------|---------------|
| AI-native development + Claude Partner | ✅ | ❌ |
| Growth strategy (0→$1M) | ✅ | ❌ |
| BD CEO endorsement (3 webinars) | ✅ | ❌ |
| In-house team (20+ devs) | ✅ | ❌ |
| Gold Certified | ✅ | ❌ |
| SaaS tools (BrilliantChat etc.) | ✅ | ❌ |
| White-label reseller | ✅ | ❌ |
| 500+ sites served | ✅ | ❌ |
| 169+ verified reviews | ✅ | ❌ |

### Navigation Learnings from Competitors

- **Steal from Developer Club:** Simple, clean nav — 4-5 items, no clutter
- **Don't copy:** BD's mega-menu (too heavy for service brand), DirectoryToolkit's 7-bucket sprawl
- **Dev Bilal's hustle:** Be present in BD Facebook groups, but as a premium brand

---

## §3 Navigation Architecture {#s3-navigation-architecture}

### Utility Strip (thin bar above main nav)

```
[LEFT] 🏅 Gold-Certified Brilliant Directories Partner · Trusted by 500+ directories
[RIGHT] About · Contact · Login
```

Optional left side: rotating announcements (Zoom Clinic today, Claude Partner badge, etc.)

### Main Header Nav (LOCKED — 6 items + CTA)

```
[BDGS Logo]   Services▾   Grow with AI▾   Hire a Developer   Tools▾   Themes   [🟢 Zoom Clinics]   [Get Started →]
```

**Decisions:**
- No "Home" link — logo click = home
- Blog removed from main nav (lives in footer Resources column + utility strip)
- "Hire a Developer" is a direct top-level link (highest-intent action; not buried in Services)
- Zoom Clinics = prominent pill button with green pulse dot
- Get Started = opens global inquiry modal (not a page)

---

### Services Dropdown (2-column mega-menu)

**LEFT — SERVICES column:**

| Item | URL | Description |
|------|-----|-------------|
| Setup & Launch | `/setup` | Concierge directory launch. Business-first approach. |
| Custom Projects | `/customization` | Scoped one-off dev work |
| Maintenance Plans | `/maintenance` | Ongoing support, backups, updates, optimization |
| Consultation | `/consultation` | Talk to Yakin's team. 18+ years directory expertise. |
| Founder's Track | `/founders-track` | Co-founder level strategy + dev + AI. Apply to join. |

**RIGHT — SOLUTIONS DONE FOR YOU panel:**

| Item | URL |
|------|-----|
| All Solutions (61+) → | `/solutions` |

**Bottom strip:** 🎯 Free Zoom Clinics — Drop in live. Get help now. `[Join Today's Session →]`

---

### Grow with AI Dropdown

| Item | URL | Description |
|------|-----|-------------|
| AI-Powered Development | `/ai-development` | Faster execution. Savings passed to you. |
| AI Dev Fleet | `/ai-dev-fleet` | A team of AI works for you. Pay for one developer. |
| AI for Your Directory | `/ai-for-your-site` | Chatbots, agents, smart search — on your site. |
| Directory Automation | `/bd-automation` | Follow-ups, lead gen, content AI, member engagement. |
| SEO & Growth | `/seo-growth` | Audits, schema, traffic strategy — directory-specific. |
| Founder's Track | `/founders-track` | AI-powered strategy. Apply to join. |

**Bottom highlight strip:** 🤖 Claude / Anthropic Service Partner — The only directory team building with AI

---

### Tools Dropdown (single column)

| Item | URL |
|------|-----|
| All Tools & Themes | `/tools` |
| BrilliantChat | `/tools/brilliantchat` |
| SEO Solutions | `/tools?category=seo` |
| Conversion Boosters | `/tools?category=conversion` |

---

### Themes

Direct link → `/themes` (no dropdown)

---

### Footer (4 columns)

**Col 1 — Services:**
Setup & Launch, Solutions (Done-For-You), Dedicated Developers, Maintenance Plans, Consultation, Founder's Track, Free Zoom Clinics 🟢

**Col 2 — Grow with AI:**
AI-Powered Development, AI Dev Fleet, AI for Your Directory, Directory Automation, SEO & Growth

**Col 3 — Resources:**
Tools & Themes, Solutions (61+ done-for-you), Directory Themes (6), Blog, Client Reviews (169+), About Us, CEO Webinars

**Col 4 — Get Started:**
WhatsApp Chat, Book a Consultation, Contact Us, Login

**Footer bottom bar:**
`© 2026 BD Growth Suite by BusinessLabs. All rights reserved. | Terms | Privacy`
`🏅 Gold Certified Brilliant Directories Partner · 🤖 Claude / Anthropic Partner · 🌍 500+ Sites · 7+ Countries`

---

## §4 Homepage Architecture {#s4-homepage-architecture}

### Locked 10-Section Homepage Flow

```
1.  HERO (Finetuned — light coral-purple gradient)
     ↓ Hook: 0→$1M, AI badges, 2× speed
     
2.  CEO ENDORSEMENT (white background)
     ↓ Trust: BD's own CEO endorses us on camera (3 webinars)
     
3.  PAIN POINTS (6 cards, light bg)
     ↓ Empathy: "Sound familiar? We've heard it all."
     
4.  CLIENT PROOF (white — logos + 169+ review screenshots)
     ↓ Receipt: 16 client logos + 11 testimonial screenshots
     
5.  STATS STRIP (dark background)
     ↓ Numbers: 500+ sites, 169+ reviews, 10+ years, 20+ devs
     
6.  SERVICES STACK (Razorpay-style stacking, light bg)
     ↓ Offerings: 6 stacking cards with tabs
     
7.  PATH CHOOSER (white)
     ↓ Self-select: Just Starting / Growing / Serious about $1M
     
8.  TEAM PHILOSOPHY (gradient — subtle coral/purple)
     ↓ Trust the team: "Forged, not hired" — dev selection + mantra
     
9.  "MADE $1M THEMSELVES" (dark background)
     ↓ Trust the founder: Yakin's story, BusinessLabs 0→50+ devs
     
10. FINAL CTA (coral gradient)
     ↓ Convert: Get Started / WhatsApp / Zoom Clinics
     
11. FAQ (white-to-bg gradient) — 10 questions with schema markup
12. FOOTER
```

### Section Details

**Section 1 — Hero**
- Headline: "The only directory team that gets you from **0→1→10→100…$1M**"
- Sub: "Brilliant Directories developers who think like co-founders—with directory website expert judgment baked in. Backed by a founder who's done it himself. The only Brilliant Directories team where strategy isn't an add-on — it's how we build."
- Badges: 🏅 Gold Certified · 🤖 Claude Partner · 🌍 500+ Sites
- AI strip: 2× Faster Delivery | 24/7 AI Agents Fleet
- CTAs: `[Explore Services]` `[Join Free Zoom Clinic]`

**Section 2 — CEO Endorsement**
- Badge: 🏅 Recommended by Brilliant Directories' CEO
- Headline: "Don't take our word for it. **Hear from the source.**"
- Body: Jason (BD CEO) endorsed BDGS across 3 official webinars. When the platform's own CEO recommends a team — that says everything.
- Right side: Embedded 30-60 sec BD CEO webinar clip
- CTAs: `[Explore Services]` `[Read 169+ Reviews]`

**Section 3 — Pain Points (6 cards)**
See [§11 Copy Decisions](#s11-copy-decisions) for approved copy per card.

**Section 4 — Client Proof**
- Heading: "We Made These Directories **~~Happy~~ VERY HAPPY**" (strikethrough on "Happy")
- Sub: "Clients say we feel like in-house Brilliant Directories experts—not a ticket queue."
- 16 client logos (grayscale by default, full color on hover)
- 11 testimonial screenshots (clickable → `/blabs-review`)
- Link: "See all 169+ reviews →"

**Section 5 — Stats Strip (dark)**
| Stat | Label |
|------|-------|
| 500+ | Directory Sites Served |
| 169+ | Verified Reviews |
| 10+ | Years in Directory Ecosystem |
| 20+ | In-House Developers |

**Section 6 — Services Stacking Section**
Title: "Everything you need to grow your Brilliant Directories site"
Tabs: Setup · Dedicated Devs · AI Development · Solutions · Tools & Themes · Growth Plan

Each tab maps to a stacking card (6 cards, Razorpay-style CSS sticky).
Cards 1 (Setup) and 2 (Dedicated Devs) have internal 3-tier sub-cards. See [§6](#s6-three-tier-pattern).

**Section 7 — Path Chooser**
Title: "Where are you on your Brilliant Directories journey?"

| Path | Target | CTA |
|------|--------|-----|
| 🚀 Just Starting | `/setup` | Start Setup → |
| ⚡ Growing & Need Firepower | `/hire-developer` | Hire a Developer → |
| 🎯 Serious About $1M | `/founders-track` | Apply for Founder's Track → |

**Section 8 — Team Philosophy**
Title: "Your directory deserves a team that thinks like founders — because every one of ours is trained to."

Body:
- We select handful from thousands → 3-month training beyond code
- "Brilliant Directories developers' training is not a slideshow — it is supervised shipping until quality is reflexive."
- Each dev carries playbook of 500+ directories; suggests what works before you ask

Mantra callout (bordered box):
> "If something doesn't work for you — that's our responsibility to fix. Adding value is our job. The final call is always yours." — The BDGS team mantra

CTAs: `[Meet the Team →]` `[Read Our Story →]`

**Section 9 — "Made $1M Themselves"** (dark)
- Quote card: "I need a team that's made $1M themselves."
- Body: BusinessLabs scaled from solo → 50+ devs serving 500+ sites. Yakin Shah. 7-figure revenue. Same strategy, systems, AI tools now built for you.

**Section 10 — Final CTA** (coral gradient)
- Title: "Ready to talk about your directory site?"
- Sub: "Tell us where you are, what you need, and how fast you want to move. Our team will route it to the right person."
- CTA: `[Get Started]` → opens inquiry modal

---

## §5 Service Taxonomy {#s5-service-taxonomy}

### Core Distinction

| Category | Definition | Pricing psychology | Examples |
|----------|-----------|-------------------|---------|
| **Services** | People-hours, ongoing or project | Proposals, retainers | Setup, Dedicated Dev, Maintenance |
| **Solutions** | Done-FOR-you, scoped outcomes, expert implements | $199–$499+ one-time | Schema, Homepage Design, SEO Audit |
| **Tools** | Productized software, self-serve or we install | One-time or subscription | BrilliantChat, future SaaS |
| **Themes** | Design templates, apply to site | One-time per theme | 6 premium themes |

**Key rule:** Never call a done-for-you implementation a "tool" — the word anchors low price expectations. "Solutions" frames the value correctly.

### Solutions — 8 Hub Categories (LOCKED)

| Hub | Slug | What's inside |
|-----|------|--------------|
| SEO & Schema | `/solutions/seo` | All 7 schema implementations + SEO audits |
| Lead Generation & Conversion | `/solutions/lead-gen` | Lead Gen on Search Results, Smart CTAs, Claim Listings |
| Member Profile Enhancement | `/solutions/member-profiles` | Business Hours, Highlights, Custom Tabs, Profile schemas |
| Search & Discovery | `/solutions/search` | Custom filters, post type sidebars, schema for results pages |
| Page Design & Development | `/solutions/page-design` | Custom Homepage, Landing Pages, Join Pages, How-It-Works |
| Content & Engagement | `/solutions/content` | FAQ Plugin, TOC, Related Posts, Elevator Pitch |
| Integrations | `/solutions/integrations` | Calendly, Intercom, Tawk.to, Google Analytics, CRM |
| Member Management & Automation | `/solutions/member-management` | Approval workflows, Auto-downgrade, Dashboards |

Total: 8 hub pages + `/solutions` main hub + 61 individual solution pages.

### Language Rules

| Never say | Always say | Why |
|-----------|-----------|-----|
| "Buy this tool" | "Get this implemented" | Buying expertise, not a download |
| "61 tools" | "61+ solutions" | Solutions sound valuable |
| "Add to cart" | "Get started" / "Request this" | Cart = cheap e-commerce |
| "Install" | "We implement this for you" | Install = DIY |

---

## §6 Three-Tier Service Pattern {#s6-three-tier-pattern}

A consistent 3-tier structure appears in Setup and Dedicated Developer stacking cards. Applied pattern:

| Tier | Tag | Price | For Whom |
|------|-----|-------|----------|
| Zoom Clinics | 🟢 FREE | Free, drop in | Budget-tight, small questions |
| Core service | ⭐ POPULAR | Paid | Active growth, committed |
| Premium/strategic | 🟣 APPLY ONLY | By application | Founders aiming at $1M+ |

### Setup Card — 3 Tiers

| Tier | Name | Price | Key fact |
|------|------|-------|---------|
| FREE | Zoom Clinics | Free | Drop in live; learn BD with our devs |
| POPULAR | Express Setup | $900 one-time | 3-day turnaround; configuration only |
| APPLY ONLY | Founder Concierge | $3,500 by application | Pay $900 floor; if Yakin can add value in 30 min, pay $2,600 more |

- Express Setup → `/setup#express`
- Founder Concierge → `/setup#founder-concierge`
- Zoom Clinics → opens modal

**Eyebrow on Founder Concierge:** "For million-dollar directory founders"

### Dedicated Developers Card — 3 Tiers

| Tier | Name | Price | Key fact |
|------|------|-------|---------|
| FREE | Zoom Clinics | Free | Quick questions, live dev help |
| POPULAR | Dedicated BD Developer | $X,XXX/mo | AI-savvy, BD-native, long-term |
| APPLY ONLY | AI Dev Fleet | $X,XXX/mo | Multiple AI agents + supervisor, pay for one |

Pricing placeholders: fill in actual monthly rates before launch.

### Hire Forever Philosophy (for /hire-developer page)

Key argument: Your directory is a technology business. Getting a Friday-night idea to MVP in a month, not 6 months, is the difference. $5K/mo × 10 years = $600K. One great idea executed on time = $1M ARR. The math works. This content lives on `/hire-developer` hero, NOT on homepage.

---

## §7 Full Site Sitemap {#s7-sitemap}

### Tier 1 — Core (Build First)

**Top-level:**
- `/` — Homepage (V7 ready)
- `/about` — Who we are, founder story
- `/contact` — Contact form, WhatsApp, email
- `/login` — Client portal entry

**Services:**
- `/services` — Services hub
- `/setup` — Setup & Launch (3 tiers)
- `/hire-developer` — Dedicated Developers (3 tiers)
- `/customization` — Custom Projects (one-shot scoped work)
- `/maintenance` — Maintenance Plans
- `/consultation` — Paid hourly with Yakin's team
- `/founders-track` — Founder's Track (apply only; renamed from Growth Plans)
- `/zoom-clinics` — Free Zoom Clinics

**Grow with AI:**
- `/grow-with-ai` — AI hub (1 hub with 4 sections)
- `/ai-development` — AI project builds (one-shot AI scoped work)
- `/ai-for-your-site` — AI built into your directory
- `/ai-dev-fleet` — AI Dev Fleet product
- `/bd-automation` — Directory automation product
- `/seo-growth` — SEO services

**Solutions:**
- `/solutions` — Solutions main hub
- `/solutions/seo` — SEO & Schema hub
- `/solutions/lead-gen` — Lead Gen & Conversion hub
- `/solutions/member-profiles` — Member Profile hub
- `/solutions/search` — Search & Discovery hub
- `/solutions/page-design` — Page Design hub
- `/solutions/content` — Content & Engagement hub
- `/solutions/integrations` — Integrations hub
- `/solutions/member-management` — Member Management hub
- `/solutions/[slug]` × 61 — Individual solution pages

**Tools & Themes:**
- `/tools` — Productized software hub
- `/tools/brilliantchat` — BrilliantChat product page
- `/themes` — All 6 themes
- `/themes/[slug]` × 6 — Individual theme pages

**Content / Proof:**
- `/blog` — Blog index
- `/blog/[slug]` × 30–50 — Blog posts
- `/blabs-review` — 169+ client reviews page
- `/case-studies` — Detailed client wins
- `/webinars` — BD CEO webinars + BDGS content

**Legal:**
- `/terms`
- `/privacy`
- `/license/sdcl-v1` — Single Domain Commercial License

### Tier 2 — Later

- `/help` — Self-serve help center
- `/community` — BD owner Slack/forum (when launched)
- `/affiliate` — Referral program
- `/partners` — Partner program hub (3 tiers)
- `/partners/referral`
- `/partners/white-label`
- `/partners/directory-operator`
- `/docs/*` — LLM crawl content
- `/sitemap.xml` — SEO sitemap only

### Dropped Pages

| Page | Decision |
|------|----------|
| `/welcome` | Killed — homepage does the job; send UTM-tagged homepage link to leads |
| `/api-docs` | Dropped — not an API company |
| `/changelog` | Dropped — not Stripe |
| `/press` | Dropped — build when you have press |
| `/partners` page (generic) | Kept but structured under 3 tiers above |
| `/careers` | Lives on BusinessLabs.org, not BDGS |

---

## §8 Partner Program {#s8-partner-program}

### 3 Tiers (LOCKED)

| Tier | Who | What they get | What BDGS gets |
|------|-----|--------------|----------------|
| **Referral Partner** | Anyone (existing clients, influencers) | Commission on referred work | Passive sales force |
| **White-Label Partner** | Agencies who want our team behind their brand | Wholesale rates; their brand front, our delivery back | Recurring high-margin work |
| **Directory Operator Partner** | BD site owners who want to monetize their members | BDGS handles end-to-end delivery; they chase sales from their listed businesses | Distribution compounding |

**Directory Operator Partner** explained: A BD site owner with hundreds of listed members (dentists, lawyers, restaurants) can sell web/SEO/marketing services to those members. BDGS delivers. Revenue share. This is a distribution channel that compounds — every BD owner converted becomes a sales arm.

---

## §9 Zoom Clinics {#s9-zoom-clinics}

### Positioning

**What it is:** Free live Zoom sessions where BDGS developers help BD site owners on small questions. No booking. Drop in.

**Rule:** Small, silly things → Zoom Clinics, always free. Strategy questions → Founder Concierge or paid consultation.

**Lead magnet logic:** Replaces the killed $100 free credits offer. Attracts serious people with real problems (not freebie hunters). Conversion path: Join free session → Experience team expertise → Become a paying client.

### Schedule

- **Days:** Tuesdays & Thursdays
- **Time (IST):** 6:30 PM — 7:30 PM (hard promise to clients)
- **Buffer:** Block 6:30–8:30 PM internally. If slow, devs log off at 7:30 PM. Hard stop at 8:30 PM — non-negotiable.
- **Shown in card:** "(+30 min if needed)" — client-facing soft buffer language

### Timezone Display (JS — LOCKED)

Use the `bdgsRenderZoomClinicsLocalTime()` function (already in V7 HTML). It:
- Anchors to 6:30 PM Asia/Kolkata
- Auto-detects user's timezone via `Intl.DateTimeFormat()`
- Shows IST only if user is in India
- Shows user's local time only (e.g., "8:00 AM EST") for all other zones
- DST auto-handled by browser (EDT vs EST, BST vs GMT, etc.)
- Falls back to "6:30 PM IST" if JS fails

### Registration (Modal approach — LOCKED)

- CTA "Join Next Session" → opens Zoom Clinics modal
- Form fields: Name, Email, Directory URL (optional), "What do you need help with?" (optional)
- On submit: inline thank-you inside same modal (no page redirect)
- Pixel firing: Google Analytics event + Facebook Pixel + LinkedIn on form submit — no thank-you page needed
- Pixel code pattern: `gtag('event', 'zoom_clinic_signup', {...}); fbq('track', 'Lead', ...)`
- Backend wiring (email/CRM) to be wired post-design phase

### Where Zoom Clinics appears

- Main nav (pill button with green pulse)
- Services dropdown (bottom highlight strip)
- Grow with AI dropdown (right column)
- Homepage Final CTA section
- Footer (Services column)
- Both Setup and Dedicated Dev stacking cards (as Tier 1 FREE option)

---

## §10 AI Positioning & Products {#s10-ai-positioning}

### Core AI Narrative

"What took years now takes months. What took months now takes weeks. And we pass every saving to you."

"The only agency that proudly says: we use AI — not to replace quality, but to multiply it."

BDGS is **AI-native**. Claude / Anthropic Service Partner badge is a unique competitive moat — no other BD team has this.

### AI Products (LOCKED)

| Product | URL | What it is |
|---------|-----|-----------|
| AI-Powered Development | `/ai-development` | Scoped AI build projects — chatbots, agents, custom AI search. One-shot. Not ongoing. |
| AI Dev Fleet | `/ai-dev-fleet` | Multiple AI agents + human supervisor working on your directory. Pay for one developer. |
| AI for Your Directory | `/ai-for-your-site` | AI embedded IN your site: chatbots, smart search, AI-driven member flows |
| Directory Automation | `/bd-automation` | Follow-up sequences, lead gen, content AI, CRM syncing |
| BrilliantChat | `/tools/brilliantchat` | BDGS's own AI chatbot product for BD sites |

### AI Page Architecture

```
/grow-with-ai (HUB — 4 sections)
   ├── AI that WORKS FOR YOU → /ai-for-your-site
   ├── AI that BUILDS FOR YOU → /ai-dev-fleet
   ├── AI that AUTOMATES → /bd-automation
   └── SEO with AI → /seo-growth (separate because not pure AI)
```

`/ai-development` is a SEPARATE standalone page for scoped AI project intake — different from the ongoing `/ai-dev-fleet` relationship.

---

## §11 Copy & Messaging Decisions {#s11-copy-decisions}

### Pain Cards — Approved Copy (6 cards)

**Card 1 — "I Bought Brilliant Directories... now what?"**
```
You expected plug-and-play. Reality hit.
We take you from:
purchase → launch → first paying member.
```
CTA: Start your setup →

**Card 2 — "This isn't as no-code as I thought."**
```
Our team handles the technical parts.
You handle the vision.
We handle everything else.
```
CTA: Get a dedicated developer →

**Card 3 — "I need a team that's actually done this before."**
```
BusinessLabs grew from solo to 50+ devs serving 500+ sites.
Every dev on your account trained under that playbook.
1000+ Brilliant Directories features shipped — yours next.
```
CTA: Meet the team →

**Card 4 — "I don't want to explain my business to every new dev."**
```
Hire a dedicated Brilliant Directories developer who knows your codebase, your vision, your niche.
One team. Forever.
```
CTA: Hire for the long term →

**Card 5 — "I want a site I'd be proud to pitch to investors."**
```
We've shaped pivots, sharpened pitches, refined business models with founders.
Some raised funding. Some hit 7 figures.
Wherever you're aiming — we've helped someone get there.
```
CTA: See solutions →

**Card 6 — "I need AI but I don't know where to start."**
```
We don't just use AI — we build it INTO your directory.
Chatbots. Agents. Smart search.
Automation that removes real busywork.
```
CTA: Grow with AI →

### SEO Keyword Strategy

Target keywords naturally woven in (not stuffed):
- "Brilliant Directories developers" (primary)
- "directory website expert"
- "bd automation"
- "business directories developers"

Apply "Brilliant Directories" explicitly in service page H1s when those pages are built.

5 specific heading updates made in V6:
1. "Everything you need to grow your **Brilliant Directories** site"
2. "Launch Your **Brilliant Directories** Site" (stacking tab)
3. "Where are you on your **Brilliant Directories** journey?"
4. Card 4: "dedicated **Brilliant Directories** developer"
5. Hero sub: "**Brilliant Directories** developers who think like co-founders"

### Naming Conventions (LOCKED)

| Old name | New name | Why |
|----------|----------|-----|
| Growth Plans | **Founder's Track** | "Partnership" confused with equity; "Track" = program you apply to |
| Setup by Devs | **Express Setup** | Focuses on speed (client benefit) not who does it |
| Setup with Yakin | **Founder Concierge** | Works for cold visitors; signals premium |
| Tools (61 items) | **Solutions (Done-For-You)** | "Tool" anchors commodity price |
| Growth Plan / apply | **Founder's Track** | Slug: `/founders-track` |

---

## §12 Technical Implementation {#s12-technical}

### Current HTML State

| Version | Location | Status |
|---------|----------|--------|
| V6 | `home-hero-v6/index.html` in GitHub | User-edited, best baseline |
| V7 | `home-hero-v7/index.html` | V6 + user's own edits in Cursor |
| Taxonomy MD | `home-hero-v6/hubspokes-and-taxonomy-from-claudeopus47.md` | Strategic document |

Live draft URLs: `https://businesslabshq.github.io/bdgs-website-v2-drafts/home-hero-v[n]/`

### V7 Known Bugs (Fixed)

**Bug 1 — Unclosed tier card in Setup stacking card:**
After the Zoom Clinics `bdgsownv2-clinic-time` div, a closing `</div>` (for the tier card) and the CTA button were missing. This broke the grid layout of the Setup card.

**Fix:** After `<span class="bdgsownv2-clinic-buffer">(+30 min if needed)</span></div>`, add:
```html
<div class="bdgsownv2-tier-cta"><button type="button" class="free-cta" onclick="bdgsOpenZoomModal()">Join Next Session →</button></div>
</div>
```

**Bug 2 — Two competing timezone scripts:**
V7 had TWO `<script>` blocks targeting `#bdgs-zoom-clinics-local-time`. The second (older, simpler) one ran after the better one and overrode it, appending "(6:30 PM IST)" for non-India users.

**Fix:** Delete the entire second `<script>` block (the one starting with `// Zoom Clinic auto-timezone display` using hardcoded `13:00 UTC`). Keep only the `bdgsRenderZoomClinicsLocalTime()` function.

### Stacking Cards (Known UX Issue — Deferred)

The Razorpay-style CSS sticky stacking cards have two remaining visual issues:
1. Setup card (tallest, with 3 sub-tiers) leaks below shorter stacking cards
2. When scrolling past the last card, all cards momentarily un-stack before the section exits

**Decision:** These are edge cases. The stacking works for most users. Real developer to fix these issues using the existing CSS sticky architecture. Do not attempt blind code changes without browser testing capability.

### Stacking Card Active Tab Logic

The active tab JS (`bdgsUpdateActiveTab`) works as follows:
- On desktop: compares each card's `getBoundingClientRect().top` against its CSS `sticky top` value (array: `[140, 190, 240, 290, 336, 368]`)
- Uses strict band (22px) and loose band (40px) for sub-pixel browser variance
- Falls back to document probe if no sticky match found
- Tab click: smooth scrolls to card with scroll offset = 168px (desktop) / 112px (mobile)
- Uses `requestAnimationFrame` for performance, throttled on scroll

### CSS Architecture Notes

```css
/* The key rule that must NOT be on the stacking section parent: */
.bdgsownv2-section { overflow: hidden; } /* This kills position:sticky! */

/* Override for services stack section: */
.bdgsownv2-section.bdgsownv2-services-stack { overflow-x: visible !important; overflow-y: visible !important; }

/* Sticky top values per card — must match JS array above */
.bdgsownv2-stack-cards > .bdgsownv2-stack-card:nth-child(1) { z-index: 1; top: 140px; }
.bdgsownv2-stack-cards > .bdgsownv2-stack-card:nth-child(2) { z-index: 2; top: 190px; }
/* ... through nth-child(6) at top: 368px */
```

### Lead Intake Structure

**Global inquiry modal (`bdgsOpenInquiryModal()`):**
- Fields: Name, Email, Phone/WhatsApp, Directory URL, Need (dropdown), Message
- On submit: inline thank-you state (no page redirect)
- Automation wiring (email/CRM/Zapier) to be done post-design phase

**Zoom Clinics modal (`bdgsOpenZoomModal()`):**
- Placeholder content for session schedule
- CTAs: "See full schedule →" + "Close"

**Page-specific lead forms:** Every service page should have its own inline form scoped to that service's intake questions. The global modal stays for general visitors.

### BD MCP Integration

Official BD MCP exists: `https://github.com/brilliantdirectories/brilliant-directories-mcp`
- 164 API operations: members, posts, reviews, leads, categories, pages, widgets, menus
- Rate limit: 100 req/60s (up to 1,000/min available)
- Install: `npx brilliant-directories-mcp --setup`
- Verify: `npx brilliant-directories-mcp --verify --api-key YOUR_KEY --url https://your-site.com`

**Decision:** BD MCP integration deferred until design phases are complete. Not needed for website frontend work.

---

## §13 Parked for Later {#s13-parked}

Items explicitly deferred — not dropped. Track these.

| # | Item | Context |
|---|------|---------|
| 1 | **Stacking card leak + un-stacking fix** | Needs real developer with browser testing access |
| 2 | **Custom Projects (`/customization`) in stacking section** | Whether to add as Card 2 in stacking or keep only in Services dropdown |
| 3 | **Inline project intake form for `/customization`** | Specific fields for scoped project work |
| 4 | **Dedicated Developers pricing** | Replace `$X,XXX/mo` placeholders with real prices |
| 5 | **AI Dev Fleet pricing** | Replace `$X,XXX/mo` placeholder |
| 6 | **Zoom Clinics modal content** | Schedule, registration backend, confirmation emails |
| 7 | **Pixel / conversion tracking wiring** | After design phase; wire Google/Facebook/LinkedIn pixels on form submit |
| 8 | **Inquiry modal backend** | Email/WhatsApp/SMS automation via Zapier/Make/API |
| 9 | **Hero Option 1 (Dark Standalone)** | Keep as draft for potential use on `/about` or `/grow-with-ai` page evaluation |
| 10 | **`/welcome` page` final removal** | Confirm deletion after homepage goes live |
| 11 | **Hub spoke tabs in stacking — 7 vs 6 cards** | Whether Custom Projects gets its own stacking card |
| 12 | **BDAutomate as separate brand** | Deferred until BDGS tools generate independent revenue |

---

## §14 Build Sprints {#s14-sprints}

### Sprint 1 — Must-haves for soft launch

1. `/` Homepage — V7 HTML (fix 2 known bugs first)
2. `/setup` — 3-tier offering page (Zoom Clinics, Express, Founder Concierge)
3. `/hire-developer` — 3-tier offering page + Hire Forever section
4. `/about` — Founder story, team, mission
5. `/contact` — All contact channels
6. `/zoom-clinics` — Schedule, how it works, registration
7. `/terms` + `/privacy`

### Sprint 2 — Depth

8. `/grow-with-ai` hub
9. `/solutions` hub + 8 category hubs
10. `/tools` + `/themes`
11. `/founders-track` application page
12. `/consultation`
13. `/blog` index + blog post template

### Sprint 3 — Proof & Trust

14. `/case-studies` (3–5 detailed wins with metrics)
15. `/blabs-review` (169+ reviews page)
16. `/webinars` (BD CEO endorsement library)
17. `/maintenance` plans page

### Sprint 4 — Sub-pages

18. All 61 `/solutions/[slug]` pages (existing on live site, migrate/redesign)
19. All 6 `/themes/[slug]` pages
20. All blog posts + new SEO-targeted posts

### Sprint 5 — Optional

21. `/help` center
22. `/community`
23. `/partners` hub + 3 tier pages
24. `/affiliate`
25. `/docs/*` for LLM crawling
26. `/sitemap.xml`

---

*Document compiled from full Claude AI design engagement session. All brainstormed-then-abandoned paths removed. Last updated: June 2026.*


---

## Changelog

- 2026-07-02 — Migrated into the BD Growth Suite production project as the living source of truth. Original design-phase content preserved.
