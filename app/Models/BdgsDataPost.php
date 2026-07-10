<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BdgsDataPost extends Model
{
    use SoftDeletes;

    protected $table = 'bdgs_data_posts';

    protected $fillable = [
        'uuid',
        'post_type_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'visibility',
        'featured_media_id',
        'published_at',
        'scheduled_at',
        'pinned',
        'sort_order',
        'view_count',
        'additional_fields',
        'pricing_type',
        'price',
        'annual_price',
        'commitment_price',
        'short_title',
        'implementation_type',
        'delivery_time',
        'warranty',
        'demo_video_url',
        'wysiwyg_cta',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'pinned' => 'boolean',
            'additional_fields' => 'array',
            'price' => 'decimal:2',
            'annual_price' => 'decimal:2',
            'commitment_price' => 'decimal:2',
            'wysiwyg_cta' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $post) {
            $post->uuid ??= (string) Str::uuid();
        });
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(BdgsDataType::class, 'post_type_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(BdgsMedia::class, 'featured_media_id');
    }

    public function meta(): HasMany
    {
        return $this->hasMany(BdgsDataMeta::class, 'post_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BdgsCategory::class, 'bdgs_rel_categories', 'post_id', 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BdgsTag::class, 'bdgs_rel_tags', 'post_id', 'tag_id');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(BdgsListSeo::class, 'seoable');
    }

    /** @param Builder<BdgsDataPost> $query */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getMetaValue(string $key, mixed $default = null): mixed
    {
        return $this->meta->firstWhere('key', $key)?->value ?? $default;
    }

    public function pricingLabel(): string
    {
        return match ($this->pricing_type) {
            'free' => 'Free',
            'fixed_price' => 'Fixed Price',
            'starts_from' => 'Starts From',
            'subscription' => 'Subscription',
            'ask_for_quote' => 'Ask for Quote',
            default => 'Fixed Price',
        };
    }

    public function formattedPrice(): ?string
    {
        if ($this->pricing_type === 'free') {
            return 'Free';
        }

        if ($this->pricing_type === 'ask_for_quote') {
            return 'Ask for Quote';
        }

        if ($this->pricing_type === 'subscription') {
            $monthly = $this->price !== null ? '$'.number_format((float) $this->price, 2) : null;
            if ($this->annual_price) {
                return ($monthly ? $monthly.' / Monthly' : '').($this->annual_price ? ' · $'.number_format((float) $this->annual_price, 2).' / Year' : '');
            }

            return $monthly;
        }

        if ($this->price === null) {
            return null;
        }

        return '$'.number_format((float) $this->price, 2);
    }

    public function implementationLabel(): ?string
    {
        return match ($this->implementation_type) {
            'quick' => 'Quick',
            'semi_custom' => 'Semi-Custom',
            'readytoimplement' => 'Ready-to-Implement',
            'fully_custom' => 'Fully Custom',
            default => $this->implementation_type ? ucwords(str_replace('_', ' ', $this->implementation_type)) : null,
        };
    }

    public function deliveryLabel(): ?string
    {
        return match ($this->delivery_time) {
            '3_days' => '< 3 Days',
            '1_week' => '< 1 Week',
            '2_weeks' => '1-2 Weeks',
            default => $this->delivery_time ? ucwords(str_replace('_', ' ', $this->delivery_time)) : null,
        };
    }

    public function demoVideoEmbedHtml(): ?string
    {
        $raw = trim((string) ($this->demo_video_url ?? ''));

        if ($raw === '') {
            return null;
        }

        if (str_contains($raw, '<iframe')) {
            return $raw;
        }

        if (preg_match('#loom\.com/(?:share|embed)/([a-zA-Z0-9]+)#', $raw, $m)) {
            return '<iframe src="https://www.loom.com/embed/'.$m[1].'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen loading="lazy"></iframe>';
        }

        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([a-zA-Z0-9_-]+)#', $raw, $m)) {
            return '<iframe src="https://www.youtube.com/embed/'.$m[1].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $raw, $m)) {
            return '<iframe src="https://player.vimeo.com/video/'.$m[1].'" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
        }

        if (filter_var($raw, FILTER_VALIDATE_URL)) {
            return '<iframe src="'.e($raw).'" frameborder="0" allowfullscreen loading="lazy"></iframe>';
        }

        return $raw;
    }
}
