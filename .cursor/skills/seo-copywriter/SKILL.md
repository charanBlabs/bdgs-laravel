---
name: seo-copywriter
description: Write page copy, FAQ answers, and blog posts for BD Growth Suite that rank on Google AND read like a real human wrote them (never like AI), using the locked keyword map. Use when writing or rewriting any customer-facing copy. Triggered by "write copy for /page", "rewrite this section", "draft the FAQ", or "write a blog post".
---

# SEO Copywriter

Write copy that ranks and sounds human. The first sentence under each heading is what AI tools quote — make it a direct answer.

## Inputs required before writing

1. Which page + funnel (from `data/seo-pages.json`).
2. Keyword brief from `docs/seo-keyword-map.md` (H1, exact phrases, FAQ questions in user wording).
3. Any speed constraint from `seo-pagespeed` (e.g. no huge hero video).

## Never use these AI-tell words

"In today's fast-paced world", unlock, elevate, delve, robust, seamless, leverage, game-changer, moreover, furthermore, in conclusion.

No em-dash triplets. No "Whether you're X or Y". No rhetorical-question openings.

## Do use

Short sentences mixed with longer ones. Real numbers, real places, real prices. "You/your". Active voice. Specifics over adjectives. State what the offer is NOT, then what it IS, then prove it with a number.

## BD Growth Suite brand voice (locked)

**Voice:** anti-fluff, oppositional (say what we're NOT, then what we ARE, then a number), founder-savvy (talk to business operators, not "users"), outcome-focused, concrete.

**Positioning:** the service arm of BusinessLabs serving Brilliant Directories site owners. A growth partner — not a freelancer, not an agency.

**Tagline (locked):** "The only directory team that gets you from 0->1->10->100...$1M".

**Never use (customer-facing):**

| Banned | Use instead |
|---|---|
| "BD" (abbreviation) | "Brilliant Directories" (full name, every time) |
| "buy this tool" / "add to cart" / "install" | "get this implemented" / "get started" / "we implement this for you" |
| "61 tools" | "61+ solutions" / "Solutions (Done-For-You)" |
| "users" | "directory owners" / "founders" / "clients" |
| "affordable" / "cheap" | state the price directly, or "investment" |
| "comprehensive" / "robust" / "seamless" / "cutting-edge" | describe the actual, specific benefit |

**Locked product names:** Founder's Track (not "Growth Plans"), Express Setup (not "Setup by Devs"), Founder Concierge (not "Setup with Yakin"), Solutions (Done-For-You) (not "Tools").

**Proof points (use naturally, don't force all onto one page):** Gold Certified Brilliant Directories Partner · Claude/Anthropic Service Partner · 500+ directory sites served · 176+ verified reviews · 10+ years in the directory ecosystem · 20+ in-house developers.

**CTAs:** verb + benefit + arrow ("Start Your Setup ->", "Read 176 reviews ->", "Apply for Founder's Track ->"). Never bare "Submit" or "Click Here". Match the CTA to the page.

## Structure

- One `<h1>` with the primary phrase.
- First paragraph answers the core question in 1–2 sentences (the AI-quotable line).
- `<h2>` sections = real search questions from the keyword map. First line under each = a direct answer.
- FAQ using the exact user phrasing from keyword research.
- 3–5 internal links to other funnel pages with descriptive anchor text.
- Use the full platform/brand name naturally where the keyword map calls for it — never keyword-stuff.

## Output

Return: full copy + which file it goes in + which JSON-LD to add (see `seo-page-audit` for the schema templates). Then hand to `seo-page-audit`.

## Example — answer-first rewrite

Bad:
> ## Pricing overview
> We offer a range of flexible, robust packages to suit every need.

Good:
> ## How much does setup cost?
> Express Setup is $900 one-time with a 3-day turnaround. Founder Concierge starts at $900 and is application-only for founders scaling past $1M.

## Ship gate

Every piece of copy must pass `seo-page-audit` before it goes live.
