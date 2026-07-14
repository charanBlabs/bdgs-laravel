@php
  $pageUrl = 'https://bdgrowthsuite.com/solutions/';
  $pageName = $totalSolutions.'+ Brilliant Directories Solutions (Done-For-You) — BD Growth Suite';
  $pageDescription = 'Browse '.$totalSolutions.'+ done-for-you Brilliant Directories solutions — SEO, lead gen, profiles, search, design, content, integrations, and member management.';
  $ogImage = 'https://bdgrowthsuite.com/images/brand/logo.png';

  $listItems = $categories->values()->map(fn ($category, $index) => [
      '@type' => 'ListItem',
      'position' => $index + 1,
      'name' => $category->name,
      'url' => 'https://bdgrowthsuite.com/solutions/'.$category->slug,
  ])->all();

  $schemaGraph = [
      '@context' => 'https://schema.org',
      '@graph' => [
          [
              '@type' => 'Organization',
              '@id' => 'https://bdgrowthsuite.com/#organization',
              'name' => 'BD Growth Suite',
              'url' => 'https://bdgrowthsuite.com/',
              'logo' => $ogImage,
          ],
          [
              '@type' => 'WebPage',
              '@id' => $pageUrl.'#webpage',
              'url' => $pageUrl,
              'name' => $pageName,
              'description' => $pageDescription,
              'isPartOf' => ['@id' => 'https://bdgrowthsuite.com/#website'],
          ],
          [
              '@type' => 'BreadcrumbList',
              '@id' => $pageUrl.'#breadcrumb',
              'itemListElement' => [
                  ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://bdgrowthsuite.com/'],
                  ['@type' => 'ListItem', 'position' => 2, 'name' => 'Solutions', 'item' => $pageUrl],
              ],
          ],
          [
              '@type' => 'Service',
              '@id' => $pageUrl.'#service',
              'name' => 'Brilliant Directories Solutions (Done-For-You)',
              'description' => $pageDescription,
              'provider' => ['@id' => 'https://bdgrowthsuite.com/#organization'],
              'areaServed' => 'Worldwide',
              'url' => $pageUrl,
          ],
          [
              '@type' => 'ItemList',
              '@id' => $pageUrl.'#itemlist',
              'name' => 'Brilliant Directories solution categories',
              'numberOfItems' => $categories->count(),
              'itemListElement' => $listItems,
          ],
      ],
  ];
@endphp
<script type="application/ld+json">{!! json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
