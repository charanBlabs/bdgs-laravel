<?php

namespace App\Services;

use App\Models\BdgsMedia;
use App\Models\BdgsMediaVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /** @var array<string, array{width: int, height: int}> */
    private array $variants = [
        'thumb' => ['width' => 300, 'height' => 300],
        'medium' => ['width' => 900, 'height' => 900],
        'large' => ['width' => 1600, 'height' => 1600],
    ];

    public function uploadAsWebp(UploadedFile $file, ?string $altText = null): BdgsMedia
    {
        if (! function_exists('imagecreatefromstring')) {
            return $this->upload($file, $altText);
        }

        $contents = file_get_contents($file->getRealPath());
        $image = $contents !== false ? @imagecreatefromstring($contents) : false;

        if ($image === false) {
            return $this->upload($file, $altText);
        }

        $disk = 'public';
        $directory = 'media/'.now()->format('Y/m');
        $filename = Str::uuid().'.webp';
        $fullDir = Storage::disk($disk)->path($directory);
        if (! is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }
        $fullPath = $fullDir.'/'.$filename;

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        imagewebp($image, $fullPath, 82);

        $w = imagesx($image);
        $h = imagesy($image);
        imagedestroy($image);

        $path = $directory.'/'.$filename;

        $media = BdgsMedia::query()->create([
            'uploaded_by' => Auth::id(),
            'filename' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'.webp',
            'disk' => $disk,
            'path' => $path,
            'mime_type' => 'image/webp',
            'size_bytes' => filesize($fullPath),
            'width' => $w,
            'height' => $h,
            'alt_text' => $altText,
        ]);

        $this->generateVariants($media, $fullPath);

        return $media->fresh('variants');
    }

    public function upload(UploadedFile $file, ?string $altText = null): BdgsMedia
    {
        $disk = 'public';
        $directory = 'media/'.now()->format('Y/m');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, $disk);

        [$width, $height] = $this->getImageDimensions($file->getRealPath());

        $media = BdgsMedia::query()->create([
            'uploaded_by' => Auth::id(),
            'filename' => $file->getClientOriginalName(),
            'disk' => $disk,
            'path' => $path,
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'size_bytes' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'alt_text' => $altText,
        ]);

        if ($this->isImage($media->mime_type) && $width && $height) {
            $this->generateVariants($media, $file->getRealPath());
        }

        return $media->fresh('variants');
    }

    /**
     * Convert an existing image (and its variants) to WebP and regenerate sizes.
     */
    public function reoptimizeAsWebp(BdgsMedia $media): BdgsMedia
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            return $media;
        }

        if (! $this->isImage($media->mime_type ?? '')) {
            return $media;
        }

        $disk = Storage::disk($media->disk);
        $sourcePath = $disk->path($media->path);
        if (! is_file($sourcePath)) {
            return $media;
        }

        $contents = file_get_contents($sourcePath);
        $image = $contents !== false ? @imagecreatefromstring($contents) : false;
        if ($image === false) {
            return $media;
        }

        $directory = dirname($media->path);
        $filename = Str::uuid().'.webp';
        $fullDir = $disk->path($directory);
        if (! is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }
        $fullPath = $fullDir.'/'.$filename;
        $newPath = $directory.'/'.$filename;

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        imagewebp($image, $fullPath, 82);

        $w = imagesx($image);
        $h = imagesy($image);
        imagedestroy($image);

        foreach ($media->variants as $variant) {
            $disk->delete($variant->path);
        }
        $media->variants()->delete();
        $disk->delete($media->path);

        $media->update([
            'filename' => pathinfo($media->filename, PATHINFO_FILENAME).'.webp',
            'path' => $newPath,
            'mime_type' => 'image/webp',
            'size_bytes' => filesize($fullPath) ?: 0,
            'width' => $w,
            'height' => $h,
        ]);

        $this->generateVariants($media->fresh(), $fullPath);

        return $media->fresh('variants');
    }

    public function regenerateVariants(BdgsMedia $media): void
    {
        if (! $this->isImage($media->mime_type ?? '')) {
            return;
        }

        $disk = Storage::disk($media->disk);
        $sourcePath = $disk->path($media->path);
        if (! is_file($sourcePath)) {
            return;
        }

        foreach ($media->variants as $variant) {
            $disk->delete($variant->path);
        }
        $media->variants()->delete();

        $this->generateVariants($media->fresh(), $sourcePath);
    }

    public function delete(BdgsMedia $media): void
    {
        Storage::disk($media->disk)->delete($media->path);

        foreach ($media->variants as $variant) {
            Storage::disk($media->disk)->delete($variant->path);
        }

        $media->variants()->delete();
        $media->delete();
    }

    private function generateVariants(BdgsMedia $media, string $sourcePath): void
    {
        foreach ($this->variants as $name => $size) {
            $variantPath = $this->resizeImage($sourcePath, $media->disk, $media->path, $name, $size['width'], $size['height']);

            if (! $variantPath) {
                continue;
            }

            [$w, $h] = $this->getImageDimensions(Storage::disk($media->disk)->path($variantPath));

            BdgsMediaVariant::query()->create([
                'media_id' => $media->id,
                'variant_name' => $name,
                'path' => $variantPath,
                'width' => $w ?? $size['width'],
                'height' => $h ?? $size['height'],
                'size_bytes' => Storage::disk($media->disk)->size($variantPath),
            ]);
        }
    }

    private function resizeImage(string $source, string $disk, string $originalPath, string $variant, int $maxW, int $maxH): ?string
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }

        $contents = file_get_contents($source);
        if ($contents === false) {
            return null;
        }

        $image = @imagecreatefromstring($contents);
        if ($image === false) {
            return null;
        }

        $origW = imagesx($image);
        $origH = imagesy($image);
        $ratio = min($maxW / $origW, $maxH / $origH, 1);
        $newW = (int) max(1, round($origW * $ratio));
        $newH = (int) max(1, round($origH * $ratio));

        $canvas = imagecreatetruecolor($newW, $newH);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $newW, $newH, $transparent);
        imagealphablending($canvas, true);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        $pathInfo = pathinfo($originalPath);
        $variantPath = $pathInfo['dirname'].'/'.$pathInfo['filename'].'-'.$variant.'.'.($pathInfo['extension'] ?? 'jpg');
        $fullPath = Storage::disk($disk)->path($variantPath);

        $saved = match (strtolower($pathInfo['extension'] ?? 'jpg')) {
            'png' => imagepng($canvas, $fullPath),
            'gif' => imagegif($canvas, $fullPath),
            'webp' => function_exists('imagewebp') ? imagewebp($canvas, $fullPath, 85) : imagejpeg($canvas, $fullPath, 85),
            default => imagejpeg($canvas, $fullPath, 85),
        };

        imagedestroy($image);
        imagedestroy($canvas);

        return $saved ? $variantPath : null;
    }

    private function getImageDimensions(string $path): array
    {
        $size = @getimagesize($path);

        return [$size[0] ?? null, $size[1] ?? null];
    }

    private function isImage(string $mime): bool
    {
        return str_starts_with($mime, 'image/') && ! str_contains($mime, 'svg');
    }
}
