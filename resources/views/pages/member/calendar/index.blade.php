@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-cyan-400 uppercase tracking-widest">Kegiatan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Kegiatan & Presensi</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola presensi Anda untuk Study Club, Mubes, dan kegiatan lainnya.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button id="viewCalendarBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white text-xs font-semibold rounded-xl hover:bg-cyan-700 transition-colors">
                <i class="fas fa-calendar mr-1"></i> Tampil Kalender
            </button>
            <button id="viewListBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-xs font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                <i class="fas fa-list mr-1"></i> Tampil Daftar
            </button>
        </div>
    </div>

    {{-- Calendar View --}}
    <div id="calendarView" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden hidden">
        <div id="calendar" class="p-4"></div>
    </div>

    {{-- List View --}}
    <div id="listView" class="space-y-4">
        @forelse($events as $event)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center shrink-0">
                                <i class="fas fa-calendar-alt text-2xl text-cyan-600"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-semibold text-cyan-600">
                                        <i class="fas fa-calendar-day mr-1"></i> {{ $event->event_date?->format('d M Y') }}
                                    </span>
                                    @php
                                        $statusStyle = match($event->status) {
                                            'published' => 'bg-emerald-50 text-emerald-600',
                                            'draft' => 'bg-amber-50 text-amber-600',
                                            'closed' => 'bg-gray-100 text-gray-500',
                                            default => 'bg-gray-50 text-gray-400'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $statusStyle }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                                <h2 class="mt-2 text-lg font-bold text-gray-900">{{ $event->title }}</h2>
                                @if($event->location)
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-map-marker-alt text-cyan-500 mr-1"></i> {{ $event->location }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <div class="flex flex-col gap-2">
                                @php
                                    $attendance = \App\Models\Attendance::where('event_id', $event->id)
                                        ->where('member_id', auth()->user()->member->id ?? 0)
                                        ->first();
                                    $hasAttended = $attendance !== null;
                                    $attendanceStatus = $attendance ? $attendance->status : null;
                                @endphp

                                {{-- Attendance Status Display --}}
                                <div class="w-full sm:w-auto">
                                    @if($hasAttended)
                                        @php
                                            $attStyle = match($attendanceStatus) {
                                                'present' => 'bg-emerald-50 text-emerald-600',
                                                'permit' => 'bg-amber-50 text-amber-600',
                                                'sick' => 'bg-blue-50 text-blue-600',
                                                'absent' => 'bg-red-50 text-red-600',
                                                default => 'bg-gray-50 text-gray-500'
                                            };
                                            $attLabel = match($attendanceStatus) {
                                                'present' => 'HADIR',
                                                'permit' => 'IZIN',
                                                'sick' => 'SAKIT',
                                                'absent' => 'ALFA',
                                                default => 'TERCATAT'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center justify-center px-3 py-2 rounded-xl text-xs font-bold {{ $attStyle }}">
                                            <i class="fas fa-check-circle mr-1"></i> {{ $attLabel }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-3 py-2 rounded-xl text-xs font-semibold bg-gray-100 text-gray-500">
                                            <i class="fas fa-clock mr-1"></i> Belum Absen
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-100">
                        <p class="text-xs text-gray-400 font-semibold mb-3">Aksi Presensi</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            {{-- Presensi Mandiri (Hadir) --}}
                            <form action="{{ route('admin.member.attendance.self', $event) }}" method="POST" class="group">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 rounded-xl font-semibold text-xs transition-all
                                        {{ !$event->attendance_enabled || $event->attendance_closed_at || $event->event_date->isPast() || $hasAttended
                                            ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                            : 'bg-emerald-600 text-white hover:bg-emerald-700 active:scale-[0.98]' }}"
                                    {{ !$event->attendance_enabled || $event->attendance_closed_at || $event->event_date->isPast() || $hasAttended ? 'disabled' : '' }}>
                                    <i class="fas fa-user-check mr-2"></i>
                                    @if($hasAttended && $attendanceStatus === 'present')
                                        Sudah Hadir
                                    @elseif($hasAttended)
                                        Hadir (Ubah)
                                    @else
                                        Presensi Mandiri
                                    @endif
                                </button>
                            </form>

                            {{-- Scan QR Code --}}
                            <button type="button"
                                onclick="openQRScanner({{ $event->id }})"
                                class="w-full py-3 rounded-xl font-semibold text-xs transition-all
                                    {{ !$event->attendance_enabled || $event->attendance_closed_at || $event->event_date->isPast() || $hasAttended
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                        : 'bg-blue-600 text-white hover:bg-blue-700 active:scale-[0.98]' }}"
                                {{ !$event->attendance_enabled || $event->attendance_closed_at || $event->event_date->isPast() || $hasAttended ? 'disabled' : '' }}>
                                <i class="fas fa-qrcode mr-2"></i>
                                Scan QR Code
                            </button>

                            {{-- Sakit --}}
                            <form action="{{ route('admin.member.attendance.sick', $event) }}" method="POST" class="group">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 rounded-xl font-semibold text-xs transition-all
                                        {{ !$event->attendance_enabled || $event->attendance_closed_at || $hasAttended
                                            ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                            : 'bg-blue-500 text-white hover:bg-blue-600 active:scale-[0.98]' }}"
                                    {{ !$event->attendance_enabled || $event->attendance_closed_at || $hasAttended ? 'disabled' : '' }}>
                                    <i class="fas fa-thermometer mr-2"></i>
                                    @if($hasAttended && $attendanceStatus === 'sick')
                                        Sudah Sakit
                                    @elseif($hasAttended)
                                        Sakit (Ubah)
                                    @else
                                        Sakit
                                    @endif
                                </button>
                            </form>

                            {{-- Izin --}}
                            <form action="{{ route('admin.member.attendance.permit', $event) }}" method="POST" class="group">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 rounded-xl font-semibold text-xs transition-all
                                        {{ !$event->attendance_enabled || $event->attendance_closed_at || $hasAttended
                                            ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                            : 'bg-amber-500 text-white hover:bg-amber-600 active:scale-[0.98]' }}"
                                    {{ !$event->attendance_enabled || $event->attendance_closed_at || $hasAttended ? 'disabled' : '' }}>
                                    <i class="fas fa-file-signature mr-2"></i>
                                    @if($hasAttended && $attendanceStatus === 'permit')
                                        Sudah Izin
                                    @elseif($hasAttended)
                                        Izin (Ubah)
                                    @else
                                        Izin
                                    @endif
                                </button>
                            </form>
                        </div>

                        {{-- Status Presensi Info --}}
                        <div class="mt-4 p-3 rounded-xl bg-gray-50">
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                                <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700"><i class="fas fa-check mr-1"></i> Hadir: Otomatis via Mandiri/QR</span>
                                <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-700"><i class="fas fa-thermometer mr-1"></i> Sakit</span>
                                <span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-700"><i class="fas fa-file-signature mr-1"></i> Izin</span>
                                <span class="px-2 py-1 rounded-lg bg-red-100 text-red-700"><i class="fas fa-times mr-1"></i> Alfa: Otomatis setelah sesi ditutup</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                    <p class="text-sm font-semibold text-gray-500">Tidak ada kegiatan</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- QR Scanner Modal --}}
    <div id="qrScannerModal" class="hidden fixed inset-0 z-[9999] bg-black/50 flex items-center justify-center p-4" onclick="closeQRScanner(event)">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full" onclick="event.stopPropagation()">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Scan QR Code</h3>
                <button onclick="closeQRScanner()" class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-100 text-gray-500 hover:bg-gray-200">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <div class="p-4" id="qrReader"></div>
            <div class="px-4 pb-4">
                <p class="text-xs text-gray-400 text-center mb-3">Arahkan kamera ke QR Code yang ditampilkan panitia</p>
                <form action="{{ route('admin.member.attendance.scan', $event) }}" method="POST" id="qrManualForm" class="hidden">
                    @csrf
                    <input type="hidden" name="event" id="qrEventId">
                    <input type="hidden" name="code" id="qrCodeInput">
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/dist/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // View toggle
        const calendarView = document.getElementById('calendarView');
        const listView = document.getElementById('listView');
        const viewCalendarBtn = document.getElementById('viewCalendarBtn');
        const viewListBtn = document.getElementById('viewListBtn');

        if (viewCalendarBtn && viewListBtn) {
            viewCalendarBtn.addEventListener('click', () => {
                calendarView.classList.remove('hidden');
                listView.classList.add('hidden');
                viewCalendarBtn.classList.add('bg-cyan-600', 'text-white');
                viewCalendarBtn.classList.remove('bg-gray-900', 'text-white');
                viewListBtn.classList.add('bg-gray-900', 'text-white');
                viewListBtn.classList.remove('bg-cyan-600', 'text-white');
                if (window.calendar) window.calendar.render();
            });

            viewListBtn.addEventListener('click', () => {
                calendarView.classList.add('hidden');
                listView.classList.remove('hidden');
                viewListBtn.classList.add('bg-gray-900', 'text-white');
                viewListBtn.classList.remove('bg-cyan-600', 'text-white');
                viewCalendarBtn.classList.add('bg-cyan-600', 'text-white');
                viewCalendarBtn.classList.remove('bg-gray-900', 'text-white');
            });
        }

        // FullCalendar
        const calendarEl = document.getElementById('calendar');
        const events = @json($events);

        if (calendarEl && typeof FullCalendar !== 'undefined') {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    list: 'Daftar'
                },
                events: events.map(e => ({
                    id: e.id,
                    title: e.title,
                    start: e.event_date,
                    backgroundColor: e.attendance_enabled ? '#0d9488' : '#6b7280',
                    borderColor: e.attendance_enabled ? '#0d9488' : '#6b7280',
                    extendedProps: {
                        location: e.location,
                        description: e.description,
                        status: e.status,
                        attendance_enabled: e.attendance_enabled,
                        attendance_code: e.attendance_code,
                        event_id: e.id
                    }
                }),
                eventDidMount: function(info) {
                    info.el.style.cursor = 'pointer';
                }
            });

            calendar.render();
            window.calendar = calendar;
        }

        // QR Scanner
        let html5QrcodeScanner = null;
        let currentScanEventId = null;

        function openQRScanner(eventId) {
            currentScanEventId = eventId;
            const modal = document.getElementById('qrScannerModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            const readerDiv = document.getElementById('qrReader');
            readerDiv.innerHTML = '';

            if (typeof Html5QrcodeScanner !== 'undefined') {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qrReader", { fps: 10, qrbox: 250 });
                html5QrcodeScanner.render(onScanSuccess, onScanError);
            } else {
                document.getElementById('qrReader').innerHTML = `
                    <div class="text-center p-4 text-red-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Library QR Scanner gagal dimuat. Silakan gunakan input manual.</p>
                    </div>
                    <form action="{{ route('admin.member.attendance.scan', ['event' => 0]) }}" method="POST" id="qrManualFormFallback">
                        @csrf
                        <input type="hidden" name="event" id="qrEventIdFallback" value="${eventId}">
                        <div class="flex gap-2 mt-2">
                            <input type="text" name="code" id="qrCodeInputFallback" placeholder="Masukkan 6 digit kode" class="flex-1 h-11 px-4 rounded-xl border border-gray-200 text-sm font-mono text-center" maxlength="6" required>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl">Kirim</button>
                        </div>
                    </form>
                `;
            }
        }

        function closeQRScanner(event) {
            if (event && event.target !== document.getElementById('qrScannerModal')) return;
            const modal = document.getElementById('qrScannerModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';

            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(() => {});
                html5QrcodeScanner = null;
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (!currentScanEventId) return;

            // Close scanner
            closeQRScanner();

            // Submit scan result
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/{{ auth()->user()->role->slug }}/events/${currentScanEventId}/attend/scan`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
            form.appendChild(csrf);

            const codeInput = document.createElement('input');
            codeInput.type = 'hidden';
            codeInput.name = 'code';
            codeInput.value = decodedText;
            form.appendChild(codeInput);

            document.body.appendChild(form);
            form.submit();
        }

        function onScanError(errorMessage) {
            // Ignore scan errors
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('qrScannerModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeQRScanner();
                }
            }
        });
    });
</script>
@endpush