
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
        <h1 id="zoom-clinics-title">Brilliant Directories <span>Zoom Clinics</span></h1>
        <p class="zc-hero-lead">Small silly things?<br>Never pay a developer again.<span class="zc-hero-lead-detail">Free Brilliant Directories Zoom Clinics — drop in live for widgets, CSS, search filters, and email templates.</span></p>
        <div class="zc-hero-meta">
          <div class="zc-meta-item">
            <span class="zc-meta-icon" aria-hidden="true">📅</span>
            <span><strong>Tuesdays &amp; Thursdays</strong></span>
          </div>
          <div class="zc-meta-item">
            <span class="zc-meta-icon" aria-hidden="true">🕡</span>
            <span id="zc-hero-time">6:30–7:30 PM IST</span>
          </div>
          <div class="zc-meta-item zc-meta-buffer">
            <span class="zc-meta-icon" aria-hidden="true">⏱</span>
            <span>+30 min buffer if queues are active</span>
          </div>
        </div>
        @if ($featuredClinic)
        <div class="zc-hero-cta">
          <button type="button" class="zc-btn-primary" data-clinic-id="{{ $featuredClinic->clinic_id }}" onclick="bdgsOpenZoomModal(Number(this.dataset.clinicId))">
            Register for Next Session →
          </button>
          <a href="#upcoming-clinics" class="zc-btn-ghost">Browse all {{ $totalUpcoming }} upcoming →</a>
        </div>
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
              data-clinic-fallback="6:30–7:30 PM IST">Loading…</div>
            @if ($featuredClinic)
            @php
              $heroRegs = $featuredClinic->confirmedRegistrations;
            @endphp
            <div class="zc-visual-participants">
              <div class="zc-avatar-stack">
                @foreach ($heroRegs->take(5) as $reg)
                <span class="zc-avatar" title="{{ $reg->name }}">{{ $reg->initials() }}</span>
                @endforeach
                @if ($heroRegs->count() > 5)
                <span class="zc-avatar zc-avatar-more">+{{ $heroRegs->count() - 5 }}</span>
                @endif
              </div>
              <span class="zc-participant-count">{{ $heroRegs->count() }} registered</span>
            </div>
            @endif
          </div>
        </div>
        <div class="zc-visual-glow"></div>
      </div>
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
        <div class="zc-stat-label">Upcoming sessions</div>
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
          <h2 id="upcoming-sessions">Which clinics can I join next?</h2>
          <p>Pick a session, register once, and get a calendar invite with the Zoom link. These are partner-run free clinics — not Brilliant Directories official support.</p>
        </div>

        @forelse ($clinics as $clinic)
        @php
          $regs = $clinic->confirmedRegistrations;
          $isFeatured = $featuredClinic && $clinic->clinic_id === $featuredClinic->clinic_id;
          $istDate = $clinic->session_starts_at->copy()->timezone('Asia/Kolkata');
        @endphp
        <article class="zc-card{{ $isFeatured ? ' zc-card--featured' : '' }}" id="{{ $clinic->slug }}">
          <div class="zc-card-accent"></div>
          <div class="zc-card-inner">
            <div class="zc-card-top">
              <div class="zc-card-date-badge">
                <span class="zc-date-day">{{ $istDate->format('d') }}</span>
                <span class="zc-date-mon">{{ $istDate->format('M') }}</span>
              </div>
              <div class="zc-card-meta">
                <div class="zc-card-tags">
                  @if ($isFeatured)
                  <span class="zc-tag zc-tag-next">Next up</span>
                  @endif
                  <span class="zc-tag zc-tag-free">FREE</span>
                  <span class="zc-tag zc-tag-day">{{ $istDate->format('l') }}</span>
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
                  data-clinic-fallback="{{ $istDate->format('D, M j') }} · 6:30–7:30 PM IST">Loading…</span>
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
                @if ($regs->isNotEmpty())
                <div class="zc-avatar-stack" aria-label="{{ $regs->count() }} registered participants">
                  @foreach ($regs->take(6) as $reg)
                  <span class="zc-avatar" title="{{ $reg->name }}">{{ $reg->initials() }}</span>
                  @endforeach
                  @if ($regs->count() > 6)
                  <span class="zc-avatar zc-avatar-more">+{{ $regs->count() - 6 }}</span>
                  @endif
                </div>
                <span class="zc-participant-count">{{ $regs->count() }} {{ Str::plural('participant', $regs->count()) }} registered</span>
                @else
                <span class="zc-participant-empty">Be the first to register</span>
                @endif
              </div>
              <button type="button" class="zc-btn-register" data-clinic-id="{{ $clinic->clinic_id }}" onclick="bdgsOpenZoomModal(Number(this.dataset.clinicId))">
                Register →
              </button>
            </div>
          </div>
        </article>
        @empty
        <div class="zc-empty">
          <p>No upcoming clinics scheduled right now. Check back soon — we run sessions every Tuesday and Thursday.</p>
          <button type="button" class="zc-btn-primary" onclick="bdgsOpenInquiryModal()">Ask us when the next clinic is →</button>
        </div>
        @endforelse
      </div>

      <aside class="zc-sidebar">
        <div class="zc-sidebar-card">
          <h3 id="how-it-works">How It Works</h3>
          <ol class="zc-steps">
            <li>
              <span class="zc-step-num">1</span>
              <div>
                <strong>Pick a session</strong>
                <p>Choose any upcoming Tuesday or Thursday clinic that fits your topic.</p>
              </div>
            </li>
            <li>
              <span class="zc-step-num">2</span>
              <div>
                <strong>Register in 30 seconds</strong>
                <p>Name and email — we send a calendar invite with the Zoom link.</p>
              </div>
            </li>
            <li>
              <span class="zc-step-num">3</span>
              <div>
                <strong>Drop in live</strong>
                <p>Share your screen or ask questions. Small fixes, live answers.</p>
              </div>
            </li>
          </ol>

          <div class="zc-sidebar-note">
            <strong>Strategy questions?</strong>
            <p>Those need our <a href="/services/">Express Setup</a> on <a href="/services/">Explore Services</a> or Founder Concierge. Zoom Clinics are for the small, tactical stuff on your Brilliant Directories site — always free.</p>
          </div>

          <div class="zc-tz-note">
            <span aria-hidden="true">🌍</span>
            <p>Times convert to your timezone automatically — EDT, EST, PST, GMT, and more. Daylight saving included.</p>
          </div>

          <div class="zc-sidebar-next">
            <h4 id="need-more-help">Need more than a clinic?</h4>
            <p>Clinics are for quick fixes. When you want us to do the work:</p>
            <ul>
              <li><a href="/">BD Growth Suite home →</a></li>
              <li><a href="/services/">Explore Services →</a></li>
              <li><a href="/services/">Express Setup — launch in 3 days →</a></li>
              <li><a href="/customization">Custom Project — scoped build →</a></li>
              <li><a href="/blabs-review">176 verified client reviews →</a></li>
              <li><a href="/webinars">CEO webinars on demand →</a></li>
            </ul>
            <button type="button" class="zc-btn-inquiry" onclick="bdgsOpenInquiryModal()">Tell Us About Your Project →</button>
          </div>
        </div>
      </aside>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     FAQ
══════════════════════════════════════ -->
<section class="zc-faq" id="zoom-clinic-faq">
  <div class="container">
    <div class="zc-faq-head">
      <h2 id="zoom-clinic-questions">What do directory owners ask about Zoom Clinics?</h2>
      <p>Quick answers before you register. For CEO strategy sessions, see our <a href="/webinars">webinars</a>.</p>
    </div>
    <div class="zc-faq-list">
      <article class="zc-faq-item">
        <h3>How do I get help with my Brilliant Directories website?</h3>
        <p>Join a free BD Growth Suite Zoom Clinic. Drop in live on Tuesday or Thursday, share your screen, and our Brilliant Directories developers walk you through widgets, CSS, search, and email templates — no support ticket required.</p>
      </article>
      <article class="zc-faq-item">
        <h3>Are BD Growth Suite Zoom Clinics free?</h3>
        <p>Yes — every Brilliant Directories Zoom Clinic is $0. Register once, get a calendar invite with the Zoom link, and join any session. Strategy and full builds belong on Express Setup or a custom project.</p>
      </article>
      <article class="zc-faq-item">
        <h3>When are Brilliant Directories Zoom Clinics?</h3>
        <p>BD Growth Suite runs Zoom Clinics every Tuesday and Thursday at 6:30–7:30 PM IST, with a +30 minute buffer when queues are active. Times convert automatically to your local timezone.</p>
      </article>
      <article class="zc-faq-item">
        <h3>What can I ask at a Brilliant Directories Zoom Clinic?</h3>
        <p>Tactical fixes: widget CSS, search filters, member dashboard quirks, email templates, and small config blockers. Not full site builds or launch strategy — those go to Express Setup or Founder Concierge.</p>
      </article>
      <article class="zc-faq-item">
        <h3>How do I register for a Zoom Clinic?</h3>
        <p>Pick any upcoming session above, click Register, enter your name and email, and we send a calendar invite with the Zoom link. One registration per session — drop in live when it starts.</p>
      </article>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════
     FINAL CTA
══════════════════════════════════════ -->
<section class="zc-final-cta">
  <div class="container">
    <div class="zc-final-inner">
      <h2 id="zoom-clinic-cta">Ready to fix that thing that's been bugging you for weeks?</h2>
      <p>Join the next free clinic — no sales pitch, just developers who know Brilliant Directories inside out.</p>
      @if ($featuredClinic)
      <button type="button" class="zc-btn-primary zc-btn-lg" data-clinic-id="{{ $featuredClinic->clinic_id }}" onclick="bdgsOpenZoomModal(Number(this.dataset.clinicId))">
        Join Next Free Session →
      </button>
      @endif
    </div>
  </div>
</section>
