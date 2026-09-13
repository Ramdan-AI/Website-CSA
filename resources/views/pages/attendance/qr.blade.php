@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-purple-400 uppercase tracking-widest">Presensi</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">QR Code Absensi</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Scan QR Code di bawah untuk mencatat kehadiran Anda pada event <strong>{{ $event->title }}</strong>.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Display QR (Organizer view) --}}
        <div class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-400/5 to-pink-400/5 pointer-events-none"></div>
            
            <div class="relative p-8 text-center">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Display QR Code</h3>
                
                @if($event->attendance_enabled && $event->attendance_code)
                    @php
                        $scanUrl = route('attendance.scan', ['event' => $event->id, 'code' => $event->attendance_code]);
                        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($scanUrl);
                    @endphp
                    
                    <div class="inline-block p-4 bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
                        <img src="{{ $qrApiUrl }}" alt="Attendance QR Code" class="w-64 h-64 mx-auto">
                    </div>
                    
                    <div class="bg-purple-50 rounded-xl p-4 mb-6">
                        <p class="text-xs text-purple-600 font-semibold uppercase tracking-wider mb-1">KODE MANUAL</p>
                        <p class="text-2xl font-bold text-purple-900 font-mono tracking-widest">{{ $event->attendance_code }}</p>
                    </div>

                    <div class="flex items-center justify-center gap-3">
                        <button onclick="window.print()" class="px-4 py-2 bg-gray-900 text-white text-xs font-semibold rounded-xl shadow transition-all hover:scale-105">
                            <i class="fas fa-print mr-1"></i> Cetak QR
                        </button>
                        <a href="{{ $scanUrl }}" target="_blank" class="px-4 py-2 bg-white text-gray-700 text-xs font-semibold rounded-xl border border-gray-200 shadow-sm transition-all hover:bg-gray-50">
                            <i class="fas fa-external-link-alt mr-1"></i> Link Manual
                        </a>
                    </div>
                @else
                    <div class="py-12">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i class="fas fa-qrcode text-3xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm">QR Code belum digenerate atau presensi belum diaktifkan.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Scanner View (Attendee view) --}}
        <div class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-cyan-400/5 pointer-events-none"></div>
            
            <div class="relative p-8 text-center">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Scan QR Code Kehadiran</h3>
                
                <div id="qr-reader" class="w-full max-w-sm mx-auto overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 mb-6">
                    {{-- QR Camera feed will appear here --}}
                </div>
                <div id="qr-reader-results" class="hidden text-sm font-medium text-emerald-600 bg-emerald-50 p-3 rounded-xl mb-4"></div>

                <p class="text-xs text-gray-400 mb-6">Arahkan kamera ke QR Code yang ditampilkan oleh panitia untuk mencatat kehadiran secara otomatis.</p>
                
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-[11px] text-gray-400 font-semibold mb-3">TIDAK BISA SCAN? GUNAKAN KODE MANUAL</p>
                    <form action="{{ route('admin.attendance.self', $event) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="Masukkan 6 digit kode..." 
                            class="flex-1 h-11 px-4 rounded-xl border border-gray-200/80 text-[13px] font-mono focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 bg-white/60 text-center uppercase">
                        <button type="submit" class="h-11 px-6 bg-blue-500 text-white text-xs font-bold rounded-xl shadow-md transition-all hover:bg-blue-600 active:scale-95">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Assume decodedText is the scan URL
        console.log(`Scan result: ${decodedText}`, decodedResult);
        
        const resultsDiv = document.getElementById('qr-reader-results');
        resultsDiv.textContent = "QR Terdeteksi! Sedang mencatat kehadiran...";
        resultsDiv.classList.remove('hidden');

        // Send to server
        fetch(decodedText, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                resultsDiv.textContent = "Berhasil: " + data.message;
                resultsDiv.className = "text-sm font-medium text-emerald-600 bg-emerald-50 p-3 rounded-xl mb-4";
                setTimeout(() => window.location.reload(), 2000);
            } else if (data.error) {
                resultsDiv.textContent = "Gagal: " + data.error;
                resultsDiv.className = "text-sm font-medium text-red-600 bg-red-50 p-3 rounded-xl mb-4";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultsDiv.textContent = "Gagal: Terjadi kesalahan koneksi";
            resultsDiv.className = "text-sm font-medium text-red-600 bg-red-50 p-3 rounded-xl mb-4";
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endpush
