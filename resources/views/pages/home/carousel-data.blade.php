@php
  $bdgsPageData = json_encode([
      'reviewCount' => (int) $reviewCount,
      'carouselReviews' => $carouselReviews,
  ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
@endphp
<script type="application/json" id="bdgs-page-data">{!! $bdgsPageData !!}</script>
<script>
(function () {
  var node = document.getElementById('bdgs-page-data');
  if (!node) return;
  try {
    var data = JSON.parse(node.textContent || '{}');
    window.bdgsReviewCount = data.reviewCount || 0;
    window.bdgsCarouselReviews = data.carouselReviews || [];
  } catch (e) {
    window.bdgsReviewCount = 0;
    window.bdgsCarouselReviews = [];
  }
})();
</script>
