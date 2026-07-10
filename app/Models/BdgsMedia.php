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
            $record = $this->variants()->where('variant_name', $variant)->first();
            if ($record) {
                return $disk->url($record->path);
            }
        }

        return $disk->url($this->path);
    }
}
