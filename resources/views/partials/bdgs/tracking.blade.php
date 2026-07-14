@php
    $trackingEnabled = (bool) config('services.tracking.enabled');
    $ga4Id = trim((string) config('services.tracking.ga4_id'));
    $metaPixelId = trim((string) config('services.tracking.meta_pixel_id'));
    $linkedinPartnerId = trim((string) config('services.tracking.linkedin_partner_id'));
    $linkedinZoomConversionId = trim((string) config('services.tracking.linkedin_zoom_clinic_conversion_id'));
    $hasAnyPixel = $ga4Id !== '' || $metaPixelId !== '' || $linkedinPartnerId !== '';
@endphp
@if ($trackingEnabled && $hasAnyPixel)
<div id="bdgs-tracking-boot"
     hidden
     data-ga4="{{ $ga4Id }}"
     data-meta="{{ $metaPixelId }}"
     data-linkedin-partner="{{ $linkedinPartnerId }}"
     data-linkedin-zoom-conversion="{{ $linkedinZoomConversionId }}"></div>
@if ($ga4Id !== '')
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
@endif
@if ($metaPixelId !== '')
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ urlencode($metaPixelId) }}&ev=PageView&noscript=1" alt=""></noscript>
@endif
@if ($linkedinPartnerId !== '')
<noscript><img height="1" width="1" style="display:none" alt="" src="https://px.ads.linkedin.com/collect/?pid={{ urlencode($linkedinPartnerId) }}&fmt=gif"></noscript>
@endif
@endif
<script src="/js/bdgs-tracking.js" defer></script>
