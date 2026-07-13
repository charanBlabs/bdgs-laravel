@php
  $footerActive = function (string $path, ?array $query = null): bool {
      $normalized = ltrim($path, '/');
      if ($normalized === '' || $normalized === '/') {
          return request()->path() === '' || request()->path() === '/';
      }
      if (! request()->is($normalized)) {
          return false;
      }
      if ($query === null) {
          return true;
      }
      foreach ($query as $key => $value) {
          if ((string) request()->query($key) !== (string) $value) {
              return false;
          }
      }
      return true;
  };
  $footerSolutionsIndex = fn (): bool => request()->is('solutions') && ! request()->is('solutions/*');
@endphp
<footer class="bdgsownv2-footer">
  <div class="container">
    <div class="row">
      <div class="col-md-3 col-sm-6">
        <h4>Services</h4>
        <ul>
          <li><a href="/setup" @class(['active-footer-link' => $footerActive('setup')])>Setup &amp; Launch</a></li>
          <li><a href="/hire-developer" @class(['active-footer-link' => $footerActive('hire-developer')])>Hire a Developer</a></li>
          <li><a href="/customization" @class(['active-footer-link' => $footerActive('customization')])>Custom Projects</a></li>
          <li><a href="/maintenance" @class(['active-footer-link' => $footerActive('maintenance')])>Maintenance Plans</a></li>
          <li><a href="/consultation" @class(['active-footer-link' => $footerActive('consultation')])>Consultation</a></li>
          <li><a href="/founders-track" @class(['active-footer-link' => $footerActive('founders-track')])>Founder's Track</a></li>
        </ul>
      </div>
      <div class="col-md-3 col-sm-6">
        <h4>Grow with AI</h4>
        <ul>
          <li><a href="/ai-development" @class(['active-footer-link' => $footerActive('ai-development')])>AI Development</a></li>
          <li><a href="/ai-for-your-site" @class(['active-footer-link' => $footerActive('ai-for-your-site')])>AI for Your Directory</a></li>
          <li><a href="/ai-dev-fleet" @class(['active-footer-link' => $footerActive('ai-dev-fleet')])>AI Dev Fleet</a></li>
          <li><a href="/bd-automation" @class(['active-footer-link' => $footerActive('bd-automation')])>Directory Automation</a></li>
          <li><a href="/seo-growth" @class(['active-footer-link' => $footerActive('seo-growth')])>SEO &amp; Growth</a></li>
          <li class="bdgsownv2-footer-gap-start bdgsownv2-footer-emph"><a href="/tools" @class(['active-footer-link' => $footerActive('tools')])>Tools &amp; Themes</a></li>
          <li><a href="/tools/brilliantchat" @class(['active-footer-link' => $footerActive('tools/brilliantchat')])>BrilliantChat</a></li>
          <li><a href="/themes" @class(['active-footer-link' => $footerActive('themes')])>Directory Themes (6)</a></li>
        </ul>
      </div>
      <div class="col-md-3 col-sm-6">
        <h4>Solutions</h4>
        <ul>
          <li><a href="/solutions/seo" @class(['active-footer-link' => $footerActive('solutions/seo')])>SEO &amp; Schema</a></li>
          <li><a href="/solutions/lead-gen" @class(['active-footer-link' => $footerActive('solutions/lead-gen')])>Lead Gen &amp; Conversion</a></li>
          <li><a href="/solutions/member-profiles" @class(['active-footer-link' => $footerActive('solutions/member-profiles')])>Member Profiles</a></li>
          <li><a href="/solutions/search" @class(['active-footer-link' => $footerActive('solutions/search')])>Search &amp; Discovery</a></li>
          <li><a href="/solutions/page-design" @class(['active-footer-link' => $footerActive('solutions/page-design')])>Page Design &amp; Development</a></li>
          <li><a href="/solutions/content" @class(['active-footer-link' => $footerActive('solutions/content')])>Content &amp; Engagement</a></li>
          <li><a href="/solutions/integrations" @class(['active-footer-link' => $footerActive('solutions/integrations')])>Integrations</a></li>
          <li><a href="/solutions/member-management" @class(['active-footer-link' => $footerActive('solutions/member-management')])>Member Management &amp; Automation</a></li>
          <li class="bdgsownv2-footer-gap-start bdgsownv2-footer-emph"><a href="/solutions" @class(['active-footer-link' => $footerSolutionsIndex()])>Browse All Solutions (61+) &rarr;</a></li>
        </ul>
      </div>
      <div class="col-md-3 col-sm-6">
        <h4>Get Started</h4>
        <ul>
          <li><a href="/zoom-clinics" @class(['active-footer-link' => $footerActive('zoom-clinics')]) style="color:var(--bdgs-coral)">Free Zoom Clinics 🟢</a></li>
          <li><a href="/consultation" @class(['active-footer-link' => $footerActive('consultation')])>Book a Consultation</a></li>
          <li><button type="button" class="bdgsownv2-footer-link" onclick="bdgsOpenInquiryModal(); return false;">Contact Us</button></li>
          <li><button type="button" class="bdgsownv2-footer-link" onclick="bdgsOpenInquiryModal(); return false;">Get Started &rarr;</button></li>
          <li class="bdgsownv2-footer-divider" aria-hidden="true"></li>
          @auth
          <li><a href="{{ route('dashboard') }}" @class(['active-footer-link' => $footerActive('dashboard')])>My Dashboard</a></li>
          @else
          <li><a href="/login" @class(['active-footer-link' => $footerActive('login')])>Login</a></li>
          @endauth
          <li><a href="/about" @class(['active-footer-link' => $footerActive('about')])>About Us</a></li>
          <li><a href="/partner-program" @class(['active-footer-link' => $footerActive('partner-program')])>Partner Program</a></li>
          <li><a href="/webinars" @class(['active-footer-link' => $footerActive('webinars')])>CEO Webinars</a></li>
          <li><a href="/reviews" id="bdgs-footer-reviews-link" @class(['active-footer-link' => $footerActive('reviews')])>Client Reviews ({{ $reviewCount }})</a></li>
          <li><a href="/blog" @class(['active-footer-link' => $footerActive('blog')])>Blog</a></li>
          <li><a href="/case-studies" @class(['active-footer-link' => $footerActive('case-studies')])>Case Studies</a></li>
        </ul>
      </div>
    </div>
    <div class="bdgsownv2-footer-bottom">
      <p>&copy; 2026 BD Growth Suite by BusinessLabs &nbsp;&middot;&nbsp; <a href="/about/terms" @class(['bdgsownv2-footer-legal', 'active-footer-link' => $footerActive('about/terms')])>Terms</a> &nbsp;&middot;&nbsp; <a href="/about/privacy" @class(['bdgsownv2-footer-legal', 'active-footer-link' => $footerActive('about/privacy')])>Privacy</a> &nbsp;&middot;&nbsp; <a href="/license/sdcl-v1" @class(['bdgsownv2-footer-legal', 'active-footer-link' => $footerActive('license/sdcl-v1')])>License</a></p>
      <div class="bdgsownv2-footer-badges">
        <span class="bdgsownv2-footer-badge">🏅 Gold Certified Brilliant Directories Partner</span>
        <span class="bdgsownv2-footer-badge">🤖 Claude / Anthropic Partner</span>
        <span class="bdgsownv2-footer-badge">🌍 500+ Sites · 7+ Countries</span>
      </div>
    </div>
  </div>
</footer>
