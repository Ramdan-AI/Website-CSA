@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Presensi</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Halaman Presensi</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola presensi kegiatan UKM CSA secara terstruktur dan terintegrasi.</p>
    </div>

    <div class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>
        
        <div class="relative p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Presensi Kegiatan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($events as $event)
                    <div class="p-5 rounded-[20px] border border-gray-200 bg-white/50 hover:shadow-lg transition-all">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900">{{ $event->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar mr-1"></i> {{ $event->event_date->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i> {{ $event->attendance_opened_at ? $event->attendance_opened_at->format('H:i') : 'Belum dibuka' }} - {{ $event->attendance_closed_at ? $event->attendance_closed_at->format('H:i') : 'Belum ditutup' }}
                                </p>
                            </div>
                            <div>
                                @if($event->attendance_enabled)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-600 text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-50 text-gray-500 text-xs font-semibold">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            @if($event->attendance_enabled && $event->attendance_code)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50">
                                    <span class="text-xs text-blue-600 font-mono font-semibold">Kode: {{ $event->attendance_code }}</span>
                                    <button onclick="copyToClipboard('{{ $event->attendance_code }}')" class="text-blue-500 hover:text-blue-600">
                                        <i class="fas fa-copy text-xs"></i>
                                    </button>
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>
                                    <i class="fas fa-user-check mr-1"></i> Total: {{ $event->attendances()->count() }}
                                </span>
                                <span class="text-green-600">
                                    <i class="fas fa-check mr-1"></i> {{ $event->attendances()->where('status', 'present')->count() }}
                                </span>
                                <span class="text-red-500">
                                    <i class="fas fa-xmark mr-1"></i> {{ $event->attendances()->where('status', 'absent')->count() }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @can('manage-attendances')
                                @if($event->attendance_enabled)
                                    <form action="{{ route('admin.attendance.toggle', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">
                                            <i class="fas fa-toggle-off mr-1"></i> Nonaktifkan
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.attendance.toggle', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-green-50 text-green-600 text-xs font-semibold rounded-lg hover:bg-green-100 transition-colors">
                                            <i class="fas fa-toggle-on mr-1"></i> Aktifkan
                                        </button>
                                    </form>
                                @endif

                                @if($event->attendance_enabled && !$event->attendance_opened_at)
                                    <form action="{{ route('admin.attendance.open', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-lg hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-play mr-1"></i> Buka Presensi
                                        </button>
                                    </form>
                                @endif

                                @if($event->attendance_enabled && $event->attendance_opened_at && !$event->attendance_closed_at)
                                    <form action="{{ route('admin.attendance.close', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-amber-50 text-amber-600 text-xs font-semibold rounded-lg hover:bg-amber-100 transition-colors">
                                            <i class="fas fa-stop mr-1"></i> Tutup Presensi
                                        </button>
                                    </form>
                                @endif

                                @if($event->attendance_code)
                                    <form action="{{ route('admin.attendance.qr', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-purple-50 text-purple-600 text-xs font-semibold rounded-lg hover:bg-purple-100 transition-colors">
                                            <i class="fas fa-qrcode mr-1"></i> Generate QR
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            <a href="{{ route('admin.attendances.index') }}?event={{ $event->id }}" class="px-3 py-1.5 bg-gray-50 text-gray-600 text-xs font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-list-ul mr-1"></i> Lihat Daftar
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($events->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada event ditemukan.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success message
        const toast = document.getElementById('globalToast');
        if (toast) {
            toast.innerHTML = '<div class=\"flex items-center\">Kode QR disalin!</div>';
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 2000);
        }
    });
}
</script>
@endpush
