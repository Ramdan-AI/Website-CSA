@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.events.index') }}" class="text-xs text-gray-400 hover:text-gray-600 font-semibold mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Event
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
            <p class="text-sm text-gray-400 mt-1 font-medium">
                <i class="fas fa-calendar mr-1 text-cyan-500"></i> {{ $event->event_date?->format('d M Y') }}
                @if($event->location)
                    <span class="mx-2">•</span>
                    <i class="fas fa-map-marker-alt mr-1 text-cyan-500"></i> {{ $event->location }}
                @endif
            </p>
        </div>

        @can('manage-events')
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.events.edit', $event) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    <i class="fas fa-pen mr-1"></i> Edit Event
                </a>
            </div>
        @endcan
    </div>

    @if($event->banner_url)
        <div class="w-full max-h-[300px] rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">Deskripsi Kegiatan</h3>
                <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">Pendaftaran & Presensi</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold mb-1">STATUS EVENT</p>
                        <span class="px-3 py-1 rounded-lg text-xs font-semibold 
                            {{ $event->status === 'published' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold mb-1">STATUS PRESENSI</p>
                        @if($event->attendance_enabled && !$event->attendance_closed_at)
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-600">Presensi Dibuka</span>
                        @elseif($event->attendance_closed_at)
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-500">Presensi Ditutup</span>
                        @else
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600">Belum Dibuka</span>
                        @endif
                    </div>

                    @if($event->registration_link)
                        <div class="pt-2">
                            <a href="{{ $event->registration_link }}" target="_blank" class="w-full py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-xl flex items-center justify-center gap-2 transition-colors">
                                <i class="fas fa-external-link-alt"></i>
                                Link Pendaftaran Eksternal
                            </a>
                        </div>
                    @else
                        @if($event->attendance_enabled && !$event->attendance_closed_at)
                            <div class="pt-2">
                                <form action="{{ route('admin.events.register', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl flex items-center justify-center gap-2 transition-colors">
                                        <i class="fas fa-user-plus"></i>
                                        Daftar / Check-In Kehadiran
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif

                    @can('manage-attendances')
                    <div class="pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.attendances.event', $event) }}" class="inline-flex items-center justify-center gap-2 w-full py-2 px-4 bg-gray-900 text-white text-xs font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                            <i class="fas fa-user-check mr-1"></i> Kelola Presensi & Export
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

</div>
@endsection