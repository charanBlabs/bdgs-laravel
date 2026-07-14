<article class="bdgs-sol-card">
  <a href="{{ url('/solutions/'.$post->slug) }}" class="bdgs-sol-card__media">
    @if ($post->featuredMedia)
      @php($media = $post->featuredMedia)
      @php($imgDims = $media->dimensions('large') ?: $media->dimensions('medium'))
      @php($srcset = $media->srcset(['medium', 'large']))
      <img
        src="{{ $media->url('large') }}"
        srcset="{{ $srcset }}"
        sizes="(max-width: 640px) 100vw, (max-width: 960px) 50vw, (max-width: 1200px) 33vw, 25vw"
        alt="{{ $media->alt_text ?? $post->title }}"
        loading="lazy"
        decoding="async"
        width="{{ $imgDims[0] ?? 1200 }}"
        height="{{ $imgDims[1] ?? 800 }}"
      >
    @else
      <div class="bdgs-sol-card__placeholder" aria-hidden="true"></div>
    @endif
  </a>
  <div class="bdgs-sol-card__body">
    <span class="bdgs-sol-card__badge bdgs-sol-card__badge--{{ $post->pricing_type ?? 'fixed_price' }}">{{ $post->pricingLabel() }}</span>
    <h2 class="bdgs-sol-card__title"><a href="{{ url('/solutions/'.$post->slug) }}">{{ $post->short_title ?: $post->title }}</a></h2>
    <p class="bdgs-sol-card__excerpt">{{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($post->excerpt ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 120) }}</p>
    <div class="bdgs-sol-card__footer">
      <span class="bdgs-sol-card__price">{{ $post->formattedPrice() ?? 'Contact us' }}</span>
      <a href="{{ url('/solutions/'.$post->slug) }}" class="bdgs-sol-card__cta">Explore</a>
    </div>
  </div>
</article>
