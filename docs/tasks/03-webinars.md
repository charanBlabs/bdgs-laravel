# 03 — Webinars page

**Goal:** `/webinars/` with both sessions, locked naming, and clean on-site video embeds (no YouTube chrome, no ads). Required destination for Home §1/§2 approval.

**Status: LOCKED Jul 3–4** (#24–#26 ☑)

## Read first
- `webinars/index.html` · `webinars/index.md` · `data/seo-pages.json` row for `/webinars`
- Decision `0002-webinar-part-naming.md`
- `snippets/bdgs-webinar-videos.js` · `snippets/bdgs-youtube-player.js`
- Runbook; `design-system.mdc`

## Spec (locked)

### Naming — Decision 0002
- **First session** (#205) and **Follow-up session** (#213) — BD-aligned labels.
- **Not used:** Part 1/Part 2, Season 1 · Episode 1/2.
- Homepage CEO embed unchanged (`pxKOMfuuPO8`).

### Videos on `/webinars/`
| ID | Label | Series |
|----|-------|--------|
| #205 | First session | Your First 100 Members — Simplify Your Directory |
| #213 | Follow-up session | Your First 100 Members — Build a High-Converting Join Page |
| #136 | Standalone | Member Dashboard Checklist |

Video IDs live in `snippets/bdgs-webinar-videos.js` (single swap point).

### YouTube embed — keep users on site (met)
- No "Watch on YouTube" link/button.
- No end-screen suggested videos / subscribe / related junk after the video ends.
- **Zero ads.**
- Custom player (`bdgs-youtube-player.js`) + `youtube-nocookie` embed, `rel=0`, `modestbranding=1`, custom controls.
- **David source-file fallback:** closed Jul 4 — not needed (nocookie embed clean).

## Build steps (completed)
1. Layout per design-system (fluid H1, `--section-y`). Answer-first intro.
2. Custom lite YouTube facade + control bar; explicit 16:9 `aspect-ratio` (no CLS).
3. First session / Follow-up session naming site-wide on this page.
4. Cross-link to Reviews, Explore Services, Zoom Clinics.
5. CopyPageButton v4 + `.md` mirror.

## Acceptance (measurable) — met
- Both sessions present; naming consistent with Decision 0002.
- Embeds show no "Watch on YouTube", no end-screen, no ads (browser verified Jul 3).
- No overflow at all widths; video has reserved aspect-ratio.

## Done
- [x] Naming scheme decision recorded in `docs/decisions/0002-webinar-part-naming.md`
- [x] Embed clean — David outreach not required
- [x] CopyPageButton v4 + `.md` mirror
- [x] Promoted to production folder (`webinars/`) Jul 4 sync
- [ ] Flip `status: live` in `data/seo-pages.json` + run `python scripts/build_seo_files.py` (when ready for SEO publish)
