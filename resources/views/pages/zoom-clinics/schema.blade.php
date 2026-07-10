@php
  $pageUrl = 'https://bdgrowthsuite.com/zoom-clinics/';
  $pageName = 'Free Brilliant Directories Zoom Clinics — BD Growth Suite';
  $pageDescription = 'Free Brilliant Directories Zoom Clinics every Tuesday and Thursday. BD Growth Suite developers answer live — widgets, CSS, search, email. Drop in, $0.';

  $faqItems = [
      [
          'question' => 'How do I get help with my Brilliant Directories website?',
          'answer' => 'Join a free BD Growth Suite Zoom Clinic. Drop in live on Tuesday or Thursday, share your screen, and our Brilliant Directories developers walk you through widgets, CSS, search, and email templates — no support ticket required.',
      ],
      [
          'question' => 'Are BD Growth Suite Zoom Clinics free?',
          'answer' => 'Yes — every Brilliant Directories Zoom Clinic is $0. Register once, get a calendar invite with the Zoom link, and join any session. Strategy and full builds belong on Express Setup or a custom project.',
      ],
      [
          'question' => 'When are Brilliant Directories Zoom Clinics?',
          'answer' => 'BD Growth Suite runs Zoom Clinics every Tuesday and Thursday at 6:30–7:30 PM IST, with a +30 minute buffer when queues are active. Times convert automatically to your local timezone.',
      ],
      [
          'question' => 'What can I ask at a Brilliant Directories Zoom Clinic?',
          'answer' => 'Tactical fixes: widget CSS, search filters, member dashboard quirks, email templates, and small config blockers. Not full site builds or launch strategy — those go to Express Setup or Founder Concierge.',
      ],
      [
          'question' => 'How do I register for a Zoom Clinic?',
          'answer' => 'Pick any upcoming session on this page, click Register, enter your name and email, and we send a calendar invite with the Zoom link. One registration per session — drop in live when it starts.',
      ],
  ];

  $graph = [
      [
          '@type' => 'Organization',
          '@id' => 'https://bdgrowthsuite.com/#organization',
          'name' => 'BD Growth Suite',
          'url' => 'https://bdgrowthsuite.com/',
          'logo' => 'https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png',
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
              ['@type' => 'ListItem', 'position' => 2, 'name' => 'Zoom Clinics', 'item' => $pageUrl],
          ],
      ],
      [
          '@type' => 'FAQPage',
          '@id' => $pageUrl.'#faq',
          'mainEntity' => collect($faqItems)->map(fn ($item) => [
              '@type' => 'Question',
              'name' => $item['question'],
              'acceptedAnswer' => [
                  '@type' => 'Answer',
                  'text' => $item['answer'],
              ],
          ])->values()->all(),
      ],
  ];

  if ($clinics->isNotEmpty()) {
      foreach ($clinics as $clinic) {
          $graph[] = [
              '@type' => 'Event',
              '@id' => $pageUrl.'#'.$clinic->slug,
              'name' => $clinic->title,
              'description' => strip_tags($clinic->description ?? ''),
              'startDate' => $clinic->session_starts_at->toIso8601String(),
              'endDate' => $clinic->session_ends_at->toIso8601String(),
              'eventAttendanceMode' => 'https://schema.org/OnlineEventAttendanceMode',
              'eventStatus' => 'https://schema.org/EventScheduled',
              'location' => [
                  '@type' => 'VirtualLocation',
                  'url' => $pageUrl,
              ],
              'organizer' => ['@id' => 'https://bdgrowthsuite.com/#organization'],
              'offers' => [
                  '@type' => 'Offer',
                  'price' => '0',
                  'priceCurrency' => 'USD',
                  'availability' => 'https://schema.org/InStock',
              ],
              'isAccessibleForFree' => true,
          ];
      }
  }

  $schemaGraph = [
      '@context' => 'https://schema.org',
      '@graph' => $graph,
  ];
@endphp
<script type="application/ld+json">{!! json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
