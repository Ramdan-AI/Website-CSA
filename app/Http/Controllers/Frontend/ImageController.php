<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    private const ALLOWED_WIDTHS = [320, 480, 640, 800, 960, 1280, 1600];

    private const ALLOWED_EXTS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    private const MIMES = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
    ];

    private const THUMB_EXTS = ['webp', 'png', 'gif'];

    public function show(string $width, string $path)
    {
        $w = (int) $width;
        if (! in_array($w, self::ALLOWED_WIDTHS, true)) {
            abort(404);
        }

        $path = ltrim($path, '/');
        if ($path === '' || str_contains($path, '..') || str_starts_with($path, 'cache/')) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, self::ALLOWED_EXTS, true)) {
            return redirect()->to(asset('storage/'.$path));
        }

        $base = realpath($disk->path(''));
        $full = realpath($disk->path($path));
        if ($base === false || $full === false || ! str_starts_with(strtolower($full), strtolower($base.DIRECTORY_SEPARATOR))) {
            abort(404);
        }

        $stem = 'cache/img/'.$w.'/'.sha1($full.$w.filemtime($full));
        $cachePath = $this->findCached($stem);

        if ($cachePath === null) {
            $thumb = $this->buildThumb($full, $w);
            if ($thumb === null) {
                return redirect()->to(asset('storage/'.$path));
            }

            [$bytes, $outExt] = $thumb;
            $cachePath = $stem.'.'.$outExt;
            $disk->put($cachePath, $bytes);
        }

        $outExt = pathinfo($cachePath, PATHINFO_EXTENSION);

        return response($disk->get($cachePath), 200, [
            'Content-Type' => self::MIMES[$outExt] ?? 'application/octet-stream',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'ETag' => '"'.sha1($path.$w).'"',
        ]);
    }

    private function findCached(string $stem): ?string
    {
        foreach (self::THUMB_EXTS as $ext) {
            if (Storage::disk('public')->exists($stem.'.'.$ext)) {
                return $stem.'.'.$ext;
            }
        }

        return null;
    }

    private function buildThumb(string $full, int $width): ?array
    {
        $size = @getimagesize($full);
        if ($size === false) {
            return null;
        }

        [$origW, $origH, $type] = $size;

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($full),
            IMAGETYPE_PNG => @imagecreatefrompng($full),
            IMAGETYPE_WEBP => @imagecreatefromwebp($full),
            IMAGETYPE_GIF => @imagecreatefromgif($full),
            default => false,
        };

        if ($src === false) {
            return null;
        }

        $height = (int) max(1, round($origH * ($width / max(1, $origW))));
        $dst = imagecreatetruecolor($width, $height);

        $hasAlpha = in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true);
        if ($hasAlpha) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $origW, $origH);

        ob_start();
        if ($type === IMAGETYPE_GIF) {
            imagegif($dst);
            $outExt = 'gif';
        } elseif ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagepng($dst);
            $outExt = 'png';
        } else {
            imagewebp($dst, null, 82);
            $outExt = 'webp';
        }
        $bytes = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        if ($bytes === false || $bytes === '') {
            return null;
        }

        return [$bytes, $outExt];
    }
}
