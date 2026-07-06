# SEO Guru — Todo

Backlog for the `seo-guru` skill. SEO Guru reads this every run, works the items, and adds new ones it finds. Humans add too. Nothing goes live without approval.

Format: `- [ ] {action} — {skill or owner} — {why it helps leads/trust}`

## Now (setup)

- [ ] Run `seo-keyword-research` in a dedicated chat; fill `docs/seo-keyword-map.md`; Yakin locks it — keyword-research — every page's copy depends on the locked map
- [ ] Confirm north-star metric with Yakin (`data/seo-pages.json` -> site.north_star) — owner — anchors every recommendation
- [ ] Build the homepage HTML at `index.html`, add the CopyPageButton from `snippets/copy-page-button.html` — page build — first live page
- [ ] Run `seo-page-audit` on the homepage; fix fails; re-audit to 2 clean passes — page-audit — first shippable page

## Owner tasks (Yakin — agents cannot create accounts)

- [ ] Google Search Console verification (HTML meta tag or DNS) — owner
- [ ] Bing Webmaster (optional) — owner
- [ ] Real 1200x630 social share image at `/og/default.png` — owner
- [ ] Google Analytics ID when ready — owner

## Next (build pages)

- [ ] Build/optimize core pages using the locked map: /setup, /hire-developer, /about, /contact — copywriter + page-audit — core lead pages
- [ ] After each page flips to `status: live` in `data/seo-pages.json`, run `python scripts/build_seo_files.py` — agent — keeps sitemap.xml + llms.txt in sync
- [ ] Run `seo-pagespeed` on the homepage before launch (target LCP < 2.5s, CLS < 0.1) — pagespeed — ranking + agent reliability
- [ ] Run `seo-content-topics` to draft the first content cluster (pillar /setup + articles) — content-topics — informational traffic + AI citations

## Off-page (only when asked)

- [ ] Find 10 marketing/directory blogs that accept guest posts — seo-guru research — backlinks
- [ ] Draft launch post ideas with UTM links — seo-guru research — referral traffic
