@php
  use App\Support\ZoomClinicTimes;
  $pageUrl = 'https://bdgrowthsuite.com/zoom-clinics/';
  $pageName = 'Free Brilliant Directories Zoom Clinics — BD Growth Suite';
  $pageDescription = 'Free Brilliant Directories Zoom Clinics every Tuesday and Thursday. BD Growth Suite developers answer live — widgets, CSS, search, email. Drop in, $0.';
  $heroScheduleLine = ZoomClinicTimes::canonicalScheduleLine($featuredClinic ?? $clinics->first());

  $faqItems = [
      [
          'question' => 'What is a Zoom Clinic?',
          'answer' => 'A Zoom Clinic is a free, live drop-in run by BD Growth Suite for directory site owners. Unlike a support ticket, you join by Zoom, share your screen, and get tactical fixes — widgets, CSS, search filters, email templates — solved live by a developer.',
      ],
      [
          'question' => 'How do I get help with my directory website?',
          'answer' => 'Join a free BD Growth Suite Zoom Clinic. Drop in live on Tuesday or Thursday, share your screen, and our developers walk you through widgets, CSS, search, and email templates — no support ticket required.',
      ],
      [
          'question' => 'Are BD Growth Suite Zoom Clinics free?',
          'answer' => 'Yes — every clinic is $0. Register once, get a calendar invite with the Zoom link, and join any session. Strategy and full builds belong on Express Setup or Founder Concierge.',
      ],
      [
          'question' => 'When are Zoom Clinics?',
          'answer' => 'BD Growth Suite runs clinics every Tuesday and Thursday at '.$heroScheduleLine.', with a +30 minute buffer when queues are active. Register in the modal to pick a different timezone for your calendar invite.',
      ],
      [
          'question' => 'What can I ask at a Zoom Clinic?',
          'answer' => 'Tactical fixes: widget CSS, search filters, member dashboard quirks, email templates, and small config blockers. Not full site builds or launch strategy — those go to Express Setup or Founder Concierge.',
      ],
      [
          'question' => 'How do I register for a Zoom Clinic?',
          'answer' => 'Pick any upcoming clinic on this page, click Register, enter your name and email, and we send a calendar invite with the Zoom link. One registration per clinic — drop in live when it starts.',
      ],
  ];

  $graph = [
      [
          '@type' => 'Organization',
          '@id' => 'https://bdgrowthsuite.com/#organization',
          'name' => 'BD Growth Suite',
          'url' => 'https://bdgrowthsuite.com/',
          'logo' => 'https://bdgrowthsuite.com/images/brand/logo.png',
      ],
      [
          '@type' => 'WebPage',
          '@id' => $pageUrl.'#webpage',
          'url' => $pageUrl,
          'name' => $pageName,
          'description' => $pageDescription,
          'isPartOf' => ['@id' => 'https://bdgrowthsuite.com/#website'],
          'speakable' => [
              '@type' => 'SpeakableSpecification',
              'cssSelector' => ['.bdgsownv2-faq-item h3', '.bdgsownv2-faq-item p'],
          ],
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
