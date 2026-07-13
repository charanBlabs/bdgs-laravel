@php
  $isLive = old('live', ($post->status ?? 'draft') === 'published' ? 'yes' : 'no');
  $wysiwygCta = old('wysiwyg_cta', ($post->wysiwyg_cta ?? false) ? 'yes' : 'no');
  $selectedCategory = old('category_id', $post->categories?->first()?->id);
  $productType = old('product_type', $post->getMetaValue('product_type'));
@endphp

<div class="bdgs-sf">
  <fieldset class="bdgs-sf__toggle">
    <legend class="bdgs-sf__legend">Live</legend>
    <label class="bdgs-sf__radio"><input type="radio" name="live" value="yes" @checked($isLive === 'yes')> <span class="bdgs-sf__radio-dot"></span> Yes</label>
    <label class="bdgs-sf__radio"><input type="radio" name="live" value="no" @checked($isLive === 'no')> <span class="bdgs-sf__radio-dot"></span> No</label>
  </fieldset>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label bdgs-sf__label--required" for="sf-title">Title of Product / Post</label>
    <input type="text" id="sf-title" name="title" class="bdgs-sf__input" value="{{ old('title', $post->title) }}" required>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-slug">Slug</label>
    <input type="text" id="sf-slug" name="slug" class="bdgs-sf__input" value="{{ old('slug', $post->slug) }}" placeholder="Auto-generated from title">
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-short-title">Short Title for Results Page</label>
    <input type="text" id="sf-short-title" name="short_title" class="bdgs-sf__input" value="{{ old('short_title', $post->short_title) }}">
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-excerpt">Card Excerpt</label>
    <textarea id="sf-excerpt" name="excerpt" class="bdgs-sf__textarea" rows="3" placeholder="Short blurb shown on category cards">{{ old('excerpt', $post->excerpt) }}</textarea>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-demo">Demo video</label>
    <textarea id="sf-demo" name="demo_video_url" class="bdgs-sf__textarea" rows="3" placeholder="Enter the video embed code">{{ old('demo_video_url', $post->demo_video_url) }}</textarea>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="bdgs-pricing-type">Pricing type</label>
    <div class="bdgs-sf__select-wrap">
      <select name="pricing_type" id="bdgs-pricing-type" class="bdgs-sf__select">
        <option value="">Select an Option</option>
        @foreach (['fixed_price' => 'Fixed Price', 'starts_from' => 'Starts From', 'subscription' => 'Subscription', 'ask_for_quote' => 'Ask for Quote', 'free' => 'Free'] as $val => $label)
          <option value="{{ $val }}" @selected(old('pricing_type', $post->pricing_type) === $val)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label">Price</label>
    <div class="bdgs-sf__input-group">
      <span class="bdgs-sf__input-addon">$</span>
      <input type="number" step="0.01" min="0" name="price" class="bdgs-sf__input bdgs-sf__input--has-addon" value="{{ old('price', $post->price) }}">
    </div>
  </div>

  <div class="bdgs-sf__field bdgs-sf__pricing-extra" data-pricing="subscription">
    <label class="bdgs-sf__label">Annual Price</label>
    <div class="bdgs-sf__input-group">
      <span class="bdgs-sf__input-addon">$</span>
      <input type="number" step="0.01" min="0" name="annual_price" class="bdgs-sf__input bdgs-sf__input--has-addon" value="{{ old('annual_price', $post->annual_price) }}">
    </div>
  </div>

  <div class="bdgs-sf__field bdgs-sf__pricing-extra" data-pricing="starts_from">
    <label class="bdgs-sf__label">Commitment Price</label>
    <div class="bdgs-sf__input-group">
      <span class="bdgs-sf__input-addon">$</span>
      <input type="number" step="0.01" min="0" name="commitment_price" class="bdgs-sf__input bdgs-sf__input--has-addon" value="{{ old('commitment_price', $post->commitment_price) }}">
    </div>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-impl">Implementation Type</label>
    <div class="bdgs-sf__select-wrap">
      <select name="implementation_type" id="sf-impl" class="bdgs-sf__select">
        <option value="">Select Implementation Type</option>
        @foreach (['quick' => 'Quick', 'semi_custom' => 'Semi Custom', 'readytoimplement' => 'Ready to Implement', 'fully_custom' => 'Fully Custom'] as $val => $label)
          <option value="{{ $val }}" @selected(old('implementation_type', $post->implementation_type) === $val)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-delivery">Delivery time</label>
    <div class="bdgs-sf__select-wrap">
      <select name="delivery_time" id="sf-delivery" class="bdgs-sf__select">
        <option value="">Select Delivery time</option>
        @foreach (['3_days' => '3 Days', '1_week' => '1 Week', '2_weeks' => '2 Weeks'] as $val => $label)
          <option value="{{ $val }}" @selected(old('delivery_time', $post->delivery_time) === $val)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-warranty">Warranty</label>
    <div class="bdgs-sf__select-wrap">
      <select name="warranty" id="sf-warranty" class="bdgs-sf__select">
        <option value="">Select Warranty</option>
        @foreach (['30 Days' => '30 Days', '1 Year' => '1 Year', 'Until Active Subscription' => 'Until Active Subscription'] as $val => $label)
          <option value="{{ $val }}" @selected(old('warranty', $post->warranty) === $val)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-category">Select Category</label>
    <div class="bdgs-sf__select-wrap">
      <select name="category_id" id="sf-category" class="bdgs-sf__select">
        <option value="">Select Category</option>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <fieldset class="bdgs-sf__toggle">
    <legend class="bdgs-sf__legend">"What You See Is What You Get" CTA</legend>
    <label class="bdgs-sf__radio"><input type="radio" name="wysiwyg_cta" value="yes" @checked($wysiwygCta === 'yes')> <span class="bdgs-sf__radio-dot"></span> Yes</label>
    <label class="bdgs-sf__radio"><input type="radio" name="wysiwyg_cta" value="no" @checked($wysiwygCta === 'no')> <span class="bdgs-sf__radio-dot"></span> No</label>
  </fieldset>

  <div class="bdgs-sf__field">
    <label class="bdgs-sf__label" for="sf-product-type">Product Type</label>
    <div class="bdgs-sf__select-wrap">
      <select name="product_type" id="sf-product-type" class="bdgs-sf__select">
        <option value="">Select Product Type</option>
        @foreach (['quick_service' => 'Quick Service', 'tool' => 'Tool', 'service' => 'Service', 'flagship_service' => 'Flagship Service'] as $val => $label)
          <option value="{{ $val }}" @selected($productType === $val)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="bdgs-sf__field bdgs-sf__editor-wrap">
    <label class="bdgs-sf__label">Enter a Description</label>
    <textarea id="bdgs-content-editor" name="content" rows="16">{{ old('content', $post->content) }}</textarea>
  </div>
</div>
