# Decisions (locked)

Immutable record of **why** something is the way it is. One file per decision, append-only.

- **Never edit a decision after it's locked.** To change direction, add a **new** decision that supersedes the old one (link both ways).
- Naming: `NNNN-short-slug.md` (e.g. `0001-review-card-border.md`), zero-padded, incrementing.
- Keep each file short: Context -> Decision -> Consequences -> Status (`locked` / `superseded by NNNN`).

The current locked set from the Jul 1 review lives in `../website-review.md` ("Decisions Locked"). Record **new** decisions here as they're made (e.g. which review variant wins, final type scale, webinar naming scheme).

Template:

```markdown
# NNNN — Title

- Date: YYYY-MM-DD
- Status: locked

## Context
What forced the decision.

## Decision
What we chose (be specific — values, copy, paths).

## Consequences
What this locks in / rules out. Which pages/files it touches.
```
