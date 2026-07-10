@if (! empty($schemaGraph))
<script type="application/ld+json">
{!! json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}
</script>
@endif
