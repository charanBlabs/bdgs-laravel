# 04 — Services page redesign (rejected Jun 28)

**Goal:** redo the Services page to the standard agreed ~Jun 28. Current build was **rejected** (not up to mark) — full rework, not tweaks.

## ⚠️ Resolve scope first (blocking question for the user)
The review lists **"Explore Services page"** (task 01) as *distinct from* the **"Services page redesign (rejected Jun 28)"**. Before building, confirm with the user:
- Are these **two different pages** (e.g. `/services` hub = Explore Services, and a separate detailed Services page), or the **same page** where task 01 already replaces the rejected design?
- If same → close this task, fold any extra requirements into 01.
- If different → get the intended URL/slug and what content differs from the hub.

Do not build until this is answered — otherwise risk rebuilding the wrong thing.

## Read first (once scope is clear)
- The Jun 28 agreed design reference (ask user for the source/mock if not in repo)
- `services-page-layout.md` in `docs/` (migrated layout reference)
- `data/seo-pages.json` · keyword map · Runbook

## Spec (locked)
- Must meet the Jun 28 agreed bar (get the reference before starting).
- Platform type scale, golden-standard principles, responsive matrix, CWV budget — same as every page.

## Acceptance / Verify / Done
- Same gates as the runbook: numeric browser check, `seo-page-audit` ×2, `seo-pagespeed`.
- Yakin sign-off required (design was rejected once) before lock/promote.
- Then: CopyPageButton + `.md` mirror → promote → `status: live` → rebuild SEO files → `progress.md`.
