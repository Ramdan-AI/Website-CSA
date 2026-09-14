@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Kehadiran</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Riwayat Kehadiran Saya</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Lacak kehadiran Anda di seluruh kegiatan UKM CSA.</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                <p class="text-xs text-emerald-700 font-semibold uppercase">Hadir</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['present'] }}</p>
            </div>
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-xs text-amber-700 font-semibold uppercase">Izin</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['permit'] }}</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-xs text-blue-700 font-semibold uppercase">Sakit</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['sick'] }}</p>
            </div>
            <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                <p class="text-xs text-red-700 font-semibold uppercase">Alfa</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['absent'] }}</p>
            </div>
        </div>

        <form method="GET" class="mb-6 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama kegiatan..."
                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
            <select name="status" onchange="this.form.submit()" class="h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-gray-600">
                <option value="">Semua Status</option>
                <option value="present" @selected(request('status') === 'present')>Hadir</option>
                <option value="permit" @selected(request('status') === 'permit')>Izin</option>
                <option value="sick" @selected(request('status') === 'sick')>Sakit</option>
                <option value="absent" @selected(request('status') === 'absent')>Alfa</option>
            </select>
            <select name="sort" onchange="this.form.submit()" class="h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-gray-600">
                <option value="date_desc" @selected(request('sort', 'date_desc') === 'date_desc')>Tanggal terbaru</option>
                <option value="date_asc" @selected(request('sort') === 'date_asc')>Tanggal terlama</option>
                <option value="title_asc" @selected(request('sort') === 'title_asc')>Nama A-Z</option>
                <option value="title_desc" @selected(request('sort') === 'title_desc')>Nama Z-A</option>
            </select>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Kegiatan</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Waktu Check-in</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Dicatat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.events.show', $attendance->event) }}" class="hover:text-cyan-600 transition-colors">
                                    <p class="text-sm font-bold text-gray-900">{{ $attendance->event->title }}</p>
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[280px]">{{ $attendance->event->location ?? 'Lokasi tidak ditentukan' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $attendance->event->event_date?->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @switch($attendance->status)
                                    @case('present')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Hadir
                                        </span>
                                        @break
                                    @case('permit')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Izin
                                        </span>
                                        @break
                                    @case('sick')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Sakit
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-500 text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Alfa
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                {{ $attendance->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-calendar-times text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm font-semibold text-gray-500">Tidak ada riwayat kehadiran</p>
                                <p class="text-xs text-gray-400 mt-1">Anda belum memiliki riwayat kehadiran pada kegiatan manapun.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $attendances->links() }}
    </div>

</div>
@endsection