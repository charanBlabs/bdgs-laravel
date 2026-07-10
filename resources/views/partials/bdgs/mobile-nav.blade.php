<div class="bdgs-mob-overlay" id="bdgsMobOverlay" aria-hidden="true">
  <div class="bdgs-mob-header">
    <a href="/" class="bdgsownv2-logo">
      <img referrerpolicy="no-referrer" loading="lazy" width="140" height="32" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png" alt="BD Growth Suite">
    </a>
    <button type="button" class="bdgs-mob-close" id="bdgsMobCloseBtn" aria-label="Close menu">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
  </div>
  <div class="bdgs-mob-panels" id="bdgsMobPanels">
    <div class="bdgs-mob-panel bdgs-mob-panel--active" data-panel="main">
      <div class="bdgs-mob-panel-scroll">
        <ul class="bdgs-mob-list">
          <li class="bdgs-mob-item bdgs-mob-item--drillable" data-target="services">
            <span class="bdgs-mob-item-label">Services</span>
            <svg class="bdgs-mob-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </li>
          <li class="bdgs-mob-item bdgs-mob-item--drillable" data-target="grow-ai">
            <span class="bdgs-mob-item-label">Grow with AI</span>
            <svg class="bdgs-mob-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </li>
          <li class="bdgs-mob-item">
            <a href="/hire-developer" class="bdgs-mob-item-link">Hire a Developer</a>
          </li>
          <li class="bdgs-mob-item bdgs-mob-item--drillable" data-target="tools">
            <span class="bdgs-mob-item-label">Tools</span>
            <svg class="bdgs-mob-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </li>
          <li class="bdgs-mob-item">
            <a href="/themes" class="bdgs-mob-item-link">Themes</a>
          </li>
          <li class="bdgs-mob-item bdgs-mob-item--pill">
            <a href="/zoom-clinics" class="bdgs-mob-zoom"><span class="pulse"></span> Zoom Clinics</a>
          </li>
        </ul>
        <div class="bdgs-mob-cta-wrap">
          @auth
          @php($mobUser = auth()->user()->loadMissing(['profile.avatar', 'roles']))
          <div class="bdgs-mob-user">
            @include('partials.bdgs.user-avatar', ['user' => $mobUser, 'size' => 48, 'class' => 'bdgs-mob-user__avatar'])
            <div class="bdgs-mob-user__meta">
              <p class="bdgs-mob-user__name">{{ $mobUser->fullName() }}</p>
              <p class="bdgs-mob-user__role">{{ $mobUser->primaryRoleLabel() }}</p>
            </div>
          </div>
          <a href="{{ route('dashboard') }}" class="bdgs-mob-cta" style="display:block;text-align:center;text-decoration:none;">My Dashboard</a>
          <form method="POST" action="{{ route('logout') }}" class="bdgs-mob-logout">
            @csrf
            <button type="submit" class="bdgs-mob-logout-btn">Log out</button>
          </form>
          @else
          <button type="button" class="bdgs-mob-cta">Get Started →</button>
          @endauth
        </div>
      </div>
    </div>
    <div class="bdgs-mob-panel" data-panel="services">
      <button type="button" class="bdgs-mob-back" data-back="main">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span>Services</span>
      </button>
      <div class="bdgs-mob-panel-scroll">
        <div class="bdgs-mob-section">
          <p class="bdgs-mob-section-heading">Services</p>
          <a href="/setup" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Setup &amp; Launch</span><span class="bdgs-mob-subitem-desc">Concierge directory launch. Business-first approach.</span></a>
          <a href="/hire-developer" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Hire a Developer</span><span class="bdgs-mob-subitem-desc">AI-savvy directory devs. Long-term permanent team.</span></a>
          <a href="/customization" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Custom Projects</span><span class="bdgs-mob-subitem-desc">One-off scoped builds. Integrations and widgets.</span></a>
          <a href="/maintenance" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Maintenance Plans</span><span class="bdgs-mob-subitem-desc">Ongoing support, backups, updates, optimization.</span></a>
          <a href="/consultation" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Consultation</span><span class="bdgs-mob-subitem-desc">Talk to Yakin's team. 18+ years directory expertise.</span></a>
          <a href="/founders-track" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Founder's Track</span><span class="bdgs-mob-subitem-desc">Co-founder level strategy + dev + AI. Apply to join.</span></a>
        </div>
        <div class="bdgs-mob-divider"></div>
        <div class="bdgs-mob-section">
          <p class="bdgs-mob-section-heading">Solutions Done For You</p>
          <a href="/solutions/seo" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">SEO &amp; Schema</span><span class="bdgs-mob-subitem-desc">Advanced markup &amp; technical SEO. Rank higher.</span></a>
          <a href="/solutions/lead-gen" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Lead Gen &amp; Conversion</span><span class="bdgs-mob-subitem-desc">Capture more leads. Turn visitors into members.</span></a>
          <a href="/solutions/member-profiles" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Member Profile Enhancement</span><span class="bdgs-mob-subitem-desc">Custom profile layouts. Make members stand out.</span></a>
          <a href="/solutions/search" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Search &amp; Discovery</span><span class="bdgs-mob-subitem-desc">Optimized search flows. Help users find what they need.</span></a>
          <a href="/solutions/page-design" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Page Design &amp; Development</span><span class="bdgs-mob-subitem-desc">Stunning layouts. Built for modern directories.</span></a>
          <a href="/solutions/content" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Content &amp; Engagement</span><span class="bdgs-mob-subitem-desc">Keep audiences hooked. Automated content strategies.</span></a>
          <a href="/solutions/integrations" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Integrations</span><span class="bdgs-mob-subitem-desc">Connect your favorite tools. Seamless API integrations.</span></a>
          <a href="/solutions/member-management" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Member Management</span><span class="bdgs-mob-subitem-desc">Approval workflows, dashboards, member control.</span></a>
        </div>
        <div class="bdgs-mob-also">
          <p class="bdgs-mob-also-label">Also explore</p>
          <div class="bdgs-mob-also-pills">
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="grow-ai">Grow with AI</a>
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="tools">Tools</a>
            <a href="/themes" class="bdgs-mob-also-pill">Themes</a>
          </div>
        </div>
      </div>
    </div>
    <div class="bdgs-mob-panel" data-panel="grow-ai">
      <button type="button" class="bdgs-mob-back" data-back="main">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span>Grow with AI</span>
      </button>
      <div class="bdgs-mob-panel-scroll">
        <div class="bdgs-mob-section">
          <a href="/ai-development" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">AI-Powered Development</span><span class="bdgs-mob-subitem-desc">Faster execution. Savings passed directly to you.</span></a>
          <a href="/ai-dev-fleet" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">AI Dev Fleet</span><span class="bdgs-mob-subitem-desc">A team of AI works for you. Pay for one developer.</span></a>
          <a href="/ai-for-your-site" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">AI for Your Directory</span><span class="bdgs-mob-subitem-desc">Chatbots, agents, smart search — on your site.</span></a>
          <a href="/bd-automation" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Directory Automation</span><span class="bdgs-mob-subitem-desc">Follow-ups, lead gen, content AI, member engagement.</span></a>
          <a href="/growth-plans" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Growth Plans</span><span class="bdgs-mob-subitem-desc">AI-powered strategy. Apply to join.</span></a>
          <a href="/seo-growth" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">SEO &amp; Growth</span><span class="bdgs-mob-subitem-desc">Audits, schema, traffic strategy — directory-specific.</span></a>
        </div>
        <div class="bdgs-mob-highlight">
          <span>Claude / Anthropic Service Partner<br>— The only directory team building with AI</span>
        </div>
        <div class="bdgs-mob-also">
          <p class="bdgs-mob-also-label">Also explore</p>
          <div class="bdgs-mob-also-pills">
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="services">Services</a>
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="tools">Tools</a>
            <a href="/themes" class="bdgs-mob-also-pill">Themes</a>
          </div>
        </div>
      </div>
    </div>
    <div class="bdgs-mob-panel" data-panel="tools">
      <button type="button" class="bdgs-mob-back" data-back="main">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span>Tools</span>
      </button>
      <div class="bdgs-mob-panel-scroll">
        <div class="bdgs-mob-section">
          <a href="/tools" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">All Tools &amp; Themes</span><span class="bdgs-mob-subitem-desc">Productized software, AI tools, and premium themes.</span></a>
          <a href="/tools/brilliantchat" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">BrilliantChat</span><span class="bdgs-mob-subitem-desc">AI chatbot for your directory site.</span></a>
          <a href="/tools?category=seo" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">SEO Solutions</span><span class="bdgs-mob-subitem-desc">Schema, audit, speed, on-page fixes.</span></a>
          <a href="/tools?category=conversion" class="bdgs-mob-subitem"><span class="bdgs-mob-subitem-title">Conversion Boosters</span><span class="bdgs-mob-subitem-desc">Lead gen, CTAs, member signup flows.</span></a>
        </div>
        <div class="bdgs-mob-also">
          <p class="bdgs-mob-also-label">Also explore</p>
          <div class="bdgs-mob-also-pills">
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="services">Services</a>
            <a href="#" class="bdgs-mob-also-pill" data-nav-to="grow-ai">Grow with AI</a>
            <a href="/themes" class="bdgs-mob-also-pill">Themes</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
