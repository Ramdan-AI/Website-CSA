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

    <div id="calendarView" class="hidden">
        <x-admin.calendar :events="$events->map(fn ($e) => [
            'title' => $e->title,
            'date' => optional($e->event_date)->toDateString(),
            'active' => $e->attendance_enabled && ! $e->attendance_closed_at,
        ])->all()" />
    </div>

    <div id="listView" class="space-y-3">
        @forelse($events as $event)
            @php
                $attendance = $attendanceByEvent[$event->id] ?? null;
                $hasAttended = $attendance !== null;
                $status = $attendance?->status;
                $isClosed = (bool) $event->attendance_closed_at;
                $isPassed = $event->event_date && $event->event_date->isBefore(now()->startOfDay());
                $sessionOpen = $event->attendance_enabled && ! $isClosed && ! $isPassed;

                // Presensi mandiri hanya bila admin mengaktifkannya.
                $selfDisabled = ! $sessionOpen || ! $event->self_attendance_enabled || $hasAttended;
                // Scan QR & sakit/izin hanya butuh sesi aktif.
                $scanDisabled = ! $sessionOpen;
                $manualDisabled = ! $sessionOpen;
                $statusMap = [
                    'present' => ['Hadir', 'csa-chip-green'],
                    'permit' => ['Izin', 'csa-chip-amber'],
                    'sick' => ['Sakit', 'csa-chip-blue'],
                    'absent' => ['Alfa', 'csa-chip-red'],
                ];
            @endphp
            <article class="csa-panel p-4 sm:p-5">
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
                            <button type="submit" class="csa-btn csa-btn-primary w-full" @disabled($selfDisabled)
                                title="{{ ! $event->self_attendance_enabled ? 'Presensi mandiri belum diaktifkan panitia' : '' }}">
                                <x-admin.icon name="user-check" :size="16" />
                                {{ $hasAttended && $status === 'present' ? 'Sudah Hadir' : 'Presensi Mandiri' }}
                            </button>
                        </form>

                        <button type="button" class="csa-btn csa-btn-ghost w-full" data-qr-trigger data-event-id="{{ $event->id }}" @disabled($scanDisabled)>
                            <x-admin.icon name="qr-code" :size="16" /> Scan QR
                        </button>

                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" class="csa-btn csa-btn-ghost w-full" @disabled($manualDisabled)
                                data-attendance-reason
                                data-action="{{ route('admin.member.attendance.sick', $event) }}"
                                data-type="sakit"
                                data-title="Alasan Sakit"
                                data-placeholder="Contoh: Demam, tidak bisa hadir.">
                                <x-admin.icon name="thermometer" :size="16" /> Sakit
                            </button>
                            <button type="button" class="csa-btn csa-btn-ghost w-full" @disabled($manualDisabled)
                                data-attendance-reason
                                data-action="{{ route('admin.member.attendance.permit', $event) }}"
                                data-type="izin"
                                data-title="Alasan Izin"
                                data-placeholder="Contoh: Ada urusan keluarga.">
                                <x-admin.icon name="file-signature" :size="16" /> Izin
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="csa-panel p-12 text-center">
                <x-admin.icon name="calendar-x" :size="36" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada kegiatan</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Kegiatan yang dipublikasikan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- QR Scanner Modal --}}
<div id="qrScannerModal" class="hidden fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm items-center justify-center p-4" data-qr-overlay>
    <div class="csa-panel w-full max-w-sm" data-qr-panel>
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

{{-- Modal alasan sakit/izin --}}
<div id="reasonModal" class="hidden fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm items-center justify-center p-4">
    <div class="csa-panel w-full max-w-sm">
        <div class="p-4 border-b border-[var(--border-soft)] flex items-center justify-between">
            <h3 class="text-[15px] font-semibold text-[var(--text-primary)]" id="reasonModalTitle">Alasan</h3>
            <button type="button" class="csa-icon-btn" data-reason-close aria-label="Tutup">
                <x-admin.icon name="x" :size="18" />
            </button>
        </div>
        <form method="POST" id="reasonForm" class="p-4 space-y-4">
            @csrf
            <div>
                <label for="reasonInput" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Keterangan</label>
                <textarea id="reasonInput" name="note" rows="3" maxlength="255" required
                    class="csa-input" placeholder=""></textarea>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="csa-btn csa-btn-primary flex-1">Simpan</button>
                <button type="button" class="csa-btn csa-btn-ghost" data-reason-close>Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    (() => {
        const scanUrlTemplate = @json(route('admin.member.attendance.scan', ['event' => '__EVENT__']));

        const setView = (view) => {
            const isCalendar = view === 'calendar';
            document.getElementById('calendarView').classList.toggle('hidden', !isCalendar);
            document.getElementById('listView').classList.toggle('hidden', isCalendar);
            document.getElementById('viewCalendarBtn').classList.toggle('is-active', isCalendar);
            document.getElementById('viewListBtn').classList.toggle('is-active', !isCalendar);
        };

        document.getElementById('viewCalendarBtn').addEventListener('click', () => setView('calendar'));
        document.getElementById('viewListBtn').addEventListener('click', () => setView('list'));

        let scanner = null;
        let scannerStopping = null;
        let currentEventId = null;
        const modal = document.getElementById('qrScannerModal');

        // Hentikan kamera & rendering pemindai, lalu bersihkan instance.
        const stopScanner = () => {
            const active = scanner;
            scanner = null;
            if (!active) return Promise.resolve();

            if (scannerStopping) return scannerStopping;

            scannerStopping = Promise.resolve()
                .then(() => active.stop?.())
                .catch(() => {})
                .then(() => active.clear?.())
                .catch(() => {})
                .finally(() => { scannerStopping = null; });

            return scannerStopping;
        };

        const closeScanner = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';

            // Hentikan kamera sungguhan (bukan sekadar menyembunyikan modal).
            stopScanner().then(() => {
                const reader = document.getElementById('qrReader');
                if (reader) reader.innerHTML = '';
            });
        };

        // Pengaman: pastikan kamera mati saat halaman ditinggalkan/di-unload.
        window.addEventListener('pagehide', () => stopScanner());
        window.addEventListener('beforeunload', () => stopScanner());

        const submitCode = (code) => {
            const text = (code || '').trim();
            // QR bisa berisi URL penuh (…/scan/ABC123 atau ?code=ABC123) atau kode 6 karakter.
            let value = text;
            const queryMatch = text.match(/[?&]code=([A-Za-z0-9]{4,8})/);
            const pathMatch = text.match(/\/scan\/([A-Za-z0-9]{4,8})/);
            if (queryMatch) value = queryMatch[1];
            else if (pathMatch) value = pathMatch[1];
            else value = text.slice(-6);

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
            codeInput.value = value;
            form.append(csrf, codeInput);
            document.body.appendChild(form);
            form.submit();
        };

        // Tunggu library pemindai termuat (CDN bisa lambat), lalu jalankan.
        const whenScannerReady = (cb, tries = 0) => {
            if (window.Html5Qrcode) return cb();
            if (tries > 25) return cb(new Error('library-missing'));

            return setTimeout(() => whenScannerReady(cb, tries + 1), 200);
        };

        const startScanner = (stage) => {
            whenScannerReady((err) => {
                // Modal mungkin sudah ditutup sebelum library siap -> jangan nyalakan kamera.
                if (modal.classList.contains('hidden')) return;

                if (err || !window.Html5Qrcode) {
                    stage.innerHTML = '<p class="text-[13px] text-red-500 text-center py-6">Modul pemindai gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.</p>';
                    return;
                }

                const config = { fps: 10, qrbox: { width: 220, height: 220 } };
                const onScan = (text) => { closeScanner(); submitCode(text); };

                scanner = new window.Html5Qrcode('qrReaderStage');
                scanner.start({ facingMode: 'environment' }, config, onScan, () => {})
                    .catch(() => {
                        // Fallback kamera depan (umum di laptop), hentikan yang lama dulu.
                        return stopScanner().then(() => {
                            if (modal.classList.contains('hidden')) return;

                            scanner = new window.Html5Qrcode('qrReaderStage');
                            return scanner.start({ facingMode: 'user' }, config, onScan, () => {});
                        });
                    })
                    .catch(() => {
                        stage.innerHTML = '<p class="text-[13px] text-red-500 text-center py-6">Gagal membuka kamera. Pastikan izin kamera diizinkan di pengaturan browser.</p>';
                    });
            });
        };

        document.querySelectorAll('[data-qr-trigger]').forEach((button) => {
            button.addEventListener('click', () => {
                currentEventId = button.dataset.eventId;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                const reader = document.getElementById('qrReader');

                // Hentikan kamera yang mungkin masih aktif sebelum memulai ulang.
                stopScanner().then(() => {
                    reader.innerHTML = '';

                    // Kamera hanya tersedia pada konteks aman (HTTPS atau localhost).
                    const secure = window.isSecureContext
                        || location.hostname === 'localhost'
                        || location.hostname === '127.0.0.1';

                    if (!secure) {
                        reader.innerHTML = '<p class="text-[13px] text-red-500 text-center py-6">Kamera hanya bisa diakses lewat HTTPS atau localhost. Hubungi admin untuk mengaktifkan HTTPS.</p>';
                        return;
                    }

                    if (!navigator.mediaDevices?.getUserMedia) {
                        reader.innerHTML = '<p class="text-[13px] text-red-500 text-center py-6">Browser ini tidak mendukung akses kamera.</p>';
                        return;
                    }

                    reader.innerHTML = '<div id="qrReaderStage" class="w-full rounded-xl overflow-hidden"></div>';
                    startScanner(document.getElementById('qrReaderStage'));
                });
            });
        });

        document.querySelector('[data-qr-close]').addEventListener('click', closeScanner);
        modal.addEventListener('click', (event) => { if (event.target === modal) closeScanner(); });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeScanner();
            if (event.key === 'Escape' && !reasonModal.classList.contains('hidden')) closeReason();
        });

        // Modal alasan sakit/izin
        const reasonModal = document.getElementById('reasonModal');
        const reasonForm = document.getElementById('reasonForm');
        const reasonTitle = document.getElementById('reasonModalTitle');
        const reasonInput = document.getElementById('reasonInput');

        const closeReason = () => {
            reasonModal.classList.add('hidden');
            reasonModal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        document.querySelectorAll('[data-attendance-reason]').forEach((button) => {
            button.addEventListener('click', () => {
                reasonForm.setAttribute('action', button.dataset.action);
                reasonTitle.textContent = button.dataset.title;
                reasonInput.setAttribute('placeholder', button.dataset.placeholder || '');
                reasonInput.value = '';
                reasonModal.classList.remove('hidden');
                reasonModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                setTimeout(() => reasonInput.focus(), 50);
            });
        });

        document.querySelectorAll('[data-reason-close]').forEach((el) => el.addEventListener('click', closeReason));
        reasonModal.addEventListener('click', (event) => { if (event.target === reasonModal) closeReason(); });
    })();
</script>
@endpush
