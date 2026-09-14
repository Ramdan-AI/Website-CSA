@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Dokumen</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Dokumen & File</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Akses dan unduh dokumen penting UKM CSA.</p>
        </div>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="relative md:col-span-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen..."
                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
            <select name="sort" onchange="this.form.submit()" class="h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-gray-600">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Terbaru</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Terlama</option>
                <option value="title_asc" @selected(request('sort') === 'title_asc')>Nama A-Z</option>
                <option value="title_desc" @selected(request('sort') === 'title_desc')>Nama Z-A</option>
            </select>
        </div>
    </form>

    <div class="space-y-3">
        @forelse($documents as $document)
            <a href="{{ route('admin.documents.download', $document) }}" class="group block bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-gray-300 transition-all p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    @switch(true)
                        @case(Str::endsWith($document->file_path, '.pdf'))
                            <i class="fas fa-file-pdf text-xl"></i>
                            @break
                        @case(Str::endsWith($document->file_path, '.doc') || Str::endsWith($document->file_path, '.docx'))
                            <i class="fas fa-file-word text-xl"></i>
                            @break
                        @case(Str::endsWith($document->file_path, '.xls') || Str::endsWith($document->file_path, '.xlsx'))
                            <i class="fas fa-file-excel text-xl"></i>
                            @break
                        @case(Str::endsWith($document->file_path, '.ppt') || Str::endsWith($document->file_path, '.pptx'))
                            <i class="fas fa-file-powerpoint text-xl"></i>
                            @break
                        @case(Str::endsWith($document->file_path, '.jpg') || Str::endsWith($document->file_path, '.jpeg') || Str::endsWith($document->file_path, '.png') || Str::endsWith($document->file_path, '.webp'))
                            <i class="fas fa-file-image text-xl"></i>
                            @break
                        @default
                            <i class="fas fa-file text-xl"></i>
                    @endswitch
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $document->title }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-calendar mr-1"></i>{{ $document->created_at->format('d M Y') }}
                        @if($document->file_size)
                            · {{ number_format($document->file_size / 1024) }} KB
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-2 text-gray-400 group-hover:text-teal-600 transition-colors">
                    <i class="fas fa-download"></i>
                    <span class="text-xs font-semibold">Unduh</span>
                </div>
            </a>
        @empty
            <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm font-semibold text-gray-500">Tidak ada dokumen ditemukan</p>
                <p class="text-xs text-gray-400 mt-1">Coba ubah kriteria pencarian.</p>
            </div>
        @endforelse
    </div>

    {{ $documents->links() }}

</div>
@endsection

@php
use Illuminate\Support\Str;
@endphp