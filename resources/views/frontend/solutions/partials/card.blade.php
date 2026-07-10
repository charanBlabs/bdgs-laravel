<article class="bdgs-sol-card">
  <a href="{{ url('/solutions/'.$post->slug) }}" class="bdgs-sol-card__media">
    @if ($post->featuredMedia)
      <img src="{{ $post->featuredMedia->url('medium') }}" alt="{{ $post->featuredMedia->alt_text ?? $post->title }}" loading="lazy" @if($post->featuredMedia->width) width="{{ $post->featuredMedia->width }}" height="{{ $post->featuredMedia->height }}" @endif>
    @else
      <div class="bdgs-sol-card__placeholder" aria-hidden="true"></div>
    @endif
    <span class="bdgs-sol-card__badge bdgs-sol-card__badge--{{ $post->pricing_type ?? 'fixed_price' }}">{{ $post->pricingLabel() }}</span>
  </a>
  <div class="bdgs-sol-card__body">
    <h2 class="bdgs-sol-card__title"><a href="{{ url('/solutions/'.$post->slug) }}">{{ $post->short_title ?: $post->title }}</a></h2>
    <p class="bdgs-sol-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? ''), 120) }}</p>
    <div class="bdgs-sol-card__footer">
      <span class="bdgs-sol-card__price">{{ $post->formattedPrice() ?? 'Contact us' }}</span>
      <a href="{{ url('/solutions/'.$post->slug) }}" class="bdgs-sol-card__cta">Explore</a>
    </div>
  </div>
</article>
