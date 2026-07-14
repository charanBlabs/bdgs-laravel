#!/usr/bin/env python3
"""Rewrite ImageKit CDN URLs to local /images/... paths."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

BRAND = {
    "logo.png": "/images/brand/logo.png",
    "favicon.png": "/images/brand/favicon.png",
    "business-growth.jpg": "/images/brand/business-growth.jpg",
}

PATTERN = re.compile(
    r"https://ik\.imagekit\.io/h1pfsvzlsf/bdgrowthsuite/images/([^\"'\\s>]+)"
)


def map_path(rel: str) -> str:
    rel = rel.split("?", 1)[0].lstrip("/")
    if rel in BRAND:
        return BRAND[rel]
    if rel.startswith("LogoP1/") or rel.startswith("LogoP2/") or rel == "CCRG-LOGO-edit1.png":
        return "/images/clients/" + rel
    if rel.startswith("stampready-email-template/"):
        return "/images/email/" + rel
    return "/images/solutions/" + Path(rel).name


def main() -> None:
    globs = [
        "resources/views/**/*.blade.php",
        "app/**/*.php",
        "config/**/*.php",
        "public/css/**/*.css",
        ".env.example",
        "database/seeders/data/solutions_data.sql",
        "database/seeders/data/bdgs_email_templates.sql",
    ]
    files: list[Path] = []
    for g in globs:
        files.extend(ROOT.glob(g))

    changed: list[str] = []
    for f in files:
        if not f.is_file():
            continue
        text = f.read_text(encoding="utf-8")
        if "ik.imagekit.io" not in text:
            continue
        new = PATTERN.sub(lambda m: map_path(m.group(1)), text)
        if new != text:
            f.write_text(new, encoding="utf-8")
            changed.append(str(f.relative_to(ROOT)))

    print(f"updated {len(changed)} files")
    for c in changed:
        print(f" - {c}")

    left = []
    for f in files:
        if f.is_file() and "ik.imagekit.io" in f.read_text(encoding="utf-8", errors="ignore"):
            left.append(str(f.relative_to(ROOT)))
    print(f"remaining imagekit refs: {len(left)}")
    for c in left:
        print(f" ! {c}")


if __name__ == "__main__":
    main()
