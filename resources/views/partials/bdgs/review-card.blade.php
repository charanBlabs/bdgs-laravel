@php
  use App\Support\ReviewPresenter;
@endphp
<article class="rev-v3 rev-v3--shimmer-d">
  <div class="rev-v3__inner">
    <div class="bdgsownv2-review-top">
      <div>
        <div class="bdgsownv2-review-meta">
          By {{ ReviewPresenter::SUBMITTER_LABEL }} on
          <time datetime="{{ ReviewPresenter::isoDate($review) }}">{{ ReviewPresenter::displayDate($review) }}</time>
        </div>
        <h3 class="bdgsownv2-review-title">{{ ReviewPresenter::cleanTitle($review) }}</h3>
      </div>
    </div>
    <div class="bdgsownv2-review-details">
      @foreach ([
        'Overall Rating' => $review->overall_rating,
        'Service' => $review->service_rating,
        'Responsiveness' => $review->responsiveness_rating,
        'Expertise' => $review->expertise_rating,
        'Results' => $review->results_rating,
        'Communication' => $review->communication_rating,
      ] as $label => $rating)
        <div class="bdgsownv2-rating-item">
          <span class="bdgsownv2-rating-label">{{ $label }}</span>
          <div class="bdgsownv2-review-stars">
            @for ($i = 0; $i < 5; $i++)
              @if ($i < (int) $rating)
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
              @else
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
              @endif
            @endfor
          </div>
        </div>
      @endforeach
    </div>
    <div class="bdgsownv2-review-body">
      {{ ReviewPresenter::cleanBody($review) }}
    </div>
    <div class="bdgsownv2-card-footer">
      <span class="bdgsownv2-verified-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
        Verified Client Review
      </span>
      <a class="bdgsownv2-verify-link" href="{{ ReviewPresenter::verifyLink($review) }}" target="_blank" rel="noopener noreferrer">
        Verified on Marketplace
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      </a>
    </div>
  </div>
</article>
