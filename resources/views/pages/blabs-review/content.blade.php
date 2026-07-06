@verbatim
<style>
.rev-page-reviews {
  padding: 0 clamp(16px, 4vw, 24px);
  max-width: 1248px;
  margin: clamp(32px, 5vw, 48px) auto 0;
}
.bdgsownv2-reviews-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  max-width: 1200px;
  margin: 0 auto;
  align-items: stretch;
}
@media (max-width: 768px) {
  .bdgsownv2-reviews-grid {
    grid-template-columns: 1fr;
  }
}
.bdgsownv2-review-card {
  background: var(--bdgs-white);
  border: 1px solid var(--bdgs-border);
  border-radius: 12px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  min-height: 320px;
}
.bdgsownv2-reviews-grid .rev-v3 {
  display: flex;
  flex-direction: column;
  min-height: 326px;
}
.bdgsownv2-reviews-grid .rev-v3__inner {
  flex: 1;
  min-height: 320px;
  display: flex;
  flex-direction: column;
}
.bdgsownv2-reviews-grid .rev-v3__inner .bdgsownv2-review-body {
  flex: 1 1 auto;
}
/* No card-level hover — per locked review spec */
.bdgsownv2-review-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
}
.bdgsownv2-review-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--bdgs-dark);
  margin: 0 0 6px;
}
.bdgsownv2-review-meta {
  font-size: 12px;
  color: var(--bdgs-text-muted);
  margin-bottom: 12px;
}
.bdgsownv2-review-stars {
  color: #F59E0B; /* Amber/Gold */
  font-size: 14px;
  display: flex;
  gap: 2px;
}
.bdgsownv2-review-body {
  font-size: 15px;
  color: var(--bdgs-text);
  line-height: 1.6;
  margin-bottom: 0;
  flex: 1 1 auto;
}
.bdgsownv2-card-footer {
  margin-top: 20px;
  padding-top: 16px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid var(--bdgs-border);
}
.bdgsownv2-verified-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 600;
  color: #16A34A;
}
.bdgsownv2-verified-badge svg {
  width: 14px;
  height: 14px;
  flex-shrink: 0;
}
.bdgsownv2-verify-link {
  font-size: 12px;
  color: var(--bdgs-text-muted);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  transition: color 0.2s;
}
.bdgsownv2-verify-link:hover {
  color: var(--bdgs-coral);
  text-decoration: underline;
}
.title-strikethrough {
  text-decoration: line-through;
  color: var(--bdgs-text-muted);
  font-weight: 600;
  text-decoration-thickness: 3px;
}
/* Skeleton placeholders — same v3 frame as live cards; static (no shimmer) to avoid “dancing” */
.rev-v3--skeleton {
  background: linear-gradient(135deg, var(--prm-gold-bright), var(--prm-gold) 38%, var(--prm-gold) 52%, var(--prm-gold-deep) 68%, var(--prm-gold-bright));
}
.rev-v3--skeleton .rev-skeleton {
  border: none;
  border-radius: 11px;
  background: var(--bdgs-white);
  min-height: 320px;
  flex: 1;
  margin: 0;
}
.rev-skeleton {
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.rev-skeleton__line {
  height: 12px;
  border-radius: 6px;
  background: var(--bdgs-border);
}
.rev-skeleton__line--title { height: 20px; width: 70%; }
.rev-skeleton__line--short { width: 45%; }
.rev-skeleton__line--body { flex: 1; min-height: 80px; }
.rev-skeleton__line--pill { height: 28px; width: 100%; border-radius: 8px; }
.bdgsownv2-review-details {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  padding-bottom: 16px;
  margin-bottom: 16px;
  border-bottom: 1px dashed var(--bdgs-border);
}
@media (max-width: 480px) {
  .bdgsownv2-proof-section { padding: 48px 0 40px; }
  .bdgsownv2-proof-section .proof-sub { margin-bottom: 32px; }
  .bdgsownv2-logo-grid { gap: 16px 20px; margin-bottom: 40px; }
  .bdgsownv2-card-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  .bdgsownv2-review-details {
    grid-template-columns: 1fr;
  }
}
.bdgsownv2-rating-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.bdgsownv2-rating-label {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  color: var(--bdgs-text-muted);
  letter-spacing: 0.05em;
}
.bdgsownv2-load-more-container {
  text-align: center;
  margin-top: 40px;
}
.bdgsownv2-load-more-btn {
  display: inline-block;
  padding: 12px 24px;
  font-size: 15px;
  font-weight: 600;
  color: var(--bdgs-coral);
  background: var(--bdgs-coral-light);
  border: 1px solid rgba(231,77,86,0.2);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}
.bdgsownv2-load-more-btn:hover {
  background: var(--bdgs-coral);
  color: #fff;
}
.bdgsownv2-load-more-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* ============================================
   REVIEW CARD VARIANTS (v1 / v2 / v3) — pick one
   ============================================ */
.rev-variants {
  max-width: 1200px;
  margin: 0 auto;
  padding: clamp(32px, 5vw, 56px) 24px;
}
.rev-variants__heading {
  font-size: var(--fs-h2);
  font-weight: 700;
  line-height: var(--lh-h2);
  letter-spacing: var(--ls-h2);
  text-align: center;
  color: var(--bdgs-dark);
  margin: 0 0 8px;
  letter-spacing: -0.5px;
}
.rev-variants__sub {
  text-align: center;
  font-size: var(--fs-lead);
  color: var(--bdgs-text-muted);
  margin: 0 auto 32px;
  max-width: 640px;
  line-height: 1.6;
}
.rev-variants__grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  align-items: stretch;
}
@media (max-width: 768px) {
  .rev-variants__grid,
  .rev-shimmer-row {
    grid-template-columns: 1fr;
  }
}
.rev-variants__grid > div,
.rev-shimmer-row > div {
  display: flex;
  flex-direction: column;
}
.rev-shimmer-row > article {
  display: flex;
  flex-direction: column;
  flex: 1;
}
.rev-variant-label {
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: var(--bdgs-coral);
  background: var(--bdgs-coral-light);
  padding: 4px 10px;
  border-radius: 4px;
  margin-bottom: 12px;
}
/* v1 — clean white card (matches live .bdgsownv2-review-card sizing) */
.rev-v1 {
  background: var(--bdgs-white);
  border: 1px solid var(--bdgs-border);
  border-radius: 12px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  min-height: 320px;
  flex: 1;
}
/* v2 — premium frame with corners + medal */
.rev-v2 {
  position: relative;
  padding: 10px;
  border-radius: 14px;
  background: linear-gradient(155deg, var(--prm-frame-dark), var(--prm-frame-mid) 50%, var(--prm-frame-dark));
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  flex: 1;
  display: flex;
  flex-direction: column;
}
.rev-v2__inner {
  position: relative;
  background: radial-gradient(120% 140% at 12% 0%, #fff 0%, var(--prm-paper) 28%, var(--prm-paper-edge) 100%);
  border-radius: 8px;
  padding: 24px;
  min-height: 320px;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.rev-v2__corner {
  position: absolute; width: 24px; height: 24px;
  border: 2px solid var(--prm-gold-deep); opacity: 0.85;
}
.rev-v2__corner--tl { top: 10px; left: 10px; border-right: none; border-bottom: none; }
.rev-v2__corner--tr { top: 10px; right: 10px; border-left: none; border-bottom: none; }
.rev-v2__corner--bl { bottom: 10px; left: 10px; border-right: none; border-top: none; }
.rev-v2__corner--br { bottom: 10px; right: 10px; border-left: none; border-top: none; }
/* v3 — gold border, no corners/certificate */
.rev-v3 {
  position: relative;
  padding: 3px;
  border-radius: 14px;
  background: linear-gradient(135deg, var(--prm-gold-bright), var(--prm-gold) 38%, var(--prm-gold) 52%, var(--prm-gold-deep) 68%, var(--prm-gold-bright));
  overflow: hidden;
  flex: 1;
  display: flex;
  flex-direction: column;
}
.rev-v3__inner {
  background: var(--bdgs-white);
  border-radius: 11px;
  padding: 24px;
  min-height: 320px;
  display: flex;
  flex-direction: column;
  position: relative;
  z-index: 1;
  flex: 1;
}
/* Demo slots inherit live card layout */
[data-rev-demo-slot] {
  display: flex;
  flex-direction: column;
  flex: 1;
  min-height: 0;
}
[data-rev-demo-slot] .bdgsownv2-review-body {
  flex: 1 1 auto;
}
/* Shimmer variants for v3 */
.rev-v3--shimmer-a::before {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.45) 50%, transparent 60%);
  background-size: 200% 100%;
  animation: revGoldShimmer 3s ease-in-out infinite;
}
.rev-v3--shimmer-b::before {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
  background: conic-gradient(from 0deg, transparent, rgba(240,217,140,0.5), transparent 30%);
  animation: revGoldSpin 4s linear infinite;
}
.rev-v3--shimmer-c::after {
  content: '';
  position: absolute; inset: -1px; border-radius: inherit; pointer-events: none;
  box-shadow: inset 0 0 20px rgba(240,217,140,0.35);
  animation: revGoldPulse 2.5s ease-in-out infinite;
}
/* D — single trophy shine: seamless LTR loop (repeating tile, no end jerk) */
.rev-v3--shimmer-d::before {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none; z-index: 0;
  background: linear-gradient(
    105deg,
    transparent 0%,
    transparent 38%,
    rgba(255, 255, 255, 0.15) 44%,
    rgba(255, 248, 220, 0.55) 50%,
    rgba(255, 255, 255, 0.15) 56%,
    transparent 62%,
    transparent 100%
  );
  background-size: 50% 100%;
  background-repeat: repeat-x;
  background-position: 0 0;
  animation: revTrophyShine 8s linear infinite;
  will-change: background-position;
}
/* Inner content shimmer — same A/B/C/D styles, scoped to white .rev-v3__inner only */
.rev-v3[class*="--inner-"] .rev-v3__inner {
  overflow: hidden;
}
.rev-v3--inner-a .rev-v3__inner::before {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none; z-index: 0;
  /* Specular shine on white — same sweep motion as border-a, white peak only (no gold wash) */
  background: linear-gradient(
    90deg,
    transparent 0%,
    transparent 42%,
    rgba(232, 232, 238, 0.3) 46%,
    rgba(255, 255, 255, 0.6) 48.5%,
    rgba(255, 255, 255, 0.85) 50%,
    rgba(255, 255, 255, 0.6) 51.5%,
    rgba(232, 232, 238, 0.3) 54%,
    transparent 58%,
    transparent 100%
  );
  background-size: 320% 100%;
  background-position: 280% 0;
  animation: revInnerSweep 6.5s linear infinite;
}
.rev-v3--inner-b .rev-v3__inner::before {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none; z-index: 0;
  /* Diagonal specular wave — back-and-forth on white (replaces gold rotate) */
  background: linear-gradient(
    105deg,
    transparent 0%,
    transparent 36%,
    rgba(232, 232, 238, 0.3) 42%,
    rgba(255, 255, 255, 0.55) 48%,
    rgba(255, 255, 255, 0.75) 50%,
    rgba(255, 255, 255, 0.55) 52%,
    rgba(232, 232, 238, 0.3) 58%,
    transparent 64%,
    transparent 100%
  );
  background-size: 220% 100%;
  animation: revInnerDiagWave 5s ease-in-out infinite;
}
.rev-v3--inner-c .rev-v3__inner::after {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none; z-index: 0;
  box-shadow: inset 0 0 20px rgba(240,217,140,0.35);
  animation: revGoldPulse 2.5s ease-in-out infinite;
}
.rev-v3--inner-d .rev-v3__inner::before {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none; z-index: 0;
  background: linear-gradient(
    105deg,
    transparent 0%,
    transparent 38%,
    rgba(255, 255, 255, 0.15) 44%,
    rgba(255, 248, 220, 0.55) 50%,
    rgba(255, 255, 255, 0.15) 56%,
    transparent 62%,
    transparent 100%
  );
  background-size: 50% 100%;
  background-repeat: repeat-x;
  background-position: 0 0;
  animation: revTrophyShine 8s linear infinite;
  will-change: background-position;
}
.rev-v3[class*="--inner-"] [data-rev-demo-slot] {
  position: relative;
  z-index: 1;
}
/* Border-only shimmer: highlight travels only on the gold frame ring */
.rev-v3[class*="--border-"] {
  background: linear-gradient(135deg, var(--prm-gold-deep), var(--prm-gold) 40%, var(--prm-gold-deep));
}
.rev-v3[class*="--border-"]::before {
  content: '';
  position: absolute; inset: 0; border-radius: inherit; pointer-events: none; z-index: 0;
  padding: 3px;
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  mask-composite: exclude;
}
/* Border A — LTR sweep on frame (was shimmer-e) */
.rev-v3--border-a::before {
  background: linear-gradient(
    90deg,
    var(--prm-gold-deep) 0%,
    var(--prm-gold) 18%,
    var(--prm-gold-bright) 32%,
    rgba(255, 255, 255, 0.85) 50%,
    var(--prm-gold-bright) 68%,
    var(--prm-gold) 82%,
    var(--prm-gold-deep) 100%
  );
  background-size: 280% 100%;
  background-position: -180% 0;
  animation: revBorderShine 4.5s linear infinite;
}
/* Border B — back-and-forth sweep on frame (mirrors card A) */
.rev-v3--border-b::before {
  background: linear-gradient(
    90deg,
    var(--prm-gold-deep) 0%,
    var(--prm-gold) 25%,
    var(--prm-gold-bright) 50%,
    var(--prm-gold) 75%,
    var(--prm-gold-deep) 100%
  );
  background-size: 200% 100%;
  animation: revGoldShimmer 3s ease-in-out infinite;
}
/* Border C — trophy shine LTR on frame (mirrors card D) */
.rev-v3--border-c::before {
  background: linear-gradient(
    90deg,
    var(--prm-gold-deep) 0%,
    var(--prm-gold-deep) 35%,
    var(--prm-gold) 42%,
    var(--prm-gold-bright) 50%,
    var(--prm-gold) 58%,
    var(--prm-gold-deep) 65%,
    var(--prm-gold-deep) 100%
  );
  background-size: 220% 100%;
  background-position: -120% 0;
  animation: revTrophyShine 5.5s linear infinite;
}
/* Border D — soft gold pulse on frame */
.rev-v3--border-d::before {
  background: linear-gradient(135deg, var(--prm-gold-deep), var(--prm-gold), var(--prm-gold-deep));
  animation: revBorderPulse 2.5s ease-in-out infinite;
}
@keyframes revBorderPulse {
  0%, 100% { opacity: 0.65; filter: brightness(0.95); }
  50% { opacity: 1; filter: brightness(1.15); }
}
@keyframes revGoldShimmer {
  0%, 100% { background-position: 200% 0; }
  50% { background-position: -200% 0; }
}
@keyframes revTrophyShine {
  0% { background-position: 0% 0; }
  100% { background-position: 100% 0; }
}
@keyframes revInnerDiagWave {
  0%, 100% { background-position: 200% 0; }
  50% { background-position: -200% 0; }
}
@keyframes revInnerSweep {
  from { background-position: 280% 0; }
  to { background-position: -180% 0; }
}
@keyframes revBorderShine {
  from { background-position: -180% 0; }
  to { background-position: 280% 0; }
}
@keyframes revGoldSpin {
  to { transform: rotate(360deg); }
}
@keyframes revGoldPulse {
  0%, 100% { opacity: 0.4; }
  50% { opacity: 1; }
}
@media (prefers-reduced-motion: reduce) {
  .rev-v3--shimmer-a::before, .rev-v3--shimmer-b::before, .rev-v3--shimmer-c::after,
  .rev-v3--shimmer-d::before,
  .rev-v3--inner-a .rev-v3__inner::before, .rev-v3--inner-b .rev-v3__inner::before,
  .rev-v3--inner-c .rev-v3__inner::after, .rev-v3--inner-d .rev-v3__inner::before,
  .rev-v3[class*="--border-"]::before { animation: none; }
}
.rev-shimmer-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  margin-top: 40px;
  padding-top: 32px;
  border-top: 1px solid var(--bdgs-border);
  align-items: stretch;
}
.rev-shimmer-row--border {
  margin-top: 28px;
  padding-top: 28px;
  border-top: 1px dashed var(--bdgs-border);
}
.rev-shimmer-row--inner {
  margin-top: 28px;
  padding-top: 28px;
  border-top: 1px dashed var(--bdgs-border);
}
.rev-shimmer-row h3,
.rev-shimmer-row h4 {
  grid-column: 1 / -1;
  font-size: var(--fs-h3);
  font-weight: 700;
  text-align: center;
  color: var(--bdgs-dark);
  margin: 0 0 8px;
}
.rev-shimmer-row h4 {
  font-size: clamp(16px, 14px + 0.4vw, 20px);
  font-weight: 600;
  color: var(--bdgs-text-muted);
}
.rev-shimmer-row__note {
  grid-column: 1 / -1;
  text-align: center;
  font-size: 14px;
  color: var(--bdgs-text-muted);
  margin: 0 0 12px;
  line-height: 1.5;
}
.rev-market-link {
  font-size: 12px;
  font-weight: 500;
  color: var(--bdgs-text-muted);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  transition: color 0.2s;
}
.rev-market-link:hover { color: var(--bdgs-coral); text-decoration: underline; }
</style>

<!-- SEO Schema.org Markup — the dynamic schema in updateSchemaGraph() is the canonical source;
     this static block is a fallback for bots that don't execute JS. -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "@id": "https://bdgrowthsuite.com/#organization",
  "name": "Business Labs",
  "alternateName": "BD Growth Suite",
  "url": "https://bdgrowthsuite.com",
  "logo": "https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/logo.png",
  "description": "Expert Brilliant Directories developers and partners. BD Growth Suite by BusinessLabs.",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "5.0",
    "reviewCount": "176",
    "bestRating": "5",
    "worstRating": "1"
  }
}
</script>
@endverbatim

<main>
  <section class="bdgsownv2-section bdgsownv2-proof-section">
    <div class="container">
      <div class="bdgsownv2-proof-head">
      <h1 id="reviews-page-title">We Made These Directories <span class="title-strikethrough">Happy</span> <em>VERY HAPPY</em></h1>
      <p class="proof-sub">Clients say we feel like in-house Brilliant Directories experts—not a ticket queue. We delivered beyond expectations — and we'll do the same for you.</p>
      </div>

      <!-- Client Logos Grid (16 logos in 2 rows of 8) -->
      <!-- First Row (Scroll Left) -->
      <div class="bdgsownv1-marquee-container">
        <div class="bdgsownv1-marquee-track">
          <div class="bdgsownv1-marquee-content">
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/1.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/9.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/8.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/3.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/CCRG-LOGO-edit1.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/supplyseys.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/4.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/hotelsjuction.png" alt="Logo"></div>
          </div>
          <div class="bdgsownv1-marquee-content">
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/1.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/9.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/8.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/3.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/CCRG-LOGO-edit1.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/supplyseys.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/4.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/hotelsjuction.png" alt="Logo"></div>
          </div>
        </div>
      </div>

      <!-- Second Row (Scroll Right) -->
      <div class="bdgsownv1-marquee-container" style="margin-bottom: 0;">
        <div class="bdgsownv1-marquee-track reverse">
          <div class="bdgsownv1-marquee-content">
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/yep.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/7.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/isostylist.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/9_1.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/5.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/localbulls.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/insureblack.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/2.png" alt="Logo"></div>
          </div>
          <div class="bdgsownv1-marquee-content">
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/yep.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/7.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/isostylist.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/9_1.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/5.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/localbulls.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP2/insureblack.png" alt="Logo"></div>
            <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="https://ik.imagekit.io/h1pfsvzlsf/bdgrowthsuite/images/LogoP1/2.png" alt="Logo"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="rev-page-reviews">
      <span id="bdgs-reviews-total-count" hidden aria-hidden="true">176</span>
      <h2 id="verified-reviews" class="bdgs-sr-only">Verified Brilliant Directories client reviews</h2>
      <div class="bdgsownv2-reviews-grid" id="reviewsGrid">
        <!-- Reviews will be injected here -->
      </div>

      <div class="bdgsownv2-load-more-container">
        <button id="loadMoreBtn" class="bdgsownv2-load-more-btn" onclick="loadMoreReviews()">Load More Reviews</button>
      </div>
    </div>
  </section>
</main>

<!-- FOOTER (V3 — Locked) -->
