<script>
(function () {
  const API_ENDPOINT = '/api/reviews/list';
  const grid = document.getElementById('reviewsGrid');
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  if (!grid) return;

  let currentIndex = grid.querySelectorAll('.rev-v3:not(.rev-v3--skeleton)').length;
  let totalReviews = parseInt(grid.getAttribute('data-total') || '0', 10);
  const REVIEWS_PER_PAGE = parseInt(grid.getAttribute('data-per-page') || '30', 10);
  let isLoading = false;

  function getStarsHtml(count) {
    let stars = '';
    for (let i = 0; i < 5; i++) {
      if (i < count) {
        stars += '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
      } else {
        stars += '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
      }
    }
    return stars;
  }

  function escapeHtml(str) {
    if (!str) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
  }

  function anonymizeReviewText(text) {
    if (!text) return '';
    let clean = text
      .replace(/arendal\.net/gi, 'our directory website')
      .replace(/ConnectCare\.ie/gi, 'our directory website')
      .replace(/uppercervicalcare\.com/gi, 'our directory website')
      .replace(/RealEstatePhotography\.com/gi, 'our directory website')
      .replace(/petrolheadlife\.com/gi, 'our directory website');
    clean = clean.replace(/\b([a-z0-9]+(-[a-z0-9]+)*\.)+(com|net|org|ie|co|io|edu|gov|co\.uk)\b/gi, 'our directory website');
    return escapeHtml(clean);
  }

  function renderReviewCard(review) {
    const submitter = 'Verified Brilliant Directories Site Owner';
    const mId = review.marketplace_review_id || review.review_id || '';
    var rawLink = review.verify_link || ('https://marketplace.brilliantdirectories.com/india/partner/business-labs/reviews/' + mId);
    const verifyLink = /^https?:\/\//i.test(rawLink) ? escapeHtml(rawLink) : 'https://marketplace.brilliantdirectories.com/india/partner/business-labs';
    let dateObj = new Date(review.review_date);
    if (isNaN(dateObj.getTime())) dateObj = new Date();
    const cleanText = anonymizeReviewText(review.review_text || '');
    const cleanTitle = anonymizeReviewText(review.title || 'Review');
    const displayDate = review.review_date || 'Recent';

    return '<article class="rev-v3 rev-v3--shimmer-d"><div class="rev-v3__inner">' +
      '<div class="bdgsownv2-review-top"><div>' +
      '<div class="bdgsownv2-review-meta">By ' + submitter + ' on <time datetime="' + dateObj.toISOString() + '">' + displayDate + '</time></div>' +
      '<h3 class="bdgsownv2-review-title">' + cleanTitle + '</h3></div></div>' +
      '<div class="bdgsownv2-review-details">' +
      ['Overall Rating', 'Service', 'Responsiveness', 'Expertise', 'Results', 'Communication'].map(function (label, idx) {
        const keys = ['overall_rating', 'service_rating', 'responsiveness_rating', 'expertise_rating', 'results_rating', 'communication_rating'];
        return '<div class="bdgsownv2-rating-item"><span class="bdgsownv2-rating-label">' + label + '</span><div class="bdgsownv2-review-stars">' + getStarsHtml(review[keys[idx]] || 5) + '</div></div>';
      }).join('') +
      '</div><div class="bdgsownv2-review-body">' + cleanText + '</div>' +
      '<div class="bdgsownv2-card-footer"><span class="bdgsownv2-verified-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>Verified Client Review</span>' +
      '<a class="bdgsownv2-verify-link" href="' + verifyLink + '" target="_blank" rel="noopener noreferrer">Verified on Marketplace <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a></div></div></article>';
  }

  async function fetchReviews(offset, limit) {
    const url = API_ENDPOINT + '?published_only=1&limit=' + limit + '&offset=' + offset;
    const response = await fetch(url);
    if (!response.ok) throw new Error('Network response was not ok');
    const data = await response.json();
    if (!data.ok) throw new Error(data.message || 'API returned an error');
    if (data.total !== undefined) {
      totalReviews = data.total;
    }
    return data.reviews || [];
  }

  async function loadMoreReviews() {
    if (isLoading || currentIndex >= totalReviews) return;
    isLoading = true;
    if (loadMoreBtn) loadMoreBtn.style.display = 'none';

    const loadingHtml = '<div id="loadingIndicator" style="text-align:center; padding: 20px; color: var(--bdgs-text-muted); grid-column: 1 / -1;">Loading more reviews...</div>';
    grid.insertAdjacentHTML('beforeend', loadingHtml);

    try {
      const reviews = await fetchReviews(currentIndex, REVIEWS_PER_PAGE);
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.remove();

      if (reviews.length > 0) {
        grid.insertAdjacentHTML('beforeend', reviews.map(renderReviewCard).join(''));
        currentIndex += reviews.length;
      }
    } catch (error) {
      console.error('Failed to fetch reviews:', error);
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.remove();
    }

    isLoading = false;
    if (loadMoreBtn) {
      loadMoreBtn.style.display = currentIndex >= totalReviews ? 'none' : '';
    }
  }

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', loadMoreReviews);
  }

  const loadContainer = document.querySelector('.bdgsownv2-load-more-container');
  if (loadContainer) {
    const observer = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) loadMoreReviews();
    }, { rootMargin: '400px' });
    observer.observe(loadContainer);
  }
})();
</script>
