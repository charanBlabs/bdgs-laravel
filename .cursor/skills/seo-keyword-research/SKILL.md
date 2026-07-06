---
name: seo-keyword-research
description: Find the exact words and questions real people type into Google and AI tools for Brilliant Directories services, and map each to a specific BD Growth Suite page URL. Produces the locked keyword map at docs/seo-keyword-map.md. Use once at project start (in a dedicated chat), when adding a new page type, or when entering a new market. Triggered by "run keyword research", "build the keyword map", or "find keywords for {page}".
---

# SEO Keyword Research

Return a **locked keyword map**, not guesses. Every phrase maps to one page URL from `data/seo-pages.json`.

## When to run

Once, in a dedicated chat, at project start. Rerun only for a new page type or a new market. Page building afterward uses the locked map — it does not re-run this.

## Method (use the browser)

Do real research; never invent phrases.

**A. Google autosuggest** — go to google.com, type each seed slowly, and capture every dropdown suggestion. Seeds: your product category, `hire`, `cost` / `price`, `best`, `for small business`, `expert`, `agency`, `near me`, `how to`. Also append each letter `a`–`z` to the main seeds.

**B. People Also Ask / Related searches** — press Enter, scroll to the bottom of results, capture "People also ask", "People also search for", and "Related searches". These are real questions.

**C. AI question mining** — note how people phrase questions to ChatGPT/Claude: "how do I…", "is X worth it", "best X", "X vs Y". Capture the wording verbatim.

**D. SERP intent validation** — Google the top 2–3 candidate primary keywords and check page-1 results. Confirm the SERP intent matches the target page type (e.g. a "partner reviews" page should not chase a keyword whose SERP is dominated by software-review sites or Reddit threads about the platform itself). Move mismatched keywords to the "Do not primary-target" section with a one-line reason.

## Output — write to `docs/seo-keyword-map.md`

```markdown
## Target map — {topic} ({funnel})

### Primary keywords (high intent, confirmed)
- "exact phrase" — found via {source} — maps to /url

### Do not primary-target on {page} (intent mismatch)
- "phrase" — reason SERP doesn't match our page type

### Question targets (for FAQs and blog)
- "exact question" — found via {source} — answer on /url or blog

### Related searches (supporting phrases)
- "phrase" — found via {source} — maps to /url (secondary use only)

### Copywriter brief
- H1: {confirmed headline}
- Use these exact phrases in headings + body: ...
- Disambiguate from: {what the page is NOT about}
- FAQ questions to add (verbatim user wording): ...
- Internal links: /page1, /page2

### Gaps / notes ({cluster name})
- Observations about volume, competition, or missing pages
```

## Rules

- Every keyword maps to a page URL that exists in `data/seo-pages.json`. If no page fits, flag it as a gap for SEO Guru — do not invent a keyword with no home.
- Note the source (autosuggest / PAA / AI) for each phrase so it is traceable.
- If a keyword's SERP results serve a different intent than the target page, move it to "Do not primary-target" with a one-line reason.
- Include a "Disambiguate from" line in the copywriter brief when the topic is easily confused with a competitor, the platform itself, or an adjacent concept.
- When the map is complete, tell the user: **"Ready for Yakin to review and lock."** After lock, mark the top of the file `STATUS: LOCKED {date}`.

## Feeds

`seo-copywriter` (briefs) and `seo-content-topics` (clusters) both read the locked map.
