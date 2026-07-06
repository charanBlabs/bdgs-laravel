> **Migration note.** Reference doc from the BD Growth Suite design phase. Historical paths like `local-html/` and `home-hero-v*` refer to the original working repo; in this project, finalized pages live at `/{slug}/index.html`.

# `/services` Page — Final Locked Layout

**Document type:** Page layout specification for `/services`
**Prepared by:** Claude Opus 4.7 (Anthropic) in collaboration with Yakin Shah
**Status:** Final — approved for Sprint 1 build
**Related docs:** `hubspokes-and-taxonomy-from-claudeopus47.md`

---

## Page Purpose

A visual navigation page. Entire page fits in one viewport — no scroll needed on desktop.
Visitor lands → sees all 8 service paths instantly → clicks their path → exits to the right page.

**No hero section. No FAQ. No proof section. No testimonials. Just title + subtitle + 8 cards + one soft link.**

---

## SEO

| Field | Value |
|---|---|
| URL | `/services` |
| H1 | Everything we do for your Brilliant Directories site |
| Meta title | Brilliant Directories Services — BD Growth Suite |
| Meta description | Setup, dedicated developers, AI, custom projects, solutions, themes, maintenance, and Founder's Track. Every service we offer for Brilliant Directories site owners. |
| Primary keyword | Brilliant Directories services |
| Internal link role | Hub linking to 8 service pages — distributes internal link equity |

---

## Full Page Layout (visual preview)

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                                                                                  │
│   Everything we do for your Brilliant Directories site                           │
│                                                                                  │
│   Pick where you need help. Each path has its own team, pricing, and process.   │
│                                                                                  │
├──────────────────┬──────────────────┬──────────────────┬──────────────────┤
│                  │                  │                  │                  │
│  🚀              │  👩‍💻              │  🤖              │  🔧              │
│  Setup & Launch  │  Hire a          │  AI Development  │  Custom Projects │
│                  │  Developer       │                  │                  │
│  Go from 0 to    │  Long-term BD    │  Build AI into   │  One-shot builds │
│  live in 3 days  │  dev, permanent  │  your directory  │  scoped & priced │
│                  │  team member     │                  │                  │
│  Explore →       │  Explore →       │  Explore →       │  Explore →       │
│                  │                  │                  │                  │
├──────────────────┼──────────────────┼──────────────────┼──────────────────┤
│                  │                  │                  │                  │
│  ✅              │  🎨              │  🛠️              │  🎯              │
│  Solutions       │  Themes          │  Maintenance     │  Founder's Track │
│  Done-For-You    │                  │  Plans           │                  │
│                  │  6 premium       │                  │  Apply-only.     │
│  61+ pre-priced  │  industry-       │  Ongoing support │  Strategy + dev  │
│  services, we    │  specific        │  + backups       │  + AI. With      │
│  implement       │  designs         │                  │  Yakin.          │
│                  │                  │                  │                  │
│  Explore →       │  Explore →       │  Explore →       │  Explore →       │
│                  │                  │                  │                  │
└──────────────────┴──────────────────┴──────────────────┴──────────────────┘

        Not sure where to start? → Book a free 30-min Discovery Call
```

---

## Card Specifications

| # | Icon | Title | One-liner | Links to |
|---|---|---|---|---|
| 1 | 🚀 | Setup & Launch | Go from 0 to live in 3 days | `/setup` |
| 2 | 👩‍💻 | Hire a Developer | Long-term BD dev, permanent team member | `/hire-developer` |
| 3 | 🤖 | AI Development | Build AI into your directory | `/grow-with-ai` |
| 4 | 🔧 | Custom Projects | One-shot builds scoped & priced | `/customization` |
| 5 | ✅ | Solutions Done-For-You | 61+ pre-priced services, we implement | `/solutions` |
| 6 | 🎨 | Themes | 6 premium industry-specific designs | `/themes` |
| 7 | 🛠️ | Maintenance Plans | Ongoing support + backups | `/maintenance` |
| 8 | 🎯 | Founder's Track | Apply-only. Strategy + dev + AI. With Yakin. | `/founders-track` |

---

## Layout Rules

- **Desktop:** 4 cards per row × 2 rows = 8 cards total. Entire page in viewport. No scroll.
- **Tablet:** 2 cards per row × 4 rows. Light scroll acceptable.
- **Mobile:** 1 card per row × 8 rows. Full stack. Scroll expected.
- **Card height:** Equal across all 8. Enforce with CSS `display: grid` + `align-items: stretch`.
- **No sticky header overlap issue** — page is short, no deep anchor links on this page.

---

## Page Sections (in order)

### 1 — Title + Subtitle (centered, no bg image)

```
H1: Everything we do for your Brilliant Directories site

Sub: Pick where you need help.
     Each path has its own team, pricing, and process.
```

Background: `var(--bdgs-gradient-subtle)` — same light coral/purple gradient as homepage hero.
Padding: `60px 0 40px`. No badges. No trust strip. Minimal.

---

### 2 — 8 Cards Grid

CSS Grid. 4 columns desktop, 2 tablet, 1 mobile.
Each card:
- White background
- 1px border `var(--bdgs-border)`
- Border-radius 14px
- Hover: coral border + slight lift + shadow
- Icon (emoji, 28px)
- Title (17px bold, `var(--bdgs-dark)`)
- One-liner (13px, `var(--bdgs-text-muted)`)
- "Explore →" link (13px bold, `var(--bdgs-coral)`)

No pricing on this page. Just routing.

---

### 3 — Soft Bottom Link (not a full CTA section)

```
Not sure where to start?
→ Book a free 30-min Discovery Call  [opens inquiry modal]
```

One line. Centered. Small text. Opens the global Inquiry Modal (same as everywhere else on site).

---

## What This Page Is NOT

- ❌ Not a full landing page with hero + proof + testimonials
- ❌ Not a pricing page
- ❌ Not a comparison page
- ❌ Not a blog or content page
- ❌ Not a page that needs scroll to see all options (desktop)

---

## Links INTO This Page

| Source | Link text |
|---|---|
| Homepage Hero — "Explore Services" CTA | `Explore Services →` → `/services` |
| Header nav — "Services" item (mobile fallback) | href="/services" |
| Footer — (no direct link; footer links to individual service pages) | — |

---

## Links OUT of This Page

| Card | Destination |
|---|---|
| Setup & Launch | `/setup` |
| Hire a Developer | `/hire-developer` |
| AI Development | `/grow-with-ai` |
| Custom Projects | `/customization` |
| Solutions Done-For-You | `/solutions` |
| Themes | `/themes` |
| Maintenance Plans | `/maintenance` |
| Founder's Track | `/founders-track` |
| Discovery Call (soft link) | Opens global Inquiry Modal |

---

## Schema Markup

```json
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Brilliant Directories Services — BD Growth Suite",
  "description": "All services offered by BD Growth Suite for Brilliant Directories site owners.",
  "url": "https://bdgrowthsuite.com/services",
  "provider": {
    "@type": "Organization",
    "name": "BD Growth Suite by BusinessLabs",
    "url": "https://bdgrowthsuite.com"
  },
  "mainEntity": {
    "@type": "ItemList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Setup & Launch", "url": "https://bdgrowthsuite.com/setup" },
      { "@type": "ListItem", "position": 2, "name": "Hire a Developer", "url": "https://bdgrowthsuite.com/hire-developer" },
      { "@type": "ListItem", "position": 3, "name": "AI Development", "url": "https://bdgrowthsuite.com/grow-with-ai" },
      { "@type": "ListItem", "position": 4, "name": "Custom Projects", "url": "https://bdgrowthsuite.com/customization" },
      { "@type": "ListItem", "position": 5, "name": "Solutions Done-For-You", "url": "https://bdgrowthsuite.com/solutions" },
      { "@type": "ListItem", "position": 6, "name": "Themes", "url": "https://bdgrowthsuite.com/themes" },
      { "@type": "ListItem", "position": 7, "name": "Maintenance Plans", "url": "https://bdgrowthsuite.com/maintenance" },
      { "@type": "ListItem", "position": 8, "name": "Founder's Track", "url": "https://bdgrowthsuite.com/founders-track" }
    ]
  }
}
```

---

## Build Notes

- **Reuse:** All CSS classes from V7 homepage (`bdgsownv2-*` prefix)
- **New class needed:** `bdgsownv2-services-grid` — 4-col CSS Grid for the cards
- **Shared component:** Inquiry Modal (already built in V7 — just include the same script)
- **No new JS needed** beyond what's already in the site template
- **Build time estimate:** 1–2 hours maximum
- **Sprint:** Sprint 1 (after `/setup` and `/hire-developer`)

---

## Items Parked (Tracked)

1. Whether to add a 9th card for "Consultation" — currently Consultation is only in the nav dropdown, not in the services cards. Add if you want direct routing from this page.
2. Whether "AI Development" card links to `/grow-with-ai` (AI hub) or `/ai-development` (one-shot AI projects specifically). Currently pointing to hub. Flag before build.

---

*End of document. Build after homepage section locks are complete.*
