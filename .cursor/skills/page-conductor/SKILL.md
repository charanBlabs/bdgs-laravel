---
name: page-conductor
description: Orchestrates the full page lifecycle — audit, fix, approval, lock — as an interactive loop. Reads page state, runs the full-lock-audit, dispatches sub-skills to fix fails (with user confirmation), waits for approval gates, and resumes from where it left off. Use when driving a page from built to locked, checking page state, or resuming after approval. Triggered by "conductor /page", "page conductor /page", "drive /page to lock", "what's next for /page", "resume /page", or "check state /page".
---

# Page Conductor

One skill. One page. Drive it from "just built" all the way to LOCKED — step by step, with your confirmation at every decision.

---

## How it works

You invoke the conductor on a page. It reads the current state, decides what step comes next, asks you to confirm, does the work (via sub-skills), and loops until the page is locked or you say stop.

**You are always in control.** Nothing happens without your explicit OK.

---

## Before every invocation

Read these files to determine current state:

| File | What to check |
|------|---------------|
| `data/seo-pages.json` | Page entry, `status` field (live / planned / noindex) |
| `docs/progress.md` | Page row — `not-started` / `in-progress` / `pending-approval` / `locked` |
| `docs/seo-keyword-map.md` | Whether keywords are locked for this page |

---

## State machine

Determine the page's current state and jump to the matching step:

```
┌─────────────────────────────────────────────────────────────┐
│  STATE              │  CONDITION                             │
├─────────────────────┼────────────────────────────────────────┤
│  PLANNED            │  seo-pages.json status = "planned"     │
│  IN-PROGRESS        │  progress.md = "in-progress"           │
│  NEEDS-AUDIT        │  status = "live" but not pending/locked │
│  FIXING             │  Last audit < 18.0 (fails exist)       │
│  PASS-1             │  One clean audit >= 18.0               │
│  PENDING-APPROVAL   │  progress.md = "pending-approval"      │
│  APPROVED           │  User confirms approval                │
│  LOCKED             │  progress.md = "locked"                │
└─────────────────────┴────────────────────────────────────────┘
```

---

## Step-by-step execution

### Step 1: Read state

1. Read `data/seo-pages.json` — find the page entry.
2. Read `docs/progress.md` — find the page row and its status.
3. Read `docs/seo-keyword-map.md` — check if keywords are mapped.
4. Determine the current state from the table above.
5. Report the state to the user in one line, then proceed to the matching step below.

### Step 2: PLANNED — page not built

**Ask:**
> This page is planned but not built yet. Do you want to start building it now?
>
> Options:
> - Yes — build Laravel page (route + controller + Blade views)
> - No — hold for now

If **Yes**: give the build instructions from `docs/seo-system-guide.md` Scenario 1 (route in `routes/web.php`, controller, `resources/views/pages/{slug}/index.blade.php` + `content.blade.php`, page CSS). After building, re-read state — it should now be NEEDS-AUDIT.

If **No**: report current state and exit. Next invocation will resume here.

### Step 3: IN-PROGRESS — page being built

**Ask:**
> This page is in-progress. Is the build complete and ready for audit?
>
> Options:
> - Yes — run full audit
> - Not yet — hold

If **Yes**: update progress.md status if needed, then proceed to Step 4.

If **Not yet**: exit. Next invocation resumes here.

### Step 4: NEEDS-AUDIT — run the full lock audit

**Action:** Run the `full-lock-audit` skill internally on this page.

Present the full audit report to the user. Then:

- If score >= 18.0 AND all sections >= 4.0 AND zero critical fails → **PASS**. Go to Step 6.
- If NOT passing → go to Step 5 (FIXING).

### Step 5: FIXING — audit found fails

**Before fixing:** ensure Blade view changes are committed or stashed in git. Report: "Use `git checkout -- resources/views/pages/{slug}/` to restore if needed."

Group the fails by domain and present them:

```
━━━ FIX GROUPS ━━━

A. SEO fixes (N items) — dispatches to: seo-copywriter + seo-page-audit
B. Design fixes (N items) — dispatches to: manual CSS edits guided by design-system.mdc
C. Parity fixes (N items) — remove inline shell markup; fix partials if shared chrome is wrong
D. Brand/copy fixes (N items) — dispatches to: seo-copywriter
E. Speed fixes (if applicable) — dispatches to: seo-pagespeed
```

**Ask:**
> Audit found fails. Which group do you want to fix first?
>
> Options:
> - All — fix everything in priority order (B2 critical first, then SEO, then design, then brand)
> - SEO only
> - Design only
> - Copy/Brand only
> - Parity only (fix partials or remove inline shell)
> - Restore — `git checkout` page views to last commit
> - Skip — I'll fix manually, re-audit when ready

If **Restore**: `git checkout -- resources/views/pages/{slug}/` (and related CSS), report "Restored to last commit." Then loop back to Step 4.

**Priority order when "All" is selected:**
1. Critical fails (any ⚠️ items) — always first
2. Section B (design tokens) — B2 typography is critical, fixes cascading issues
3. Section A (SEO) — meta, H1, keywords, schema, links
4. Section D (brand/copy) — CTAs, voice, banned terms
5. Section C (parity) — only if broken; usually clean

**Dispatch rules per group:**

| Group | Action |
|-------|--------|
| SEO (A) — meta description, H1, keyword placement, schema | Edit `index.blade.php` (meta) + `content.blade.php` (body); run `seo-copywriter` for copy |
| Design (B) — tokens, spacing, radius, motion | Edit `public/css/bdgs-{slug}.css` with design tokens from `design-system.mdc` |
| Parity (C) — inline shell in content | Remove inline header/footer/modal/FAB from `content.blade.php`; fix `partials/bdgs/*` if shared chrome is wrong |
| Brand (D) — voice, CTAs, banned words | Run `seo-copywriter` for rewrites in `content.blade.php` |
| Speed (E) — images, fonts, JS | Run `seo-pagespeed`; apply confirmed fixes |

After fixes are applied, **always re-run the full-lock-audit** (loop back to Step 4).

### Step 6: PASS — audit passed

**Track the pass count.** Two consecutive passes are required.

If this is **pass 1/2**:

**Ask:**
> Pass 1 achieved (score: X.XX/20.0). The page needs one more clean pass to lock.
> Re-run full audit now for pass 2?
>
> Options:
> - Yes — run pass 2 now
> - Later — hold here

If **Yes**: loop back to Step 4 for pass 2.

If this is **pass 2/2**:

**Ask:**
> Two consecutive passes achieved. Page is ready to lock.
> Mark as pending-approval (waiting for Yakin)?
>
> Options:
> - Yes — mark pending-approval
> - No — hold

If **Yes**: update `docs/progress.md` to set the page status to `pending-approval`. Proceed to Step 7.

### Step 7: PENDING-APPROVAL — waiting for owner sign-off

**Ask:**
> This page is pending Yakin's approval. Has it been approved?
>
> Options:
> - Yes — approved, proceed to make live
> - Not yet — hold
> - Rejected — needs changes (back to fixing)

If **Yes (approved)**: proceed to Step 8.

If **Not yet**: report state and exit. Say:
> Page `/slug` is on hold — pending-approval. Come back when Yakin has reviewed it, or say "conductor /slug" to check again.

If **Rejected**: ask what needs changing, then loop back to Step 5 (FIXING) with the new requirements.

### Step 8: APPROVED — make live and lock

**Ask:**
> Approved. Run the make-live steps now?
> - Update seo-pages.json status (if not already "live")
> - Regenerate sitemap.xml + llms.txt
> - Verify `.md` URL works (`/{slug}.md` via `ProvideMarkdownResponse` middleware)
> - Run final sanity audit
> - Mark LOCKED in progress.md
>
> Options:
> - Yes — run all steps
> - Hold — not ready to deploy yet

If **Yes**, execute in order:

1. Confirm `data/seo-pages.json` has `"status": "live"` for this page.
2. Run `python scripts/build_seo_files.py` to regenerate sitemap + llms.txt.
3. Verify `/{slug}.md` returns markdown (middleware on route group in `routes/web.php`).
4. Run one final `full-lock-audit` — must still pass (sanity check after any status file edits).
5. **Ask before locking:**
   > Everything passes. Do you have any custom changes to make before locking?
   >
   > Options:
   > - No — lock it now
   > - Yes — I have changes to make (will re-audit after)

   If **Yes**: hold here. The user makes their changes. When they invoke the conductor again, it detects the page is still not locked and re-runs the audit (Step 4). If new fails appear, they get fixed in the normal loop.

   If **No**: proceed to lock.
6. Update `docs/progress.md` — set the page to `locked`.
7. Report:

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
✓ PAGE LOCKED: /slug
State: locked
Score: XX.XX/20.0
Passes: 2/2
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

8. Lock complete. No `.bak` files — git is the backup.

### Step 9: LOCKED — done

If the page is already locked:

> `/slug` is LOCKED (score: XX.XX/20.0, locked on YYYY-MM-DD).
>
> Options:
> - Re-audit this page (run full-lock-audit again to verify it still passes)
> - Unlock and redo (revert to NEEDS-AUDIT state for new changes)
> - Show all page states
> - Pick next page to drive

If **Re-audit**: run `full-lock-audit`. If it still passes, confirm lock holds and report: "Lock verified — page still passes." If it fails (e.g. something drifted after a shared-shell update), ask:
> Page no longer passes (score: X.XX/20.0). Unlock and fix?
>
> Options:
> - Yes — unlock and enter fix loop
> - No — keep locked, note the drift

If yes: update `docs/progress.md` from `locked` back to `in-progress`, then loop to Step 4 (NEEDS-AUDIT).

If **Unlock and redo**: update `docs/progress.md` from `locked` to `in-progress`, then proceed to Step 4 (NEEDS-AUDIT). This lets the user make changes and drive back through the full audit loop.

If **Show all page states**: read `data/seo-pages.json` + `docs/progress.md` and display a summary table of all pages with their current lifecycle state.

---

## Keyword map gate

If `docs/seo-keyword-map.md` has no entry for this page (or the entry is marked DRAFT):

**Ask before any audit:**
> The keyword map has no locked keywords for this page. The audit will flag keyword-related items.
>
> Options:
> - Run keyword research first (triggers `seo-keyword-research`)
> - Proceed with audit anyway (accept keyword warnings)
> - Hold until keywords are locked

---

## Backup protocol

Before any fix edits begin (Step 5):

1. Ensure current work is committed or stashed: `git status` on `resources/views/pages/{slug}/` and `public/css/bdgs-{slug}.css`.
2. Report: "Restore with `git checkout -- resources/views/pages/{slug}/ public/css/bdgs-{slug}.css` if fixes go wrong."

**Restore:** If the user says "restore", run `git checkout` on the page view files, then re-enter Step 4.

---

## Rules for this skill

1. **Always ask before acting.** Never auto-fix, auto-approve, or auto-lock.
2. **One page at a time.** For batch audits use "audit all pages" directly.
3. **State is derived, not stored.** Re-read `progress.md` + `seo-pages.json` on every invocation — no hidden state file.
4. **Two-pass rule is strict.** A single pass (even at 20.0) does not unlock. Two consecutive passes required.
5. **Approval is human-only.** The conductor never assumes approval — it always asks.
6. **Exit gracefully.** At any "hold" answer, report current state clearly so the next invocation knows where to resume.
7. **Priority is: critical fails → design → SEO → brand → speed.** This order minimizes rework (design token fixes prevent cascading SEO/brand issues).
8. **Shared partial fixes need care.** If parity (C) fixes touch `resources/views/partials/bdgs/*`, warn: "This change affects ALL pages. Confirm?" — then fix the partial once.
9. **Never invent fixes.** Only relay what the `full-lock-audit` reports. If a fix is ambiguous, ask the user.
10. **Report progress.** After each fix group, show a mini-scoreboard: "Fixed N/M items in group X. Re-auditing…"

---

## Output format (state report)

Every invocation starts with a one-line state report:

```
━━━ CONDUCTOR: /slug ━━━
State: {PLANNED | IN-PROGRESS | NEEDS-AUDIT | FIXING (N fails) | PASS 1/2 | PENDING-APPROVAL | LOCKED}
Score: {last audit score or "—" if not audited}
Keywords: {locked | DRAFT | missing}
Next action: {what happens next}
━━━━━━━━━━━━━━━━━━━━━━━━
```

Then proceed to the appropriate step.

---

## Example session flow

```
User: "conductor /customization"

Conductor reads state → pending-approval
━━━ CONDUCTOR: /customization ━━━
State: PENDING-APPROVAL
Score: 14.54/20.0 (last audit — NOT READY)
Keywords: DRAFT
Next action: Confirm approval or fix remaining fails
━━━━━━━━━━━━━━━━━━━━━━━━

Ask: "This page scored 14.54 on last audit (needs 18.0).
      It's marked pending-approval but has unfixed fails.
      What would you like to do?
      - Fix the fails first (recommended)
      - Check if Yakin approved as-is
      - Hold"

User: "Fix the fails first"

Conductor: runs full-lock-audit, groups fails, asks which group...
[loop continues until locked]
```

---

## Related skills and rules

| Resource | Role in this flow |
|----------|-------------------|
| `full-lock-audit` | The audit engine — called by conductor at Steps 4, 6, 8 |
| `seo-page-audit` | Quick mid-fix verification (used within SEO fix group) |
| `seo-copywriter` | Rewrites meta, H1, H2s, CTAs, FAQ answers |
| `seo-pagespeed` | Speed measurement and fixes |
| `seo-keyword-research` | Only if keyword map is missing for this page |
| `design-system.mdc` | Rule — auto-enforces tokens during design edits |
| `shared-components-parity.mdc` | Rule — enforces Blade partial parity during fixes |
| `brand-voice-and-copy.mdc` | Rule — enforces voice during copy rewrites |

---

## Trigger phrases

- `"conductor /page"` or `"page conductor /page"`
- `"drive /page to lock"`
- `"what's next for /page"`
- `"resume /page"`
- `"check state /page"`
