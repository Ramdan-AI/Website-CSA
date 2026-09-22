@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Kegiatan & Presensi</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola presensi Anda untuk Study Club, Mubes, dan kegiatan lainnya.</p>
        </div>
        <div class="inline-flex items-center gap-1 p-1 rounded-xl bg-[var(--bg-muted)]" role="tablist">
            <button id="viewListBtn" type="button" class="csa-seg is-active" aria-pressed="true">
                <x-admin.icon name="list" :size="15" /> Daftar
            </button>
            <button id="viewCalendarBtn" type="button" class="csa-seg" aria-pressed="false">
                <x-admin.icon name="calendar-days" :size="15" /> Kalender
            </button>
        </div>
    </div>

    <div id="calendarView" class="csa-card p-3 sm:p-4 hidden">
        <div id="calendar"></div>
    </div>

    <div id="listView" class="space-y-3">
        @forelse($events as $event)
            @php
                $attendance = $attendanceByEvent[$event->id] ?? null;
                $hasAttended = $attendance !== null;
                $status = $attendance?->status;
                $isClosed = (bool) $event->attendance_closed_at;
                $isPassed = $event->event_date && $event->event_date->isBefore(now()->startOfDay());
                $locked = !$event->attendance_enabled || $isClosed || $hasAttended;
                $statusMap = [
                    'present' => ['Hadir', 'csa-chip-green'],
                    'permit' => ['Izin', 'csa-chip-amber'],
                    'sick' => ['Sakit', 'csa-chip-blue'],
                    'absent' => ['Alfa', 'csa-chip-red'],
                ];
            @endphp
            <article class="csa-card p-4 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center shrink-0 text-[var(--text-secondary)]">
                            <x-admin.icon name="calendar-days" :size="20" />
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[12px] font-medium text-[var(--text-secondary)]">{{ $event->event_date?->translatedFormat('d M Y') }}</span>
                                @if ($attendance)
                                    <span class="csa-chip {{ $statusMap[$status][1] ?? 'csa-chip-blue' }}">{{ $statusMap[$status][0] ?? 'Tercatat' }}</span>
                                @elseif ($isClosed)
                                    <span class="csa-chip">Selesai</span>
                                @endif
                            </div>
                            <h2 class="mt-1.5 text-[15px] font-semibold text-[var(--text-primary)] truncate">{{ $event->title }}</h2>
                            @if ($event->location)
                                <p class="text-[12px] text-[var(--text-muted)] mt-1 inline-flex items-center gap-1.5">
                                    <x-admin.icon name="map-pin" :size="13" /> {{ $event->location }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="shrink-0 text-[12px] text-[var(--text-muted)] sm:text-right">
                        @if (! $event->attendance_enabled)
                            Presensi belum dibuka
                        @elseif ($isClosed)
                            Sesi presensi ditutup
                        @elseif ($hasAttended)
                            Sudah tercatat
                        @else
                            Presensi dibuka
                        @endif
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-[var(--border-soft)]">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <form action="{{ route('admin.member.attendance.self', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="csa-btn csa-btn-primary w-full" @disabled($locked || $isPassed)>
                                <x-admin.icon name="user-check" :size="16" />
                                {{ $hasAttended && $status === 'present' ? 'Sudah Hadir' : 'Presensi Mandiri' }}
                            </button>
                        </form>

                        <button type="button" class="csa-btn csa-btn-ghost w-full" data-qr-trigger data-event-id="{{ $event->id }}" @disabled($locked || $isPassed)>
                            <x-admin.icon name="qr-code" :size="16" /> Scan QR
                        </button>

                        <div class="grid grid-cols-2 gap-2.5">
                            <form action="{{ route('admin.member.attendance.sick', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="csa-btn csa-btn-ghost w-full" @disabled($locked)>
                                    <x-admin.icon name="thermometer" :size="16" /> Sakit
                                </button>
                            </form>
                            <form action="{{ route('admin.member.attendance.permit', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="csa-btn csa-btn-ghost w-full" @disabled($locked)>
                                    <x-admin.icon name="file-signature" :size="16" /> Izin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="csa-card p-12 text-center">
                <x-admin.icon name="calendar-x" :size="36" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada kegiatan</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Kegiatan yang dipublikasikan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- QR Scanner Modal --}}
<div id="qrScannerModal" class="hidden fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm items-center justify-center p-4" data-qr-overlay>
    <div class="csa-card w-full max-w-sm" data-qr-panel>
        <div class="p-4 border-b border-[var(--border-soft)] flex items-center justify-between">
            <h3 class="text-[15px] font-semibold text-[var(--text-primary)]">Scan QR Code</h3>
            <button type="button" class="csa-icon-btn" data-qr-close aria-label="Tutup">
                <x-admin.icon name="x" :size="18" />
            </button>
        </div>
        <div class="p-4" id="qrReader"></div>
        <p class="px-4 pb-4 text-[12px] text-[var(--text-muted)] text-center">Arahkan kamera ke QR Code yang ditampilkan panitia.</p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/dist/html5-qrcode.min.js"></script>
<script>
    (() => {
        const scanUrlTemplate = @json(route('admin.member.attendance.scan', ['event' => '__EVENT__']));

        const setView = (view) => {
            const isCalendar = view === 'calendar';
            document.getElementById('calendarView').classList.toggle('hidden', !isCalendar);
            document.getElementById('listView').classList.toggle('hidden', isCalendar);
            document.getElementById('viewCalendarBtn').classList.toggle('is-active', isCalendar);
            document.getElementById('viewListBtn').classList.toggle('is-active', !isCalendar);
            if (isCalendar && window.calendar) window.calendar.updateSize();
        };

        document.getElementById('viewCalendarBtn').addEventListener('click', () => setView('calendar'));
        document.getElementById('viewListBtn').addEventListener('click', () => setView('list'));

        const calendarEl = document.getElementById('calendar');
        const events = @json($events);
        if (calendarEl && window.FullCalendar) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                height: 'auto',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listWeek' },
                buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'Daftar' },
                events: events.map((event) => ({
                    title: event.title,
                    start: event.event_date,
                    color: event.attendance_enabled ? '#2563eb' : '#64748b',
                })),
            });
            calendar.render();
            window.calendar = calendar;
        }

        let scanner = null;
        let currentEventId = null;
        const modal = document.getElementById('qrScannerModal');

        const closeScanner = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
            if (scanner) {
                scanner.clear().catch(() => {});
                scanner = null;
            }
        };

        const submitCode = (code) => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = scanUrlTemplate.replace('__EVENT__', currentEventId);
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const codeInput = document.createElement('input');
            codeInput.type = 'hidden';
            codeInput.name = 'code';
            codeInput.value = code;
            form.append(csrf, codeInput);
            document.body.appendChild(form);
            form.submit();
        };

        document.querySelectorAll('[data-qr-trigger]').forEach((button) => {
            button.addEventListener('click', () => {
                currentEventId = button.dataset.eventId;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                const reader = document.getElementById('qrReader');
                reader.innerHTML = '';
                if (window.Html5QrcodeScanner) {
                    scanner = new window.Html5QrcodeScanner('qrReader', { fps: 10, qrbox: 240 });
                    scanner.render((text) => { closeScanner(); submitCode(text); }, () => {});
                } else {
                    reader.innerHTML = '<p class="text-[13px] text-[var(--text-muted)] text-center">Kamera tidak tersedia. Gunakan presensi mandiri.</p>';
                }
            });
        });

        document.querySelector('[data-qr-close]').addEventListener('click', closeScanner);
        modal.addEventListener('click', (event) => { if (event.target === modal) closeScanner(); });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeScanner();
        });
    })();
</script>
@endpush
