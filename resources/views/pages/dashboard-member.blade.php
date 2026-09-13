@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Dashboard Anggota</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Halo, {{ explode(' ', $user->name)[0] }}!</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Selamat datang kembali di panel anggota UKM CSA.</p>
    </div>

    {{-- WA Group Banner --}}
    @php
        $waLink = \App\Models\Setting::first()?->whatsapp_group_link;
    @endphp
    @if ($waLink)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 leading-tight">Grup WhatsApp Resmi Divisi {{ $member?->division?->name ?? 'CSA' }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Gabung grup untuk koordinasi study club, info tugas, dan kegiatan rutin.</p>
                </div>
            </div>
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl transition-all shrink-0">
                <i class="fab fa-whatsapp text-sm"></i>
                <span>Buka Grup WA</span>
            </a>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NIM</span>
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-id-card text-sm"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900 tracking-tight">{{ $member?->nim ?? '-' }}</p>
            <p class="text-xs text-gray-400 mt-1">Nomor Induk Mahasiswa</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Divisi</span>
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                    <i class="fas fa-sitemap text-sm"></i>
                </div>
            </div>
            <p class="text-lg font-bold text-gray-900 tracking-tight">{{ $member?->division?->name ?? 'Belum ada' }}</p>
            <p class="text-xs text-gray-400 mt-1">Cabang pengembangan minat</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kehadiran</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-user-check text-sm"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900 tracking-tight">{{ $attendanceRate }}%</p>
            <p class="text-xs text-gray-400 mt-1">{{ $attendanceStats['present'] }} dari {{ $attendanceStats['total'] }} kegiatan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</span>
                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600">
                    <i class="fas fa-id-badge text-sm"></i>
                </div>
            </div>
            <p class="text-lg font-bold text-gray-900 tracking-tight">{{ $member && $member->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            <p class="text-xs text-gray-400 mt-1">Keanggotaan</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Edit Profil</p>
                        <p class="text-xs text-gray-400">Perbarui data diri & foto</p>
                    </div>
                </a>
                <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-cyan-600">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Lihat Kegiatan</p>
                        <p class="text-xs text-gray-400">Daftar study club & acara</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Upcoming Events with Self-Attendance --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900">Kegiatan Mendatang</h3>
                <a href="{{ route('admin.events.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-3">
                @forelse($upcomingEvents as $event)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-blue-600">{{ $event->event_date->format('d M Y') }}</span>
                                @if($event->attendance_enabled && !$event->attendance_closed_at)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Presensi Dibuka</span>
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $event->title }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location ?? 'Online' }}</p>
                        </div>

                        <div>
                            @if($event->attendance_enabled && !$event->attendance_closed_at)
                                <form action="{{ route('admin.attendance.self', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl transition-colors">
                                        <i class="fas fa-check-circle mr-1"></i> Check-In
                                    </button>
                                </form>
                            @elseif($event->attendance_closed_at)
                                <span class="px-3 py-1.5 rounded-xl bg-gray-100 text-gray-500 text-xs font-semibold">Selesai</span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-xs font-semibold">Segera</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm">Belum ada kegiatan mendatang.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Attendances --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <h3 class="text-base font-bold text-gray-900 mb-4">Riwayat Kehadiran Anda</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Kegiatan</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Check-in</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendances as $attendance)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $attendance->event->title }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $attendance->event->event_date->format('d M Y') }}</td>
                            <td class="py-3 px-4">
                                @if($attendance->status === 'present')
                                    <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-semibold">Hadir</span>
                                @else
                                    <span class="px-2 py-1 rounded-lg bg-red-50 text-red-500 text-xs font-semibold">Absen</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400">Belum ada riwayat kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
