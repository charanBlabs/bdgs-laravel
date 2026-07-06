# Decision 0003 — Review card + shimmer locked (Card D trophy shine)

**Date:** 2026-07-03  
**Status:** LOCKED  
**Owner:** Yakin (pick) · Charan (promote)

## What was locked

**v3 gold-border review card** with **full-card shimmer — Card D (trophy shine)**.

| Layer | Choice | CSS class |
|-------|--------|-----------|
| Card family | v3 — gold gradient frame, no certificate/corners | `rev-v3` |
| Shimmer | Card D — single LTR trophy highlight, 7.5s loop | `rev-v3--shimmer-d` |

**Not chosen:** v1 clean, v2 premium frame, shimmer A/B/C, inner-only shimmers, border-only shimmers.

## Where it ships

| Surface | Path |
|---------|------|
| Reviews page (live API grid) | `local-html/blabs-review/index.html` → `blabs-review/index.html` |
| Home §4 single-review carousel | `local-html/index.html` → `home-hero-v8/index.html` |

## Design tokens

Uses registered `--prm-*` gold palette (`custom-color-registry.mdc`).

## Promotion notes (Jul 3)

- Variant picker removed from Reviews page; locked banner replaces demo grids.
- Carousel cards: `bdgsownv2-card rev-v3 rev-v3--shimmer-d` > `rev-v3__inner` > content.
- `prefers-reduced-motion`: shimmer animation disabled.

## Still open (not part of this lock)

- Reviews page title typography polish (#9)
- “Dancing cards” / load CLS (#8)
- Home §4 **final approval** (#33) — card locked; section sign-off may still wait on linked destinations
