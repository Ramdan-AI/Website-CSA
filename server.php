<?php

$publicPath = getcwd();
$path = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = $publicPath.$path;

$type = [
    'css' => 'text/css; charset=UTF-8',
    'js' => 'text/javascript; charset=UTF-8',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'webp' => 'image/webp',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
][strtolower(pathinfo($path, PATHINFO_EXTENSION))] ?? null;

if ($type === null || str_contains($path, '..') || ! is_file($file)) {
    return require __DIR__.'/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php';
}

$etag = '"'.dechex(filemtime($file)).'-'.dechex(filesize($file)).'"';

header('Content-Type: '.$type);
header('ETag: '.$etag);
header(str_starts_with($path, '/build/assets/')
    ? 'Cache-Control: public, max-age=31536000, immutable'
    : 'Cache-Control: public, max-age=3600');

if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? null) === $etag) {
    http_response_code(304);

    return true;
}

header('Content-Length: '.filesize($file));
readfile($file);

return true;
