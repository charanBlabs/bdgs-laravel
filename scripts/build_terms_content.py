import re
from pathlib import Path

html = Path("storage/terms-production-html.txt").read_text(encoding="utf-8")
m = re.search(
    r'<div class="col-md-12">(.*)</div>\s*\n\s*\n\s*</div>\s*<!-- Closes Row -->',
    html,
    re.DOTALL,
)
body = m.group(1) if m else html
body = re.sub(r'\s*data-cursor-ref="[^"]*"', "", body)
body = re.sub(r"<h1[^>]*>", "<h1>", body)

schema = """
    <script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "WebPage",
    "@id": "https://bdgrowthsuite.com/about/terms",
    "url": "https://bdgrowthsuite.com/about/terms",
    "name": "Terms of Use - BD Growth Suite",
    "headline": "Read BD Growth Suite terms of use.",
    "about": "BD Growth Suite > About Terms",
    "inLanguage": "en-US",
    "dateModified": "2026-07-01T00:00:00-04:00",
    "mainEntityOfPage": "https://bdgrowthsuite.com/about/terms",
    "author": {
        "@id": "https://bdgrowthsuite.com/#organization"
    },
    "publisher": {
        "@id": "https://bdgrowthsuite.com/#organization"
    },
    "isPartOf": {
        "@id": "https://bdgrowthsuite.com/#website"
    },
    "primaryImageOfPage": {
        "@type": "ImageObject",
        "url": "/images/logo.png"
    },
    "keywords": [
        "business directory",
        ""
    ]
}
    </script>
"""

page = f"""<div class="content-container fr-view" id="first_container">
  <div class="container">
    <div class="clearfix body-content"></div>
    <div class="row">
      <div class="col-md-12">
{body.strip()}
      </div>
    </div>
    @verbatim
{schema}
    @endverbatim
    <div class="clearfix"></div>
  </div>
</div>
"""

out = Path("resources/views/pages/terms/content.blade.php")
out.parent.mkdir(parents=True, exist_ok=True)
out.write_text(page, encoding="utf-8")
print(f"written {len(page)} bytes to {out}")
