{{-- resources/views/pages/public/home.blade.php --}}
@extends('layouts.landing')

@section('content')

{{-- Shared data: semua properti dihitung sekali, dipakai setiap section --}}
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

    $roleOf = fn($m) => $m->position?->name;
    $ketua = $members->first(fn($m) => $roleOf($m) === 'Ketua');
    $wakil = $members->first(fn($m) => $roleOf($m) === 'Wakil Ketua');
    $pengurusInti = $members->filter(fn($m) => in_array($roleOf($m), ['Sekretaris', 'Bendahara'], true))->values();
    $koordinator = $members->filter(fn($m) => in_array($roleOf($m), ['Ketua Divisi', 'Wakil Ketua Divisi'], true))->values();
    $anggotaList = $members->filter(fn($m) => in_array($roleOf($m), ['Anggota', null], true))->values();
    $anggotaPreview = $anggotaList->take(8);
    $totalAnggota = $anggotaList->count();
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