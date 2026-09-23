<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

trait ResolvesStorageUrl
{
    /**
     * URL publik untuk berkas di disk "public".
     *
     * Origin mengikuti host request yang sedang dibuka, bukan APP_URL.
     * Ini penting saat aplikasi diakses lewat domain lokal seperti
     * web-csa.test sementara APP_URL menunjuk ke host lain.
     */
    protected function storageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Buang origin dari disk URL, lalu bangun ulang dari request.
        $full = Storage::disk('public')->url($path);
        $relative = ltrim(parse_url($full, PHP_URL_PATH) ?? '', '/');

        return url($relative);
    }
}
