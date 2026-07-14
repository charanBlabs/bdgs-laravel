@php
  $homeOrganizationSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'Organization',
      'name' => 'BD Growth Suite',
      'alternateName' => 'BDGS',
      'url' => 'https://bdgrowthsuite.com',
      'logo' => 'https://bdgrowthsuite.com/images/brand/logo.png',
      'description' => 'Brilliant Directories developers — dedicated BD developer team for setup, customization, AI automation, and growth.',
      'foundingDate' => '2014',
      'founder' => ['@type' => 'Person', 'name' => 'Yakin Shah'],
      'numberOfEmployees' => ['@type' => 'QuantitativeValue', 'minValue' => 20],
      'aggregateRating' => [
          '@type' => 'AggregateRating',
          'ratingValue' => '5',
          'reviewCount' => (string) $reviewCount,
          'bestRating' => '5',
      ],
      'contactPoint' => [
          '@type' => 'ContactPoint',
          'contactType' => 'customer service',
          'email' => 'hello@bdgrowthsuite.com',
      ],
  ];

  $homeWebsiteSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'WebSite',
      'name' => 'BD Growth Suite',
      'url' => 'https://bdgrowthsuite.com',
      'potentialAction' => [
          '@type' => 'SearchAction',
          'target' => 'https://bdgrowthsuite.com/search?q={search_term_string}',
          'query-input' => 'required name=search_term_string',
      ],
  ];
@endphp
<script type="application/ld+json">{!! json_encode($homeOrganizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}</script>
<script type="application/ld+json">{!! json_encode($homeWebsiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}</script>
@include('partials.bdgs.schema-home-faq')
