<script type="application/json" id="bdgs-zoom-clinics-data">{!! json_encode($clinicJson ?? [], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<script>
window.bdgsZoomClinics = JSON.parse(document.getElementById("bdgs-zoom-clinics-data").textContent);
</script>
