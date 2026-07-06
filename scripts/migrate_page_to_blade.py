#!/usr/bin/env python3
"""Migrate a static BDGS HTML page to Laravel Blade views."""

from __future__ import annotations

import argparse
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
VIEWS = ROOT / "resources" / "views"
PUBLIC_CSS = ROOT / "public" / "css"
SHELL_CSS = PUBLIC_CSS / "bdgs-shell.css"


def read_lines(path: Path) -> list[str]:
    return path.read_text(encoding="utf-8").splitlines()


def extract_lines(lines: list[str], start: int, end: int) -> str:
    return "\n".join(lines[start - 1 : end]) + "\n"


def strip_style_tags(css: str) -> str:
    css = css.strip()
    if css.startswith("<style>"):
        css = css[7:]
    if css.endswith("</style>"):
        css = css[:-8]
    return css.strip() + "\n"


def find_line(lines: list[str], pattern: str, start: int = 0) -> int:
    rx = re.compile(pattern)
    for i in range(start, len(lines)):
        if rx.search(lines[i]):
            return i + 1
    raise ValueError(f"Pattern not found: {pattern}")


def extract_meta_block(lines: list[str]) -> str:
    start = find_line(lines, r"<title>")
    end = find_line(lines, r"<link rel=\"preconnect\"") - 1
    return extract_lines(lines, start, end)


def extract_title(lines: list[str]) -> str:
    for line in lines:
        m = re.search(r"<title>(.*?)</title>", line)
        if m:
            return m.group(1)
    return ""


def extract_ai_summary(lines: list[str]) -> str:
    start = find_line(lines, r'class="bdgs-ai-summary"')
    while start > 1 and "<div" not in lines[start - 1]:
        start -= 1
    end = start
    while end <= len(lines) and "</div>" not in lines[end - 1]:
        end += 1
    return extract_lines(lines, start, end)


def extract_content(lines: list[str], extra_after_footer: bool = False) -> str:
    header_end = find_line(lines, r"</header>")
    footer_start = find_line(lines, r'<footer class="bdgsownv2-footer">')
    chunks = [extract_lines(lines, header_end + 1, footer_start - 1)]
    if extra_after_footer:
        inquiry_start = find_line(lines, r'id="bdgsInquiryModal"')
        zoom_start = find_line(lines, r'id="bdgsZoomModal"')
        if zoom_start < inquiry_start:
            chunks.append(extract_lines(lines, zoom_start, inquiry_start - 1))
    return "\n".join(chunks)


def extract_json_ld_blocks(lines: list[str]) -> str:
    out: list[str] = []
    i = 0
    while i < len(lines):
        if '<script type="application/ld+json">' in lines[i]:
            start = i + 1
            while i < len(lines) and "</script>" not in lines[i]:
                i += 1
            block = extract_lines(lines, start, i)
            out.append(f"<script type=\"application/ld+json\">\n{block}</script>")
        i += 1
    return "\n".join(out) + ("\n" if out else "")


def extract_page_scripts(lines: list[str], slug: str) -> str:
    """Page-specific scripts not in layout partials."""
    if slug == "home":
        try:
            start = find_line(lines, r"// Modal — Zoom Clinics")
        except ValueError:
            return ""
        while start > 1 and "<script>" not in lines[start - 1]:
            start -= 1
        mob = find_line(lines, r'<div class="bdgs-mob-overlay"')
        block = extract_lines(lines, start, mob - 1)
        # Carousel / review block after mobile nav in source file — capture separately
        try:
            carousel = find_line(lines, r"Dynamic review fetcher")
        except ValueError:
            return block
        while carousel > 1 and "<script>" not in lines[carousel - 1]:
            carousel -= 1
        body_end = find_line(lines, r"</body>") - 1
        return block + "\n" + extract_lines(lines, carousel, body_end)

    try:
        cpb_start = find_line(lines, r'class="cpb-fab"')
    except ValueError:
        return ""

    script_end = cpb_start
    for i in range(cpb_start - 1, len(lines)):
        if "<script" in lines[i] and "application/ld+json" not in lines[i]:
            script_end = i + 1
        if script_end > cpb_start and "</script>" in lines[i] and i >= cpb_start:
            script_end = i + 1
            break
    # Continue through any trailing cpb-fab script blocks
    for i in range(script_end, len(lines)):
        if "</script>" in lines[i]:
            script_end = i + 1
        if "</body>" in lines[i]:
            break

    body_end = find_line(lines, r"</body>") - 1
    body = []
    for i in range(script_end, body_end):
        line = lines[i]
        if "bdgs-review-count.js" in line:
            continue
        body.append(line)
    text = "\n".join(body).strip()
    return (text + "\n") if text else ""


def extract_page_css(source: Path, slug: str) -> str:
    lines = read_lines(source)
    style_start = find_line(lines, r"^<style>")
    style_end = find_line(lines, r"^</style>", style_start)
    full_css = strip_style_tags(extract_lines(lines, style_start, style_end))
    if SHELL_CSS.is_file():
        shell = SHELL_CSS.read_text(encoding="utf-8")
        # Remove shell CSS prefix if it matches the start of the inline block
        if full_css.startswith(shell[:500]):
            # Heuristic: drop first N lines matching shell line count
            shell_lines = len(shell.strip().splitlines())
            page_lines = full_css.strip().splitlines()
            if len(page_lines) > shell_lines:
                full_css = "\n".join(page_lines[shell_lines:]) + "\n"
    return full_css


def write_controller(slug: str, controller: str) -> None:
    name = controller.replace("Controller", "")
    view_slug = slug.replace("-", "_") if slug != "home" else "home"
    path = ROOT / "app" / "Http" / "Controllers" / f"{controller}.php"
    if path.exists():
        return
    path.write_text(
        f"""<?php

namespace App\\Http\\Controllers;

use Illuminate\\View\\View;

class {controller} extends Controller
{{
    public function index(): View
    {{
        return view('pages.{slug}.index');
    }}
}}
""",
        encoding="utf-8",
    )


def write_index_blade(
    slug: str,
    meta: str,
    active_nav: str | None,
    css_file: str,
    schema_partial: str | None,
) -> None:
    active = f"@php($activeNav = '{active_nav}')\n\n" if active_nav else ""
    schema_push = (
        f"@push('page-schema')\n@include('{schema_partial}')\n@endpush\n"
        if schema_partial
        else ""
    )
    show_fab = "@php($showFab = false)\n\n" if slug == "home" else ""
    content = f"""@extends('layouts.bdgs')

{show_fab}{active}@section('title')
{meta.splitlines()[0]}
@endsection

@section('meta')
{chr(10).join(meta.splitlines()[1:])}
@endsection

@push('page-styles')
<link rel="stylesheet" href="/css/{css_file}">
@endpush

@section('content')
@include('pages.{slug}.content')
@endsection

{schema_push}"""
    out = VIEWS / "pages" / slug / "index.blade.php"
    out.parent.mkdir(parents=True, exist_ok=True)
    out.write_text(content, encoding="utf-8")


def migrate(
    source: Path,
    slug: str,
    controller: str,
    active_nav: str | None = None,
    extra_after_footer: bool = False,
) -> None:
    lines = read_lines(source)
    meta = extract_meta_block(lines)
    ai_summary = extract_ai_summary(lines)
    content = extract_content(lines, extra_after_footer=extra_after_footer)
    schema = extract_json_ld_blocks(lines)
    page_scripts = extract_page_scripts(lines, slug)
    page_css = extract_page_css(source, slug)

    css_name = f"bdgs-{slug}.css"
    PUBLIC_CSS.mkdir(parents=True, exist_ok=True)
    (PUBLIC_CSS / css_name).write_text(page_css, encoding="utf-8")

    content_path = VIEWS / "pages" / slug / "content.blade.php"
    content_path.parent.mkdir(parents=True, exist_ok=True)
    content_path.write_text(content, encoding="utf-8")

    schema_partial = None
    if schema.strip():
        schema_path = VIEWS / "partials" / "bdgs" / f"schema-{slug}.blade.php"
        schema_path.write_text("@verbatim\n" + schema + "@endverbatim\n", encoding="utf-8")
        schema_partial = f"partials.bdgs.schema-{slug}"

    # Per-page AI summary override in layout stack - use page-specific if different
    ai_path = VIEWS / "pages" / slug / "ai-summary.blade.php"
    default_ai = (VIEWS / "partials" / "bdgs" / "ai-summary.blade.php").read_text(encoding="utf-8")
    if ai_summary.strip() != default_ai.strip():
        ai_path.write_text(ai_summary, encoding="utf-8")

    if page_scripts.strip():
        scripts_path = VIEWS / "pages" / slug / "page-scripts.blade.php"
        scripts_path.write_text(page_scripts, encoding="utf-8")
        # Append to index blade via second pass
        write_index_blade(slug, meta, active_nav, css_name, schema_partial)
        index_path = VIEWS / "pages" / slug / "index.blade.php"
        index_path.write_text(
            index_path.read_text(encoding="utf-8")
            + "\n@push('page-scripts')\n@include('pages."
            + slug
            + ".page-scripts')\n@endpush\n",
            encoding="utf-8",
        )
    else:
        write_index_blade(slug, meta, active_nav, css_name, schema_partial)

    write_controller(slug, controller)
    print(f"Migrated {source.name} -> pages/{slug}/")


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("source", type=Path)
    parser.add_argument("--slug", required=True)
    parser.add_argument("--controller", required=True)
    parser.add_argument("--active-nav")
    parser.add_argument("--extra-after-footer", action="store_true")
    args = parser.parse_args()
    migrate(
        args.source.resolve(),
        args.slug,
        args.controller,
        active_nav=args.active_nav,
        extra_after_footer=args.extra_after_footer,
    )


if __name__ == "__main__":
    main()
