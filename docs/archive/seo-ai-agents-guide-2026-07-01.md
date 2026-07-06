Hi Charan,

Yakin asked me to write you one complete email — plain English, no jargon — about how we want BDgrowthsuite.com to show up on Google, inside ChatGPT/Claude/Perplexity, and later be easy for software agents to read and use. This is the same system we built for our CCAF website, written so you can use it on a static HTML site or any other stack.

Read this once end to end. Save it. When you build a page, come back to the checklist at the bottom.

---

PART 1 — WHAT ARE WE EVEN TRYING TO DO? (IN SIMPLE WORDS)

There are three different "games" happening at once. They overlap but they are not the same thing.

GAME 1 — GOOGLE SEARCH (classic SEO)

People type words into Google. We want our pages to appear on page 1 when those words match what we sell.

What helps: clear page titles, good descriptions, fast pages, useful content, links from other sites, a sitemap, and not blocking Google from reading our pages.

GAME 2 — AI ANSWERS (AEO and GEO)

People ask ChatGPT, Claude, Gemini, or Perplexity a question. The AI writes an answer. Sometimes it quotes a website. We want BDgrowthsuite to be the site that gets quoted.

AEO means Answer Engine Optimization — write so the first sentence under each heading is a direct answer a machine can lift and quote.

GEO means Generative Engine Optimization — same idea, wider term for "show up inside AI-generated answers."

Important honest note from Google (July 2026): Google says good old SEO still matters for Google Search and AI Overviews. Google does NOT use llms.txt for ranking. But ChatGPT, Claude, Perplexity, and future browsing agents DO benefit from clean text copies of our pages. So we do BOTH: proper SEO for Google, plus clean markdown copies and llms.txt for AI tools.

GAME 3 — AGENT-READY WEBSITE (future)

Soon, software agents (not humans) will browse websites, fill forms, book demos, and take actions. We want BDgrowthsuite ready for that: clear forms, labeled fields, stable URLs, plain text versions of pages, and documented APIs if we have forms on the backend.

Today we use Cursor chat agents with written "skills" (instruction files). Later (Phase 2) the same logic can run on a server every day. Phase 1 is you and Yakin chatting with Cursor on a Saturday and setting it all up.

---

PART 2 — WHAT "OPEN IN CHATGPT" AND "OPEN IN CLAUDE" MEANS

On each important public page we add a small button group (we call it CopyPageButton). It does four things:

1. Copy as Markdown — copies a clean text version of the page (from a .md file) to the clipboard.

2. View as Markdown — opens that .md file in the browser (example: yoursite.com/pricing.md).

3. Open in ChatGPT — opens ChatGPT with a pre-filled message: "Read https://yoursite.com/pricing and answer my questions about it."

4. Open in Claude — same thing for Claude.

Why this matters:

- A human can one-click send our page into an AI to ask questions.
- An AI that can browse URLs gets a clean text file (.md) that is easier to read than a messy HTML page full of menus and scripts.
- It is a simple habit: every marketing page has a twin .md file kept in sync.

On a static HTML site you can build this with a small JavaScript snippet: fetch the .md file, copy to clipboard, and build the ChatGPT/Claude links using the page URL. No fancy framework needed.

---

PART 3 — THE FILES EVERY SITE SHOULD HAVE (STATIC HTML IS FINE)

Whether you use plain HTML, WordPress, or something dynamic — these files should exist at the website root:

robots.txt

Tells search engines what they may crawl. Public marketing pages: allow. Admin, login, thank-you pages: disallow.

sitemap.xml

A list of every public page URL. Google Search Console uses this. You can hand-write it or generate it from your page list.

llms.txt

A plain text map of the site for AI tools (see llmstxt.org). One short paragraph about what the company does, then a list of pages with one-line descriptions, then links to .md mirror files. Google ignores this for ranking; AI tools find it useful.

Markdown mirrors (.md files)

For each public page: home → index.md, pricing → pricing.md, etc. Same information as the HTML page but without navigation chrome. Updated whenever the HTML page changes.

A single "page list" file (your source of truth)

On our Next.js site this is called PUBLIC_ROUTES in code. For BDgrowthsuite, keep a simple file — call it seo-pages.json or pages-list.txt — listing every public URL, its title, its main keyword, and whether it is live or planned. Every other file (sitemap, llms.txt, audits) reads from this list so nothing is forgotten.

---

PART 4 — WHAT EVERY PUBLIC PAGE MUST HAVE (CHECKLIST)

Before any page goes live, check all of these:

1. Unique page title (what shows in the Google tab).

2. Meta description — one sentence, under 160 characters, includes the main search phrase.

3. Canonical URL — the one official URL for this page (avoids duplicate content problems).

4. Open Graph tags — so links look good on WhatsApp, LinkedIn, Facebook.

5. Exactly one H1 heading — the main topic of the page.

6. The main search phrase appears in the H1 and the first paragraph.

7. H2 headings written as real questions people search (example: "How much does it cost?" not "Pricing overview").

8. First sentence under each H2 is a direct answer (AEO pattern).

9. JSON-LD structured data in a script tag — at minimum Organization + WebPage + Breadcrumb; add FAQ schema if there is an FAQ section.

10. A matching .md mirror file, kept in sync.

11. The Copy / View MD / Open in ChatGPT / Open in Claude button group.

12. Meaningful alt text on images.

13. Links to other important pages on the site (home, pricing, contact, blog, etc.).

14. Page loads fast on mobile.

15. Page is listed in sitemap.xml and llms.txt.

Pages that should NOT be indexed: login, dashboard, admin, thank-you after form submit, draft pages. Add noindex on those and leave them out of the sitemap.

---

PART 5 — THE AGENT TEAM (SKILLS) AND HOW THEY WORK TOGETHER

We do not rely on one giant prompt. We split the work into specialist "skills" — text instruction files that Cursor reads when you ask for that job. Think of them as job descriptions for AI workers. SEO Guru is the manager. The others report to it.

Here is each skill in full, written for BDgrowthsuite (not tied to any one tech stack).

---

SKILL: SEO GURU (the manager)

When to use: You type "hi SEO guru" or "run SEO" or "what should we do next for search."

Job: Overall strategist. Knows the business goal (for BDgrowthsuite: whatever Yakin sets as north star — leads, signups, demos). Maps every page and blog post to a funnel. Commands the other skills in order. Never says "maybe" or "optional" — only clear confirmed actions.

North star rule: Every recommendation must connect to a business outcome (more leads, more signups, more trust).

Source of truth it reads: your page list file, robots.txt, sitemap, llms.txt, keyword map file (see below).

Sub-skills it manages:

- seo-keyword-research
- seo-page-audit
- seo-pagespeed
- seo-copywriter
- seo-content-topics

Standard workflow when optimising one page:

1. Run keyword research for that page's topic.
2. Run page audit — list what is missing.
3. Run pagespeed if the page is already built.
4. Hand results to copywriter to write or fix copy.
5. Run page audit again. Two clean passes = page is ready.

Content engine (Phase 2 — not now): daily cron picks a blog topic, AI drafts, second AI critiques, three rounds, human approves, publish. Gated by page audit every time.

Reporting format: Confirmed change → which file → why it helps the business.

---

SKILL: SEO KEYWORD RESEARCH

When to use: Once at the start of the project in a separate Cursor chat. Also when adding a new page type or entering a new market.

Job: Find the exact words and questions real people type in India on Google and inside AI tools. Return a locked keyword map — not guesses.

How it works (the agent uses a browser):

A. Google autosuggest — go to google.com, type seed phrases slowly, capture every suggestion from the dropdown. Try seeds like your product category, city names, "how to", "best", "price", "near me", "for small business", etc. Also try each letter a-z after the seed.

B. People Also Search For — press Enter, scroll to bottom of results, capture "People also search for" and "Related searches". These are real questions people think about.

C. AI question mining — note how people phrase questions to ChatGPT: "how do I...", "is X worth it", "best X in India", "X vs Y".

Output shape (saved to a file — example: docs/seo-keyword-map.md):

## Target map — [topic] ([funnel name])

### Primary keywords (high intent, confirmed)
- "exact phrase" — found via autosuggest — maps to /pricing page

### Question targets (for FAQs and blog)
- "exact question" — found via People Also Search For — answer on /faq or blog

### Copywriter brief
- H1: confirmed headline
- Use these exact phrases in headings and body: ...
- FAQ questions to add (verbatim user wording): ...

Every keyword must map to a specific page URL from your page list. When the map is done, Yakin reviews and locks it. After lock, page building uses only mapped keywords per page.

This is Phase 1 Part A step 1: one dedicated chat, run once, store the map, lock it.

---

SKILL: SEO PAGE AUDIT

When to use: After building or editing any public page. Before saying a page is done.

Job: Pass or fail checklist. No soft language. Each fail item includes exact fix (which file, what to add).

Checklist for a public page:

1. Page is in your page list / sitemap / llms.txt.
2. Title, description (160 chars max), canonical URL, Open Graph, Twitter card tags present.
3. Exactly one H1. Target phrase in H1 and first paragraph.
4. AEO: first sentence under each question-style H2 is a direct answer. FAQ has FAQ schema if applicable.
5. JSON-LD: Organization + WebPage + Breadcrumb minimum.
6. .md mirror exists and matches the live page.
7. CopyPageButton (or equivalent) present.
8. Images have alt text.
9. Internal links to sibling pages (home, pricing, contact, etc.).
10. Page returns HTTP 200 and loads without errors.

For login/admin pages: NOT in sitemap, noindex, no .md mirror.

Output:

# Audit: /pricing
PASS: 8/10
FAILs:
- Missing .md mirror → create pricing.md
- No FAQ schema → add JSON-LD FAQ block

Re-audit after fixes. Two full passes = shippable.

---

SKILL: SEO PAGESPEED

When to use: When a page feels slow or before launch.

Job: Measure performance and list exact fixes.

How to run: Google PageSpeed Insights (free, paste your URL), or Lighthouse in Chrome DevTools (F12 → Lighthouse tab).

Report: Performance score, LCP (largest paint time), CLS (layout shift), and each failing item with a confirmed fix.

Common fixes on static sites: compress images, use width/height on images to stop layout jump, defer non-critical JavaScript, use font-display swap, minify CSS, serve images in WebP.

---

SKILL: SEO COPYWRITER

When to use: Writing or rewriting page copy, blog posts, FAQ answers.

Job: Write copy that ranks AND sounds like a real human wrote it — never like AI.

Inputs needed before writing:

1. Which page and which business funnel.
2. Keyword brief from keyword research (H1, exact phrases, FAQ questions in user wording).
3. Any speed constraints from pagespeed (e.g. no huge hero video).

Hard rules — never use these AI-tell words:

"In today's fast-paced world", unlock, elevate, delve, robust, seamless, leverage, game-changer, moreover, furthermore, in conclusion.

No em-dash triplets. No "Whether you're X or Y". No rhetorical question openings.

Do use: short sentences mixed with longer ones, real numbers, real places, rupee amounts if relevant, "you/your", active voice, specifics.

Structure:

- One H1 with primary phrase.
- First paragraph answers the core question in 1-2 sentences (this is what AI quotes).
- H2 sections = real search questions. First line under each = direct answer.
- FAQ using exact user phrasing from keyword research.
- Internal links to other funnel pages.

Output: full copy + which file it goes in + which JSON-LD to add.

---

SKILL: SEO CONTENT TOPICS

When to use: "What blog posts should we write?" or "content calendar" or when SEO Guru plans the next month.

Job: Turn keyword research into a prioritised content plan.

Method:

1. Pull targets from keyword research file.
2. Group into clusters — each cluster has one pillar page plus 5-10 supporting articles.
3. Score each topic: is the searcher ready to buy (transactional) or just learning (informational)?
4. Sequence: money pages first, then educational articles that feed AI citations.

Output example:

# Content plan
## Cluster: [topic] ([funnel])
Pillar: /services — target "main phrase"
Articles:
- "Article title" — target "question phrase" — informational — priority 1

Hand chosen titles to copywriter. Every published article must pass page audit before going live.

---

PART 6 — HOW KEYWORD RESEARCH MAPS TO PAGES (THE FLOW)

This is the part Yakin wanted you to understand clearly.

STEP 1 — ONE TIME (separate Cursor chat, Phase 1 Part A)

You and Cursor run seo-keyword-research. You list every page BDgrowthsuite will have (home, about, pricing, services, contact, blog, etc.). The agent researches Google and AI questions. Output: docs/seo-keyword-map.md with every phrase mapped to a page URL.

Yakin reviews. You lock the map. No more guessing keywords per page.

STEP 2 — WHEN BUILDING EACH PAGE

You open Cursor on the page you are building. The rules file (seo-standards) tells Cursor: read the keyword map, use only the phrases mapped to this page, follow the full page checklist, run audit before done.

You do not re-run full keyword research for every page. You use the locked map.

STEP 3 — WHEN ASKING "WHAT NEXT?" (SEO Guru chat)

You type: hi SEO guru

SEO Guru reads: keyword map, page list, audit backlog, content topic backlog, todo list (see Part 7). It tells you: build this page next, write this blog, fix this audit fail, research guest post on this site, etc.

STEP 4 — LATER (when Google Search Console has data)

A script or agent pulls real search queries from GSC (which phrases people used before clicking you). SEO Guru compares that to the keyword map and orders copy updates or new articles. That is Phase 2 automation.

---

PART 7 — SEO GURU DAILY / WEEKLY RUN (PHASE 1 PART B)

After Part A setup is done, this is how a normal session works.

You type: hi SEO guru

SEO Guru checks its todo list (a markdown file you keep in the project — example: docs/seo-todo.md). That list was created by SEO Guru in earlier sessions with you and Yakin. You can add items anytime ("research guest posts on marketing blogs", "check our LinkedIn post ideas", "audit the pricing page").

SEO Guru reads:

- Today's todo items
- Backlog items not done
- Keyword map (locked)
- Page list (which pages exist vs planned)
- Last audit results
- Content topic backlog

Then it works for 10 minutes to 4 hours depending on what you asked. It may use sub-skills (research, audit, copywriter, content topics, and later outreach for third-party blogs and social).

At the end it gives you a list for human approval:

- Pages to build or fix (with exact changes)
- Blog titles to write
- Guest post / directory / social opportunities found
- Speed fixes
- Copy changes

You or Yakin approve, comment, or reject. Nothing goes live without your OK in Phase 1.

The todo list grows over time. SEO Guru adds to it as it works. Humans add too.

Example todo items:

- Audit /pricing — run page audit
- Write blog: "How to X in India" — copywriter
- Find 10 marketing blogs that accept guest posts — research agent
- Submit sitemap to Google Search Console — owner task
- Fix LCP on home page — pagespeed fix

---

PART 8 — PHASE 1 PART A — WHAT TO DO IN ONE SATURDAY (4-5 DAYS)

This is the setup sprint. Goal: when any page is built after this, Cursor automatically follows the rules.

Day 1 — Files and list

- Create pages-list (every URL you plan to have, with status: planned / live).
- Create robots.txt, sitemap.xml (even if only home is live), llms.txt, index.md.
- Add meta tags template you copy for each new page.

Day 2 — Cursor skills and rules in the BDgrowthsuite project

Copy or recreate these skill files in .cursor/skills/:

- seo-guru
- seo-keyword-research (rename from india-specific if you want)
- seo-page-audit
- seo-pagespeed
- seo-copywriter
- seo-content-topics

Add one rule file: .cursor/rules/seo-standards.mdc — "every public page must pass the checklist before done."

Day 3 — Keyword research chat (separate, important)

New Cursor chat. Say: run seo-keyword-research for BDgrowthsuite. Give your page list and business description. Save output to docs/seo-keyword-map.md. Yakin locks it.

Day 4 — Build or fix first real page using the map

Pick the most important page (usually home or main service). Cursor follows keyword map + standards. Run audit twice. Add CopyPageButton equivalent.

Day 5 — SEO Guru todo file + test run

Create docs/seo-todo.md with initial items. New chat: hi SEO guru — run weekly plan. Review output. Adjust skills if something was unclear.

Owner tasks you need from Yakin (agents cannot create accounts):

- Google Search Console verification (HTML meta tag or DNS)
- Bing Webmaster if wanted
- Real 1200x630 social share image (replace favicon as placeholder)
- Google Analytics ID when ready

---

PART 9 — PHASE 2 (LATER) — SEO ON AUTOPILOT

Not now. For vision only.

- SEO Guru runs on a server daily or weekly via API (same skills, not Cursor chat).
- Pulls Google Search Console and Analytics stats automatically.
- Compares performance to keyword map.
- Drafts content, queues guest post outreach, proposes social posts.
- Everything lands in an approval UI — human approves before publish.
- AI learns from each approval, rejection, and comment you leave.

Phase 1 must prove the skills and checklist work manually first.

---

PART 10 — OFF-PAGE AND SOCIAL (PHASE 1 PART B AND BEYOND)

SEO Guru can also research (when you ask):

- Guest post opportunities on third-party blogs
- Directory listings
- LinkedIn / Twitter post ideas with UTM tracking links
- Partner sites for backlinks

It adds these to the todo list, does the research in a run, and gives you a approve/comment list. It does not spam or post without your OK.

---

PART 11 — THREE RESOURCES TO READ (FREE, FROM THE INTERNET)

These are the best starting points Yakin picked for you. Read in this order.

1. Google Search Central — Optimizing for generative AI features on Google Search
   https://developers.google.com/search/docs/fundamentals/ai-optimization-guide
   Google's own guide. Main lesson: good SEO fundamentals still matter for Google. Write for humans. Clear headings. Fast pages. Do not chase fake hacks.

2. Google Search Essentials (SEO starter)
   https://developers.google.com/search/docs/essentials
   The official checklist for what Google needs to find and rank pages. Sitemap, titles, useful content, no spam.

3. Chrome for Developers — Agent-ready toolkit blog
   https://developer.chrome.com/blog/agent-ready-toolkit
   How websites will be tested for "agent-friendly" design — clear structure, accessibility, stability. This is the future Game 3 we are preparing for.

Bonus (llms.txt spec): https://llmstxt.org — short read on what llms.txt is and why we add it for AI tools even though Google ignores it.

---

PART 12 — GLOSSARY (PLAIN ENGLISH)

SEO — Search Engine Optimization. Helping Google find and rank your pages.

AEO — Answer Engine Optimization. Writing so AI tools can quote your direct answers.

GEO — Generative Engine Optimization. Broader term for showing up inside AI-generated text.

JSON-LD — A small JSON block in the page source that tells Google and AI what the page is about (company, FAQ, product, job, etc.).

Canonical URL — The one official URL for a page.

Sitemap — XML file listing all public pages for Google.

llms.txt — Plain text site map for AI tools.

.md mirror — Plain text copy of a page for machines and the Copy button.

Skill — An instruction file that tells Cursor how to do one job (audit, keywords, copy, etc.).

SEO Guru — The manager skill that reads everything and assigns work.

Keyword map — Locked file mapping search phrases to page URLs.

Phase 1 Part A — Setup skills, rules, keyword map, base files (one weekend).

Phase 1 Part B — Ongoing Cursor chats with SEO Guru, human approval.

Phase 2 — Automated runs on a server with approval UI.

---

PART 13 — YOUR IMMEDIATE NEXT STEPS

1. Read this email once fully.

2. Read resource 1 and 2 from Part 11 (Google links) — 30-45 minutes total.

3. Create the base files on BDgrowthsuite: robots.txt, sitemap.xml, llms.txt, index.md, pages-list.

4. Tell Yakin when ready for a joint Cursor session to copy the skills into the BDgrowthsuite project.

5. Run the keyword research chat (separate session) before writing final copy for main pages.

6. Reply to Yakin with questions — one email thread is fine.

---

PART 14 — ONE-PAGE DEFINITION OF DONE (PIN THIS)

For every new public page:

[ ] In pages-list and sitemap.xml and llms.txt
[ ] Title + description + canonical + social tags
[ ] One H1, phrase in first paragraph
[ ] Question-style H2s with answer-first first lines
[ ] JSON-LD in page source
[ ] .md mirror in sync
[ ] Copy / View MD / Open in ChatGPT / Open in Claude buttons
[ ] Internal links to other key pages
[ ] Fast on mobile
[ ] seo-page-audit PASS twice

---

You are not expected to become an SEO expert overnight. You are expected to follow this system, use the Cursor skills, and ask SEO Guru when stuck. The skills do the specialist thinking; you build the pages and approve the output.

Any questions, reply to Yakin or reply to this email.

— Yakin (via BusinessLabs assistant)
