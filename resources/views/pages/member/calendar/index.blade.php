@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Kegiatan & Presensi</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Presensi Study Club, Mubes, dan kegiatan lain CSA.</p>
        </div>
        <div class="inline-flex items-center gap-1 p-1 rounded-full bg-[var(--bg-muted)]" role="tablist">
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

    <div id="listView" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($events as $event)
            @php
                $attendance = $attendanceByEvent[$event->id] ?? null;
                $status = $attendance?->status;
                $isPresent = $status === 'present'; // hanya hadir yang memblokir presensi mandiri
                $hasAttended = $isPresent;
                $isClosed = (bool) $event->attendance_closed_at;

                // Sesi presensi ditentukan oleh admin (buka/tutup), bukan perbandingan
                // tanggal, agar anggota bisa presensi selama sesi masih dibuka.
                $sessionOpen = $event->attendance_enabled && ! $isClosed;

                // Tombol mandiri aktif selama sesi dibuka, mandiri diaktifkan, dan
                // anggota belum tercatat HADIR (alfa/izin/sakit tetap bisa presensi).
                $selfDisabled = ! $sessionOpen || ! $event->self_attendance_enabled || $isPresent;
                $scanDisabled = ! $sessionOpen;
                $manualDisabled = ! $sessionOpen;
                $statusMap = [
                    'present' => ['Hadir', 'csa-chip-green'],
                    'permit' => ['Izin', 'csa-chip-amber'],
                    'sick' => ['Sakit', 'csa-chip-blue'],
                    'absent' => ['Alfa', 'csa-chip-red'],
                ];
            @endphp
            <article class="csa-panel rounded-2xl overflow-hidden flex flex-col">
                {{-- Banner (klik ke detail) --}}
                <a href="{{ route('admin.events.show', $event) }}" class="group relative block aspect-[16/9] bg-[var(--bg-muted)] overflow-hidden">
                    @if ($event->banner_url)
                        <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.04]">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                            <x-admin.icon name="calendar-days" :size="28" />
                        </div>
                    @endif

                    <div class="absolute top-3 left-3 flex items-center gap-2">
                        @if ($attendance)
                            <span class="csa-chip {{ $statusMap[$status][1] ?? 'csa-chip-blue' }} shadow-sm">{{ $statusMap[$status][0] ?? 'Tercatat' }}</span>
                        @elseif ($isClosed)
                            <span class="csa-chip shadow-sm">Selesai</span>
                        @elseif ($sessionOpen)
                            <span class="csa-chip csa-chip-green shadow-sm">Presensi dibuka</span>
                        @elseif ($event->attendance_enabled)
                            <span class="csa-chip csa-chip-amber shadow-sm">Presensi ditutup</span>
                        @endif
                    </div>

                    <div class="absolute bottom-3 left-3 rounded-lg bg-black/55 backdrop-blur-sm text-white px-2.5 py-1.5 text-center leading-none">
                        <span class="block text-[15px] font-bold">{{ $event->event_date?->format('d') }}</span>
                        <span class="block text-[10px] font-medium uppercase mt-0.5">{{ $event->event_date?->translatedFormat('M') }}</span>
                    </div>
                </a>

                {{-- Isi --}}
                <div class="p-4 flex flex-col flex-1">
                    <h2 class="text-[15px] font-semibold leading-snug line-clamp-2">
                        <a href="{{ route('admin.events.show', $event) }}" class="text-[var(--text-primary)] hover:text-[var(--accent-bright)] transition-colors">{{ $event->title }}</a>
                    </h2>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-2 text-[12px] text-[var(--text-muted)]">
                        <span class="inline-flex items-center gap-1.5"><x-admin.icon name="calendar-days" :size="13" /> {{ $event->event_date?->translatedFormat('d M Y') }}</span>
                        @if ($event->location)
                            <span class="inline-flex items-center gap-1.5"><x-admin.icon name="map-pin" :size="13" /> {{ $event->location }}</span>
                        @endif
                    </div>

                    @if ($attendance?->note)
                        <p class="text-[12px] text-[var(--text-muted)] mt-2 italic">"{{ $attendance->note }}"</p>
                    @endif

                    <div class="mt-4 pt-3 border-t border-[var(--border-soft)]">
                        @if ($sessionOpen && ! $event->self_attendance_enabled && ! $hasAttended)
                            <p class="text-[11px] text-[var(--text-muted)] mb-2 inline-flex items-center gap-1.5">
                                <x-admin.icon name="info" :size="12" /> Presensi mandiri belum diaktifkan panitia — silakan scan QR.
                            </p>
                        @endif

                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('admin.member.attendance.self', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="csa-btn csa-btn-primary w-full !min-h-[38px] !text-[12px] !px-3"
                                    @disabled($selfDisabled)
                                    @if ($selfDisabled && ! $hasAttended) title="Presensi mandiri belum diaktifkan panitia" @endif>
                                    <x-admin.icon name="user-check" :size="14" />
                                    {{ $isPresent ? 'Sudah Hadir' : 'Mandiri' }}
                                </button>
                            </form>

                            <button type="button" class="csa-btn csa-btn-ghost w-full !min-h-[38px] !text-[12px] !px-3" data-qr-trigger data-event-id="{{ $event->id }}" @disabled($scanDisabled)>
                                <x-admin.icon name="qr-code" :size="14" /> Scan QR
                            </button>

                            <button type="button" class="csa-btn csa-btn-ghost w-full !min-h-[38px] !text-[12px] !px-3" @disabled($manualDisabled)
                                data-attendance-reason
                                data-action="{{ route('admin.member.attendance.sick', $event) }}"
                                data-title="Alasan Sakit"
                                data-placeholder="Contoh: Demam, tidak bisa hadir.">
                                <x-admin.icon name="thermometer" :size="14" /> Sakit
                            </button>
                            <button type="button" class="csa-btn csa-btn-ghost w-full !min-h-[38px] !text-[12px] !px-3" @disabled($manualDisabled)
                                data-attendance-reason
                                data-action="{{ route('admin.member.attendance.permit', $event) }}"
                                data-title="Alasan Izin"
                                data-placeholder="Contoh: Ada urusan keluarga.">
                                <x-admin.icon name="file-signature" :size="14" /> Izin
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full csa-panel p-16 text-center">
                <div class="w-14 h-14 rounded-2xl bg-[var(--bg-muted)] flex items-center justify-center mx-auto text-[var(--text-muted)]">
                    <x-admin.icon name="calendar-x" :size="26" />
                </div>
                <p class="text-[15px] font-semibold text-[var(--text-secondary)] mt-4">Belum ada kegiatan</p>
                <p class="text-[13px] text-[var(--text-muted)] mt-1">Kegiatan yang dipublikasikan akan muncul di sini.</p>
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
