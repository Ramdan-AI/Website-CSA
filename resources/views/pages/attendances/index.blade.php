@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Keanggotaan</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Presensi Kegiatan</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Rekap kehadiran seluruh kegiatan UKM CSA. Gunakan filter untuk mempersempit pencarian.</p>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="relative">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama kegiatan..."
                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 transition-all">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 transition-all">
            </div>
            <div>
                <select name="sort" onchange="this.form.submit()" class="w-full h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-gray-600">
                    <option value="date_desc" @selected(request('sort') === 'date_desc')>Tanggal terbaru</option>
                    <option value="date_asc" @selected(request('sort') === 'date_asc')>Tanggal terlama</option>
                    <option value="title_asc" @selected(request('sort') === 'title_asc')>Nama A-Z</option>
                    <option value="title_desc" @selected(request('sort') === 'title_desc')>Nama Z-A</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="h-11 px-5 bg-gray-900 text-white text-xs font-semibold rounded-xl hover:bg-gray-800 whitespace-nowrap">Cari</button>
                <a href="{{ route('admin.attendances.index') }}" class="h-11 px-4 bg-gray-100 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-200 flex items-center whitespace-nowrap">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Kegiatan</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Total</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Hadir</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Izin</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Sakit</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Alfa</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900">{{ $event->title }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-[220px]">{{ $event->location ?? 'Lokasi belum ditentukan' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $event->event_date?->format('d M Y') ?: '-' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-700">{{ $event->attendances_count }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-emerald-600">{{ $event->present_count }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-amber-600">{{ $event->permit_count }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $event->sick_count }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-red-600">{{ $event->absent_count }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $event->attendance_enabled ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $event->attendance_enabled ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.attendances.event', $event) }}" class="px-3 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 whitespace-nowrap">Kelola</a>
                                    <a href="{{ route('admin.attendance.export', $event) }}" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-50" title="Export CSV">
                                        <i class="fas fa-file-export text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <i class="fas fa-calendar-times text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm font-semibold text-gray-500">Tidak ada kegiatan ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Coba ubah kriteria pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($events->hasPages())
            <div class="p-5 border-t border-gray-100">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection