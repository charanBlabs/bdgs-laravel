<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdgsMedia;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index');
    }

    public function store(Request $request, MediaService $mediaService): RedirectResponse|JsonResponse
    {
        $request->validate(['file' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf']]);

        $file = $request->file('file');
        $mime = (string) ($file->getMimeType() ?? '');
        $media = str_starts_with($mime, 'image/') && ! str_contains($mime, 'svg')
            ? $mediaService->uploadAsWebp($file, $request->input('alt_text'))
            : $mediaService->upload($file, $request->input('alt_text'));

        if ($request->expectsJson() || $request->boolean('editor_upload')) {
            return response()->json([
                'location' => $media->url(),
                'media_id' => $media->id,
            ]);
        }

        return back()->with('status', 'File uploaded.');
    }

    public function destroy(BdgsMedia $media, MediaService $mediaService): RedirectResponse
    {
        $mediaService->delete($media);

        return back()->with('status', 'Media deleted.');
    }
}
