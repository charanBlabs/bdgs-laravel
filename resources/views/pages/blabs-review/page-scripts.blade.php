@verbatim
<script>
const API_ENDPOINT = 'https://bdgrowthsuite.com/api/widget/json/get/bdgs-blabs-reviews-api';
let currentIndex = 0;
let totalReviews = Infinity; // We will update this on first fetch
const REVIEWS_PER_PAGE = 30;
const grid = document.getElementById('reviewsGrid');
const loadMoreBtn = document.getElementById('loadMoreBtn');

function syncReviewCountDisplays(count) {
  if (window.BDGS_REVIEW_COUNT) BDGS_REVIEW_COUNT.sync(count);
}

function getStarsHtml(count) {
  let stars = '';
  for(let i=0; i<5; i++) {
    if(i < count) stars += '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
    else stars += '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
  }
  return stars;
}

function anonymizeReviewText(text) {
  if (!text) return '';
  var clean = text
    .replace(/arendal\.net/gi, 'our directory website')
    .replace(/ConnectCare\.ie/gi, 'our directory website')
    .replace(/uppercervicalcare\.com/gi, 'our directory website')
    .replace(/RealEstatePhotography\.com/gi, 'our directory website')
    .replace(/petrolheadlife\.com/gi, 'our directory website');
  clean = clean.replace(/\b([a-z0-9]+(-[a-z0-9]+)*\.)+(com|net|org|ie|co|io|edu|gov|co\.uk)\b/gi, 'our directory website');
  return clean;
}

function renderReviewCard(review) {
  const submitter = "Verified Brilliant Directories Site Owner";
  const mId = review.marketplace_review_id || review.review_id || '';
  const verifyLink = review.verify_link || `https://marketplace.brilliantdirectories.com/india/partner/business-labs/reviews/${mId}`;
  
  // Gracefully handle date parsing safely
  let dateObj = new Date(review.review_date);
  if (isNaN(dateObj.getTime())) { dateObj = new Date(); }
  
  const cleanText = anonymizeReviewText(review.review_text || '');
  const cleanTitle = anonymizeReviewText(review.title || 'Review');
  
  return `
    <article class="rev-v3 rev-v3--shimmer-d">
      <div class="rev-v3__inner">
      <div class="bdgsownv2-review-top">
        <div>
          <div class="bdgsownv2-review-meta">
            By ${submitter} on <time datetime="${dateObj.toISOString()}">${review.review_date || 'Recent'}</time>
          </div>
          <h3 class="bdgsownv2-review-title">${cleanTitle}</h3>
        </div>
      </div>
      <div class="bdgsownv2-review-details">
        <div class="bdgsownv2-rating-item">
          <span class="bdgsownv2-rating-label">Overall Rating</span>
          <div class="bdgsownv2-review-stars">${getStarsHtml(review.overall_rating || 5)}</div>
        </div>
        <div class="bdgsownv2-rating-item">
          <span class="bdgsownv2-rating-label">Service</span>
          <div class="bdgsownv2-review-stars">${getStarsHtml(review.service_rating || 5)}</div>
        </div>
        <div class="bdgsownv2-rating-item">
          <span class="bdgsownv2-rating-label">Responsiveness</span>
          <div class="bdgsownv2-review-stars">${getStarsHtml(review.responsiveness_rating || 5)}</div>
        </div>
        <div class="bdgsownv2-rating-item">
          <span class="bdgsownv2-rating-label">Expertise</span>
          <div class="bdgsownv2-review-stars">${getStarsHtml(review.expertise_rating || 5)}</div>
        </div>
        <div class="bdgsownv2-rating-item">
          <span class="bdgsownv2-rating-label">Results</span>
          <div class="bdgsownv2-review-stars">${getStarsHtml(review.results_rating || 5)}</div>
        </div>
        <div class="bdgsownv2-rating-item">
          <span class="bdgsownv2-rating-label">Communication</span>
          <div class="bdgsownv2-review-stars">${getStarsHtml(review.communication_rating || 5)}</div>
        </div>
      </div>
      <div class="bdgsownv2-review-body">
        ${cleanText}
      </div>
      <div class="bdgsownv2-card-footer">
        <span class="bdgsownv2-verified-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
          Verified Client Review
        </span>
        <a class="bdgsownv2-verify-link" href="${verifyLink}" target="_blank" rel="noopener noreferrer">
          Verified on Marketplace
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
      </div>
      </div>
    </article>
  `;
}

const API_KEY = '3d5ca826459ec3a3b47675f7ea0a0cd3';

// Optimized Async Fetch Function for the BD Endpoint
async function fetchReviews(offset, limit) {
  try {
    const url = `${API_ENDPOINT}?action=list&published_only=1&limit=${limit}&offset=${offset}&bd_api_key=${API_KEY}`;
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    if (!data.ok) {
      throw new Error(data.message || 'API returned an error');
    }
    // Update total count from API
    if (data.total !== undefined) {
      totalReviews = data.total;
      syncReviewCountDisplays(totalReviews);
    }
    return data.reviews || [];
  } catch(error) {
    console.error("Failed to fetch reviews:", error);
    return [];
  }
}

let schemaGraph = {
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "@id": "https://bdgrowthsuite.com/#organization",
  "name": "Business Labs",
  "url": "https://bdgrowthsuite.com",
  "aggregateRating": null,
  "review": []
};

// Inject and update JSON-LD Schema dynamically
function updateSchemaGraph(totalCount, newReviews) {
  if (!schemaGraph.aggregateRating) {
    schemaGraph.aggregateRating = {
      "@type": "AggregateRating",
      "ratingValue": "5.0",
      "reviewCount": totalCount.toString(),
      "bestRating": "5",
      "worstRating": "1"
    };
  }

  newReviews.forEach(r => {
    const mId = r.marketplace_review_id || r.review_id || '';
    const verifyLink = r.verify_link || `https://marketplace.brilliantdirectories.com/india/partner/business-labs/reviews/${mId}`;
    let dateObj = new Date(r.review_date);
    if (isNaN(dateObj.getTime())) { dateObj = new Date(); }
    
    schemaGraph.review.push({
      "@type": "Review",
      "url": verifyLink,
      "datePublished": dateObj.toISOString(),
      "name": anonymizeReviewText(r.title) || 'Review',
      "reviewBody": anonymizeReviewText(r.review_text) || '',
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": (r.overall_rating || 5).toString(),
        "bestRating": "5",
        "worstRating": "1"
      },
      "author": {
        "@type": "Person",
        "name": "Verified Brilliant Directories Site Owner"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Brilliant Directories Marketplace",
        "url": "https://marketplace.brilliantdirectories.com",
        "sameAs": "https://www.brilliantdirectories.com"
      }
    });
  });

  let script = document.getElementById('dynamic-aggregate-schema');
  if (!script) {
    script = document.createElement('script');
    script.id = 'dynamic-aggregate-schema';
    script.type = 'application/ld+json';
    document.head.appendChild(script);
  }
  script.text = JSON.stringify(schemaGraph);
}

let isLoading = false;

function renderSkeletons(count) {
  var html = '';
  for (var i = 0; i < count; i++) {
    html += '<article class="rev-v3 rev-v3--skeleton" aria-hidden="true">' +
      '<div class="rev-v3__inner rev-skeleton">' +
      '<div class="rev-skeleton__line rev-skeleton__line--short"></div>' +
      '<div class="rev-skeleton__line rev-skeleton__line--title"></div>' +
      '<div class="rev-skeleton__line rev-skeleton__line--pill"></div>' +
      '<div class="rev-skeleton__line rev-skeleton__line--pill"></div>' +
      '<div class="rev-skeleton__line rev-skeleton__line--body"></div>' +
      '<div class="rev-skeleton__line rev-skeleton__line--short"></div>' +
      '</div></article>';
  }
  return html;
}

var REVIEWS_SKELETON_COUNT = 12;

async function loadMoreReviews() {
  if (isLoading || currentIndex >= totalReviews) return;
  isLoading = true;

  var isFirstLoad = currentIndex === 0;
  var reservedHeight = 0;
  if (isFirstLoad) {
    grid.innerHTML = renderSkeletons(REVIEWS_SKELETON_COUNT);
    reservedHeight = grid.offsetHeight;
    if (reservedHeight > 0) grid.style.minHeight = reservedHeight + 'px';
  } else {
    const loadingHtml = '<div id="loadingIndicator" style="text-align:center; padding: 20px; color: var(--bdgs-text-muted); grid-column: 1 / -1;">Loading more reviews...</div>';
    grid.insertAdjacentHTML('beforeend', loadingHtml);
  }
  
  if (loadMoreBtn) loadMoreBtn.style.display = 'none';
  
  const reviews = await fetchReviews(currentIndex, REVIEWS_PER_PAGE);
  
  const indicator = document.getElementById('loadingIndicator');
  if (indicator) indicator.remove();
  
  if (reviews && reviews.length > 0) {
    const html = reviews.map(renderReviewCard).join('');
    if (isFirstLoad) {
      grid.innerHTML = html;
      requestAnimationFrame(function() {
        grid.style.minHeight = '';
      });
    } else {
      grid.insertAdjacentHTML('beforeend', html);
    }
    currentIndex += reviews.length;
    
    // Dynamically update schema graph with the new reviews
    if (totalReviews !== Infinity) {
      updateSchemaGraph(totalReviews, reviews);
    }
  } else if (isFirstLoad) {
    grid.style.minHeight = '';
  }
  
  isLoading = false;
}

// Initial Load & Infinite Scroll Observer
document.addEventListener('DOMContentLoaded', () => {
  loadMoreReviews();
  
  const loadContainer = document.querySelector('.bdgsownv2-load-more-container');
  if (loadContainer) {
    const observer = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) {
        loadMoreReviews();
      }
    }, { rootMargin: '400px' });
    
    observer.observe(loadContainer);
  }
});
</script>
@endverbatim
