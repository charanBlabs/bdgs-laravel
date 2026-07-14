<main>
  <section class="bdgsownv2-section bdgsownv2-proof-section">
    <div class="container">
      <div class="bdgsownv2-proof-head">
      <h1 id="reviews-page-title">We Made These Brilliant Directories Sites <span class="title-strikethrough">Happy</span> <em>VERY HAPPY</em></h1>
      <p class="proof-sub">Clients say we feel like in-house Brilliant Directories experts—not a ticket queue. We delivered beyond expectations — and we'll do the same for you.</p>
      </div>

      @include('partials.bdgs.client-logo-marquee', ['secondRowFlush' => true])
    </div>

    <div class="rev-page-reviews">
      <span id="bdgs-reviews-total-count" hidden aria-hidden="true">{{ $reviewCount }}</span>

      <div class="rev-verified-head">
        <div class="rev-verified-rule" aria-hidden="true">
          <span class="rev-verified-rule__line"></span>
          <span class="rev-verified-rule__medal">🏅</span>
          <span class="rev-verified-rule__line"></span>
        </div>
        <h2 id="verified-reviews">Are these Brilliant Directories client reviews verified?</h2>
        <p class="rev-verified-lead">Yes — every review below is imported from the official Brilliant Directories Marketplace and linked to a verified site owner. Business Labs by BD Growth Suite is a Gold Certified Brilliant Directories Partner with {{ $reviewCount }} five-star reviews you can cross-check on the marketplace.</p>
      </div>

      <div class="bdgsownv2-reviews-grid" id="reviewsGrid" data-total="{{ $reviews->count() < $reviewsPerPage ? $reviews->count() : $reviewCount }}" data-per-page="{{ $reviewsPerPage }}">
        @forelse ($reviews as $review)
          @include('partials.bdgs.review-card', ['review' => $review])
        @empty
          <p class="rev-empty-state" style="grid-column: 1 / -1; text-align: center; color: var(--bdgs-text-muted);">No verified client reviews are published yet. Please check back soon.</p>
        @endforelse
      </div>

      @if ($hasMoreReviews)
      <div class="bdgsownv2-load-more-container">
        <button type="button" id="loadMoreBtn" class="bdgsownv2-load-more-btn">Load More Reviews</button>
      </div>
      @endif
    </div>
  </section>
</main>

<!-- FOOTER (V3 — Locked) -->
