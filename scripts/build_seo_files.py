#!/usr/bin/env python3
"""Generate sitemap.xml and llms.txt for BD Growth Suite from the single source of truth.

Source:  data/seo-pages.json
Outputs: public/sitemap.xml, public/llms.txt

Only pages with "status": "live" are emitted (planned/noindex pages are excluded,
per the Google guidance that only indexable pages belong in the sitemap, and the
llms.txt spec that only pages with a real .md mirror should be listed).

Run:  python scripts/build_seo_files.py
Stdlib only. Re-run whenever a page's status flips to "live" in seo-pages.json.
"""
from __future__ import annotations

import datetime as _dt
import json
from pathlib import Path

REPO_ROOT = Path(__file__).resolve().parents[1]
DATA = REPO_ROOT / "data" / "seo-pages.json"
OUT_DIR = REPO_ROOT / "public"
TODAY = _dt.date.today().isoformat()

# lastmod priority per funnel/url; homepage is always top.
PRIORITY = {
    "awareness": "0.7",
    "consideration": "0.8",
    "conversion": "0.8",
    "trust": "0.6",
    "legal": "0.3",
    "utility": "0.3",
}


def load() -> dict:
    with DATA.open(encoding="utf-8") as fh:
        return json.load(fh)


def live_pages(data: dict) -> list[dict]:
    return [p for p in data["pages"] if p.get("status") == "live"]


def abs_url(base: str, path: str) -> str:
    if path == "/":
        return base.rstrip("/") + "/"
    url = base.rstrip("/") + path
    if not path.endswith("/") and "." not in path.split("/")[-1]:
        url += "/"
    return url


def build_sitemap(data: dict) -> str:
    base = data["site"]["production_url"]
    rows = []
    for p in live_pages(data):
        loc = abs_url(base, p["url"])
        prio = "1.0" if p["url"] == "/" else PRIORITY.get(p.get("funnel", ""), "0.6")
        rows.append(
            "  <url>\n"
            f"    <loc>{loc}</loc>\n"
            f"    <lastmod>{TODAY}</lastmod>\n"
            "    <changefreq>monthly</changefreq>\n"
            f"    <priority>{prio}</priority>\n"
            "  </url>"
        )
    return (
        '<?xml version="1.0" encoding="UTF-8"?>\n'
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n'
        + "\n".join(rows)
        + "\n</urlset>\n"
    )


def md_url_for_page(base: str, page: dict) -> str:
    """Build the .md mirror URL (middleware: /slug.md or /index.md for home)."""
    if page["url"] == "/":
        path = "/index.md"
    else:
        path = page["url"].rstrip("/") + ".md"
    return base.rstrip("/") + path


def build_llms(data: dict) -> str:
    site = data["site"]
    base = site["production_url"]
    lines = [
        f"# {site['name']}",
        "",
        f"> {site['description']}",
        "",
        "Clean markdown copies of each page live at the same URL with `.md` appended "
        "(e.g. `/services/` -> `/services.md`). Served by Laravel middleware (ProvideMarkdownResponse). "
        "Google ignores this file for ranking; it exists to help ChatGPT, Claude, and Perplexity read the site.",
        "",
        "## Pages",
    ]
    for p in live_pages(data):
        md_url = md_url_for_page(base, p)
        lines.append(f"- [{p['title']}]({md_url}): {p.get('primary_keyword', '') or 'BD Growth Suite'}")
    lines += [
        "",
        "## Optional",
        f"- [Full site (HTML)]({base}/): human-facing pages",
    ]
    return "\n".join(lines) + "\n"


def main() -> None:
    data = load()
    n = len(live_pages(data))
    (OUT_DIR / "sitemap.xml").write_text(build_sitemap(data), encoding="utf-8")
    (OUT_DIR / "llms.txt").write_text(build_llms(data), encoding="utf-8")
    print(f"Wrote sitemap.xml + llms.txt from {n} live page(s) -> {OUT_DIR}")


if __name__ == "__main__":
    main()
