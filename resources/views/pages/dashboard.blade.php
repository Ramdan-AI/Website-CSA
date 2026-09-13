@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Overview</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Berikut ringkasan keuangan dan aktivitas sistem Anda hari
                ini.</p>
        </div>

        @php
            $waLink = \App\Models\Setting::first()?->whatsapp_group_link;
        @endphp
        @if ($waLink)
            <div class="relative overflow-hidden bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent rounded-[24px] border border-emerald-500/20 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 leading-tight">Grup WhatsApp Resmi UKM CSA</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Dapatkan info kegiatan, jadwal pertemuan, dan koordinasi divisi terbaru.</p>
                    </div>
                </div>
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl shadow-[0_4px_12px_rgba(16,185,129,0.25)] hover:scale-105 transition-all shrink-0">
                    <i class="fab fa-whatsapp text-sm"></i>
                    <span>Buka Grup WA</span>
                    <i class="fas fa-arrow-up-right-from-square text-[10px] opacity-80"></i>
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            <div
                class="relative bg-white/80 backdrop-blur-xl rounded-[28px] p-5 border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden group hover:shadow-[0_8px_32px_rgba(59,130,246,0.12)] transition-all duration-300">
                <div
                    class="absolute -top-8 -right-8 w-24 h-24 bg-blue-400/20 rounded-full blur-2xl group-hover:bg-blue-400/30 transition-colors">
                </div>
                <div class="relative flex items-center justify-between mb-4">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Saldo</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-wallet text-sm"></i>
                    </div>
                </div>
                <p class="text-[26px] font-bold text-gray-900 tracking-tight">Rp
                    {{ number_format($balance ?? 0, 0, ',', '.') }}</p>
                <p class="text-[11px] text-gray-400 mt-1 font-medium">Total dana kas</p>
            </div>

            <div
                class="relative bg-white/80 backdrop-blur-xl rounded-[28px] p-5 border border-white/60 shadow-[0_8px_32px_rgba(16,185,129,0.06)] overflow-hidden group hover:shadow-[0_8px_32px_rgba(16,185,129,0.12)] transition-all duration-300">
                <div
                    class="absolute -top-8 -right-8 w-24 h-24 bg-emerald-400/20 rounded-full blur-2xl group-hover:bg-emerald-400/30 transition-colors">
                </div>
                <div class="relative flex items-center justify-between mb-4">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pemasukan</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-arrow-trend-down text-sm"></i>
                    </div>
                </div>
                <p class="text-[26px] font-bold text-gray-900 tracking-tight">Rp
                    {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</p>
                <p class="text-[11px] text-gray-400 mt-1 font-medium">Total pemasukan</p>
            </div>

            <div
                class="relative bg-white/80 backdrop-blur-xl rounded-[28px] p-5 border border-white/60 shadow-[0_8px_32px_rgba(244,63,94,0.06)] overflow-hidden group hover:shadow-[0_8px_32px_rgba(244,63,94,0.12)] transition-all duration-300">
                <div
                    class="absolute -top-8 -right-8 w-24 h-24 bg-rose-400/20 rounded-full blur-2xl group-hover:bg-rose-400/30 transition-colors">
                </div>
                <div class="relative flex items-center justify-between mb-4">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pengeluaran</span>
                    <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600">
                        <i class="fas fa-arrow-trend-up text-sm"></i>
                    </div>
                </div>
                <p class="text-[26px] font-bold text-gray-900 tracking-tight">Rp
                    {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
                <p class="text-[11px] text-gray-400 mt-1 font-medium">Total pengeluaran</p>
            </div>

            <div
                class="relative bg-white/80 backdrop-blur-xl rounded-[28px] p-5 border border-white/60 shadow-[0_8px_32px_rgba(139,92,246,0.06)] overflow-hidden group hover:shadow-[0_8px_32px_rgba(139,92,246,0.12)] transition-all duration-300">
                <div
                    class="absolute -top-8 -right-8 w-24 h-24 bg-violet-400/20 rounded-full blur-2xl group-hover:bg-violet-400/30 transition-colors">
                </div>
                <div class="relative flex items-center justify-between mb-4">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Anggota</span>
                    <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                </div>
                <p class="text-[26px] font-bold text-gray-900 tracking-tight">{{ number_format($totalMembers ?? 0) }}</p>
                <p class="text-[11px] text-gray-400 mt-1 font-medium">Anggota aktif</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div
                class="xl:col-span-2 relative bg-white/80 backdrop-blur-xl rounded-[28px] p-6 border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.05)]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Arus Kas</h3>
                        <p class="text-xs text-gray-400 mt-0.5 font-medium">Perbandingan pemasukan & pengeluaran</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <span class="flex items-center gap-1.5"><span
                                class="w-2.5 h-2.5 rounded-full bg-green-500"></span>Masuk</span>
                        <span class="flex items-center gap-1.5"><span
                                class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>Keluar</span>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>

            <div
                class="relative bg-white/80 backdrop-blur-xl rounded-[28px] p-6 border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.05)]">
                <h3 class="text-lg font-bold text-gray-900 mb-5">Aktivitas Data</h3>

                <div class="space-y-2">
                    <a href="{{ route('admin.news.index') }}"
                        class="flex items-center gap-4 p-3 rounded-2xl hover:bg-white/70 transition-all duration-200 group">
                        <div
                            class="w-11 h-11 rounded-2xl bg-sky-100 flex items-center justify-center text-sky-600 group-hover:scale-105 transition-transform">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Berita</p>
                            <p class="text-xs text-gray-400">{{ number_format($totalNews ?? 0) }} artikel</p>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-0.5 transition-all text-xs"></i>
                    </a>

                    <a href="{{ route('admin.events.index') }}"
                        class="flex items-center gap-4 p-3 rounded-2xl hover:bg-white/70 transition-all duration-200 group">
                        <div
                            class="w-11 h-11 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 group-hover:scale-105 transition-transform">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Acara</p>
                            <p class="text-xs text-gray-400">{{ number_format($totalEvents ?? 0) }} event</p>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-0.5 transition-all text-xs"></i>
                    </a>

                    <a href="{{ route('admin.documents.index') }}"
                        class="flex items-center gap-4 p-3 rounded-2xl hover:bg-white/70 transition-all duration-200 group">
                        <div
                            class="w-11 h-11 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-105 transition-transform">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Dokumen</p>
                            <p class="text-xs text-gray-400">{{ number_format($totalDocuments ?? 0) }} file</p>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-0.5 transition-all text-xs"></i>
                    </a>

                    @if (auth()->user()->role->slug === 'super-admin')
                        <a href="{{ route('admin.divisions.index') }}"
                            class="flex items-center gap-4 p-3 rounded-2xl hover:bg-white/70 transition-all duration-200 group">
                            <div
                                class="w-11 h-11 rounded-2xl bg-violet-100 flex items-center justify-center text-violet-600 group-hover:scale-105 transition-transform">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">Divisi</p>
                                <p class="text-xs text-gray-400">{{ number_format($totalDivisions ?? 0) }} departemen</p>
                            </div>
                            <i
                                class="fas fa-chevron-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-0.5 transition-all text-xs"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const canvas = document.getElementById('financeChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const incomes = @json($chartIncomes ?? array_fill(0, 12, 0));
            const expenses = @json($chartExpenses ?? array_fill(0, 12, 0));

            const gradientIncomes = ctx.createLinearGradient(0, 0, 0, 300);
            gradientIncomes.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradientIncomes.addColorStop(1, 'rgba(16, 185, 129, 0)');

            const gradientExpenses = ctx.createLinearGradient(0, 0, 0, 300);
            gradientExpenses.addColorStop(0, 'rgba(244, 63, 94, 0.2)');
            gradientExpenses.addColorStop(1, 'rgba(244, 63, 94, 0)');

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov',
                        'Des'
                    ],
                    datasets: [{
                            label: 'Pemasukan',
                            data: incomes,
                            borderColor: '#10B981',
                            backgroundColor: gradientIncomes,
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#10B981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenses,
                            borderColor: '#F43F5E',
                            backgroundColor: gradientExpenses,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#F43F5E',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#9CA3AF',
                                padding: 6
                            }
                        },
                        y: {
                            border: {
                                display: false
                            },
                            grid: {
                                color: '#F3F4F6'
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#9CA3AF',
                                padding: 6,
                                maxTicksLimit: 5,
                                callback: (v) => v >= 1000000 ? (v / 1000000) + 'M' : v >= 1000 ? (v /
                                    1000) + 'k' : v
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
