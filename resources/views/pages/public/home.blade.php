@extends('layouts.landing')

@section('content')

@php
    $imageExists = [];
    $imageUrl = function ($photo, $width = null) use (&$imageExists) {
        $src = $photo->image;
        if (!$src || str_starts_with($src, 'http')) {
            return $src;
        }
        if (!array_key_exists($src, $imageExists)) {
            $imageExists[$src] = \Illuminate\Support\Facades\Storage::disk('public')->exists($src);
        }
        if (!$imageExists[$src]) {
            return $src;
        }
        if ($width !== null && preg_match('/\.(jpe?g|png|webp|gif)$/i', $src)) {
            return asset('img/' . $width . '/' . ltrim($src, '/'));
        }
        return asset('storage/' . $src);
    };
    $heroPhotos = collect($photos)->take(8)->values();
@endphp

@include('components.public.sections.hero')
@include('components.public.sections.tentang')
@include('components.public.sections.mengapa')
@include('components.public.sections.divisi')
@include('components.public.sections.kepengurusan')
@include('components.public.sections.cerita')
@include('components.public.sections.karya')
@include('components.public.sections.kegiatan')
@include('components.public.sections.daftar')

@endsection