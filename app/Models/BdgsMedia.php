<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BdgsMedia extends Model
{
    protected $table = 'bdgs_media';

    protected $fillable = [
        'uuid',
        'uploaded_by',
        'filename',
        'disk',
        'path',
        'mime_type',
        'size_bytes',
        'width',
        'height',
        'alt_text',
        'title',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $media) {
            $media->uuid ??= (string) Str::uuid();
        });
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(BdgsMediaVariant::class, 'media_id');
    }

    public function url(?string $variant = null): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($this->disk);

        if ($variant) {
            $record = $this->variantRecord($variant);
            if ($record) {
                return $disk->url($record->path);
            }
        }

        return $disk->url($this->path);
    }

    public function variantRecord(?string $variant): ?BdgsMediaVariant
    {
        if (! $variant) {
            return null;
        }

        if ($this->relationLoaded('variants')) {
            return $this->variants->firstWhere('variant_name', $variant);
        }

        return $this->variants()->where('variant_name', $variant)->first();
    }

    /**
     * Intrinsic width/height for a variant (falls back to original).
     *
     * @return array{0: int|null, 1: int|null}
     */
    public function dimensions(?string $variant = null): array
    {
        if ($variant) {
            $record = $this->variantRecord($variant);
            if ($record) {
                return [$record->width, $record->height];
            }
        }

        return [$this->width, $this->height];
    }

    /**
     * Responsive srcset using available variants.
     *
     * @param  array<int, string>  $variants
     */
    public function srcset(array $variants = ['thumb', 'medium', 'large']): string
    {
        $parts = [];

        foreach ($variants as $name) {
            $record = $this->variantRecord($name);
            if (! $record || ! $record->width) {
                continue;
            }

            $parts[] = $this->url($name).' '.$record->width.'w';
        }

        if ($parts === [] && $this->width) {
            $parts[] = $this->url().' '.$this->width.'w';
        }

        return implode(', ', $parts);
    }
}
