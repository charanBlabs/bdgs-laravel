---
name: seo-content-topics
description: Turn the locked keyword map into a prioritized content plan (pillar pages + supporting articles) for BD Growth Suite. Use when planning blog posts, a content calendar, or when SEO Guru plans the next month. Triggered by "what blog posts should we write", "content plan", "content calendar", or "plan next month's content".
---

# SEO Content Topics

Turn keyword research into a prioritized plan. Money pages first, then educational articles that earn AI citations and feed the funnel.

## Method

1. Pull targets from `docs/seo-keyword-map.md`.
2. Group into **clusters** — each cluster = one pillar page + 5–10 supporting articles that link to it.
3. Score intent per topic: **transactional** (ready to buy) vs **informational** (learning).
4. Sequence: transactional/money pages first, then informational articles that build topical authority and get quoted by AI tools.
5. Confirm each planned URL exists (or should be added) in `data/seo-pages.json`.

## Output

```markdown
# Content plan — {month}

## Cluster: {topic} ({funnel})
Pillar: /{url} — target "{main phrase}"
Articles:
- "{Article title}" — target "{question phrase}" — informational — priority 1
- "{Article title}" — target "{comparison phrase}" — informational — priority 2
```

## Rules

- Every article maps to a keyword from the locked map and links to its pillar.
- Prefer **non-commodity** angles (first-hand results, real client wins, specific numbers) over generic listicles — that is what wins in Google AI Overviews and AI tools.
- Hand chosen titles to `seo-copywriter`. Every published article must pass `seo-page-audit` before going live.
- Add the plan (or chosen titles) to `docs/seo-todo.md` so SEO Guru tracks it.
