@php
  use App\Support\ZoomClinicTimes;
  $scheduleClinic = $featuredClinic ?? $clinics->first();
  $heroScheduleLine = ZoomClinicTimes::canonicalScheduleLine($scheduleClinic);
@endphp

<!-- ══════════════════════════════════════
     HERO
══════════════════════════════════════ -->
<section class="zc-hero">
  <div class="container">
    <div class="zc-hero-inner">
      <div class="zc-hero-copy">
        <div class="zc-badge">
          <span class="zc-pulse" aria-hidden="true"></span>
          FREE · Drop In Anytime
        </div>
        <h1>Brilliant Directories <span>Zoom Clinics</span></h1>
        <p class="zc-hero-lead">Small silly things?<br>Never pay a developer again.<span class="zc-hero-lead-detail">BD Growth Suite's free Brilliant Directories Zoom Clinics — join 500+ site owners getting help with widgets, CSS, search filters, and email templates.</span></p>
        <div class="zc-hero-meta">
          <div class="zc-meta-item">
            <span class="zc-meta-icon" aria-hidden="true">📅</span>
            <span><strong>Tuesdays &amp; Thursdays</strong></span>
          </div>
          <div class="zc-meta-item">
            <span class="zc-meta-icon" aria-hidden="true">🕡</span>
            <span id="zc-hero-time"
              data-fallback="{{ $heroScheduleLine }}"
              @if ($featuredClinic)
                data-clinic-starts="{{ $featuredClinic->session_starts_at->toIso8601String() }}"
                data-clinic-ends="{{ $featuredClinic->session_ends_at->toIso8601String() }}"
              @endif
            >{{ $heroScheduleLine }}</span>
          </div>
          <div class="zc-meta-item zc-meta-buffer">
            <span class="zc-meta-icon" aria-hidden="true">⏱</span>
            <span>+30 min buffer if queues are active</span>
          </div>
        </div>
        @if ($featuredClinic)
        @php
          $heroClinicDate = ZoomClinicTimes::inPageTz($featuredClinic->session_starts_at);
        @endphp
        @endif
      </div>

      <div class="zc-hero-visual" aria-hidden="true">
        <div class="zc-visual-card">
          <div class="zc-visual-header">
            @php
              $heroClinicIsLive = $featuredClinic?->isLiveNow() ?? false;
            @endphp
            <span class="zc-live-dot{{ $heroClinicIsLive ? '' : ' zc-live-dot--upcoming' }}" aria-hidden="true"></span>
            <span>{{ $heroClinicIsLive ? 'Live now' : 'Next clinic' }}</span>
          </div>
          <div class="zc-visual-body">
            <div class="zc-visual-title">{{ $featuredClinic?->title ?? 'Live Website Reviews & Open Q&A' }}</div>
            <div class="zc-visual-time zc-clinic-time"
              data-clinic-starts="{{ $featuredClinic?->session_starts_at?->toIso8601String() }}"
              data-clinic-ends="{{ $featuredClinic?->session_ends_at?->toIso8601String() }}"
              data-clinic-fallback="{{ $featuredClinic ? ZoomClinicTimes::clinicRange($featuredClinic) : $heroScheduleLine }}">{{ $featuredClinic ? ZoomClinicTimes::clinicRange($featuredClinic) : $heroScheduleLine }}</div>
            @if ($featuredClinic)
            <div class="zc-visual-participants">
              @include('partials.zoom-clinic.social-proof', [
                'clinic' => $featuredClinic,
                'registrations' => $featuredClinic->confirmedRegistrations,
                'variant' => 'hero',
              ])
            </div>
            @endif
          </div>
        </div>
        <div class="zc-visual-glow"></div>
      </div>

      @if ($featuredClinic)
      <div class="zc-hero-cta">
        <button type="button" class="zc-btn-primary" data-clinic-id="{{ $featuredClinic->clinic_id }}" onclick="bdgsOpenZoomModal(Number(this.dataset.clinicId))">
          @if ($totalUpcoming > 1)
          Register for {{ $heroClinicDate->format('D, M j') }} →
          @else
          Register for This Clinic →
          @endif
        </button>
        @if ($totalUpcoming > 1)
        <a href="#upcoming-clinics" class="zc-btn-choose">See all {{ $totalUpcoming }} clinics below ↓</a>
        @endif
        <p class="zc-hero-cta-note">@if ($totalUpcoming > 1)The button above registers you for the <strong>next</strong> clinic ({{ $heroClinicDate->format('l, M j') }}). Other dates cover different topics — pick the clinic you want from the list below.@else One quick form — we email your calendar invite and Zoom link. No account required.@endif</p>
      </div>
      @else
      <div class="zc-hero-cta">
        <p class="zc-hero-cta-note" style="margin:0;">No upcoming clinics are open right now. We usually run them every Tuesday and Thursday — check back soon, or ask us below.</p>
        <button type="button" class="zc-btn-primary" style="margin-top:12px;" onclick="bdgsOpenInquiryModal()">Ask about the next clinic</button>
      </div>
      @endif
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     STATS STRIP
══════════════════════════════════════ -->
<section class="zc-stats" aria-label="Zoom Clinics at a glance">
  <div class="container">
    <div class="zc-stats-row">
      <div class="zc-stat">
        <div class="zc-stat-value">{{ $totalUpcoming }}</div>
        <div class="zc-stat-label">Upcoming clinics</div>
      </div>
      <div class="zc-stat">
        <div class="zc-stat-value">$0</div>
        <div class="zc-stat-label">Always free</div>
      </div>
      <div class="zc-stat">
        <div class="zc-stat-value">60 min</div>
        <div class="zc-stat-label">Open Q&amp;A format</div>
      </div>
      <div class="zc-stat">
        <div class="zc-stat-value">500+</div>
        <div class="zc-stat-label">Directories helped</div>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     MAIN: LIST + SIDEBAR
══════════════════════════════════════ -->
<section class="zc-main" id="upcoming-clinics">
  <div class="container">
    <div class="zc-layout">

      <div class="zc-list-col">
        <div class="zc-section-head">
          <h2 id="upcoming-clinics-list">Which clinics can I join next?</h2>
          <p>Pick a clinic, register once, and get a calendar invite with the Zoom link. These are partner-run free clinics — not Brilliant Directories official support.</p>
        </div>

        @forelse ($clinics as $clinic)
        @php
          $isFeatured = $featuredClinic && $clinic->clinic_id === $featuredClinic->clinic_id;
          $nyDate = ZoomClinicTimes::inPageTz($clinic->session_starts_at);
          $whenLine = ZoomClinicTimes::clinicRange($clinic);
        @endphp
        <article class="zc-card{{ $isFeatured ? ' zc-card--featured' : '' }}" id="{{ $clinic->slug }}">
          <div class="zc-card-accent"></div>
          <div class="zc-card-inner">
            <div class="zc-card-top">
              <div class="zc-card-date-badge">
                <span class="zc-date-day">{{ $nyDate->format('d') }}</span>
                <span class="zc-date-mon">{{ $nyDate->format('M') }}</span>
              </div>
              <div class="zc-card-meta">
                <div class="zc-card-tags">
                  @if ($isFeatured)
                  <span class="zc-tag zc-tag-next">Next up</span>
                  @endif
                  <span class="zc-tag zc-tag-free">FREE</span>
                  <span class="zc-tag zc-tag-day">{{ $nyDate->format('l') }}</span>
                </div>
                <h3 class="zc-card-title">{{ $clinic->title }}</h3>
                <p class="zc-card-desc">{{ $clinic->description }}</p>
              </div>
            </div>

            <div class="zc-card-details">
              <div class="zc-detail-row">
                <span class="zc-detail-label">When</span>
                <span class="zc-clinic-time zc-detail-value"
                  data-clinic-starts="{{ $clinic->session_starts_at->toIso8601String() }}"
                  data-clinic-ends="{{ $clinic->session_ends_at->toIso8601String() }}"
                  data-clinic-fallback="{{ $whenLine }}">{{ $whenLine }}</span>
              </div>
              <div class="zc-detail-row">
                <span class="zc-detail-label">Agenda</span>
                <span class="zc-detail-value">{{ $clinic->agenda }}</span>
              </div>
              <div class="zc-detail-row">
                <span class="zc-detail-label">Format</span>
                <span class="zc-detail-value">{{ $clinic->format_note }} <span class="zc-buffer-note">(+30 min if needed)</span></span>
              </div>
            </div>

            <div class="zc-card-footer">
              <div class="zc-participants">
                @include('partials.zoom-clinic.social-proof', [
                  'clinic' => $clinic,
                  'registrations' => $clinic->confirmedRegistrations,
                  'variant' => 'card',
                ])
              </div>
              <button type="button" class="zc-btn-register" data-clinic-id="{{ $clinic->clinic_id }}" onclick="bdgsOpenZoomModal(Number(this.dataset.clinicId))">
                Register →
              </button>
            </div>
          </div>
        </article>
        @empty
        <div class="zc-empty">
          <p>No upcoming clinics scheduled right now. Check back soon — we run clinics every Tuesday and Thursday.</p>
          <button type="button" class="zc-btn-primary" onclick="bdgsOpenInquiryModal()">Ask us when the next clinic is →</button>
        </div>
        @endforelse
      </div>

      <aside class="zc-sidebar">
        <div class="zc-sidebar-card">
          <h3 id="need-more-help">Need more than a clinic?</h3>
          <p class="zc-sidebar-lead">Clinics fix small things live.<br>Strategy, launch, and full builds need a deeper partnership.</p>

          <div class="zc-founder-teaser">
            <span class="zc-founder-tag">Apply only</span>
            <p class="zc-founder-teaser-copy">
              <strong>Founder Concierge</strong> — Yakin-led strategy for launches and full builds. From $3,500.
              <a href="/setup#founder-concierge">Apply →</a>
            </p>
          </div>

          <ul class="zc-sidebar-links">
            <li><a href="/setup#express">Express Setup — launch in 3 days →</a></li>
            <li><a href="/webinars">CEO webinars on demand →</a></li>
          </ul>
          <button type="button" class="zc-btn-inquiry" onclick="bdgsOpenInquiryModal()">Tell Us About Your Project →</button>
        </div>

        <div class="zc-sidebar-card zc-sidebar-card--trust">
          <span class="zc-trust-badge">🏅 Gold Certified Brilliant Directories Partner</span>
          <h3>Why Directory Owners Trust Us</h3>
          <p class="zc-trust-intro">The same team running your free clinics — Gold Certified, founder-led, and built for Brilliant Directories.</p>
          <ul class="zc-trust-stats">
            <li><span class="zc-trust-val">500+</span> Directories Directly Served</li>
            <li><span class="zc-trust-val">{{ $reviewCount }}</span> Verified Reviews</li>
            <li><span class="zc-trust-val">10+</span> Years in Directory Ecosystem</li>
            <li><span class="zc-trust-val">20+</span> In-House Developers</li>
          </ul>
        </div>
      </aside>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     NEXT STEPS
══════════════════════════════════════ -->
<section class="zc-next-steps" aria-label="Beyond free clinics">
  <div class="container">
    <div class="zc-next-steps-inner">
      <p>Need a full build? See our <a href="/services/">services hub</a> or return to <a href="/">BD Growth Suite home</a>.</p>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     FAQ
══════════════════════════════════════ -->
<section class="bdgsownv2-section bdgsownv2-faq-section" id="zoom-clinic-faq">
  <div class="container">
    <div class="bdgsownv2-faq-header">
      <h2 id="zoom-clinic-questions">What do directory owners ask about <span class="zc-faq-title-break"><span class="zc-title-keep">Zoom Clinics?</span></span></h2>
      <p>They ask whether clinics are free, when they run, what to bring live, and how to register — quick answers below.</p>
    </div>
    <div class="bdgsownv2-faq-grid">
      <div class="bdgsownv2-faq-item">
        <h3>What is a Zoom Clinic?</h3>
        <p>A Zoom Clinic is a free, live drop-in run by BD Growth Suite for directory site owners. Unlike a support ticket, you join by Zoom, share your screen, and get tactical fixes — widgets, CSS, search filters, email templates — solved live by a developer.</p>
      </div>
      <div class="bdgsownv2-faq-item">
        <h3>How do I get help with my directory website?</h3>
        <p>Join a free BD Growth Suite Zoom Clinic. Drop in live on Tuesday or Thursday, share your screen, and our developers walk you through widgets, CSS, search, and email templates — no support ticket required.</p>
      </div>
      <div class="bdgsownv2-faq-item">
        <h3>Are BD Growth Suite Zoom Clinics free?</h3>
        <p>Yes — every clinic is $0. Register once, get a calendar invite with the Zoom link, and join any session. Strategy and full builds belong on Express Setup or Founder Concierge.</p>
      </div>
      <div class="bdgsownv2-faq-item">
        <h3>When are Zoom Clinics?</h3>
        <p>BD Growth Suite runs clinics every Tuesday and Thursday at {{ $heroScheduleLine }}, with a +30 minute buffer when queues are active. Register in the modal to pick a different timezone for your calendar invite.</p>
      </div>
      <div class="bdgsownv2-faq-item">
        <h3>What can I ask at a Zoom Clinic?</h3>
        <p>Tactical fixes: widget CSS, search filters, member dashboard quirks, email templates, and small config blockers. Not full site builds or launch strategy — those go to Express Setup or Founder Concierge.</p>
      </div>
      <div class="bdgsownv2-faq-item">
        <h3>How do I register for a Zoom Clinic?</h3>
        <p>Pick any upcoming clinic above, click Register, enter your name and email, and we send a calendar invite with the Zoom link. One registration per clinic — drop in live when it starts.</p>
      </div>
    </div>
  </div>
</section>
