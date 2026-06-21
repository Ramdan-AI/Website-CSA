@extends('layouts.admin')

@push('styles')
    <style>
        /* Reset & Utility Dashboard */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 32px;
        }

        .dashboard-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin: 0;
        }

        .solid-card {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .solid-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(15, 23, 42, 0.05);
        }

        /* Stat Cards Design (Diperbarui dengan Struktur Visual Data) */
        .stat-card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .stat-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            width: 100%;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.2;
        }

        /* Solid Icon Boxes & Accents */
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .accent-green .icon-box {
            background: #ECFDF5;
            color: var(--csa-green);
        }

        .accent-red .icon-box {
            background: #FEF2F2;
            color: #DC2626;
        }

        .accent-blue .icon-box {
            background: #EFF6FF;
            color: var(--csa-blue);
        }

        .accent-purple .icon-box {
            background: #FAF5FF;
            color: #9333EA;
        }

        /* CSS Mini Charts untuk Stat Cards */
        .mini-chart {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 36px;
            margin-top: auto;
            width: 100%;
        }

        .mini-chart .bar {
            flex: 1;
            border-radius: 4px 4px 0 0;
            opacity: 0.2;
        }

        .mini-chart .bar:last-child {
            opacity: 1;
        }

        .accent-green .mini-chart .bar {
            background: var(--csa-green);
        }

        .accent-red .mini-chart .bar {
            background: #DC2626;
        }

        .accent-blue .mini-chart .bar {
            background: var(--csa-blue);
        }

        .accent-purple .mini-chart .bar {
            background: #9333EA;
        }

        /* Section & List Styles */
        .card-header-solid {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-soft);
            background: transparent;
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        /* System Summary List (Kodingan Asli) */
        .summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-soft);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item-label {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .summary-item-label i {
            color: var(--csa-blue);
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .summary-item-value {
            font-weight: 700;
            color: var(--text-main);
            background: var(--bg-body);
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        /* Activity Timeline (Diperbarui) */
        .timeline {
            position: relative;
            padding: 24px 24px 24px 48px;
            /* Disesuaikan untuk dot yang ditarik ke kiri */
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 31px;
            /* Posisi garis relatif terhadap container */
            top: 32px;
            bottom: 24px;
            width: 2px;
            background: var(--border-soft);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 28px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -24px;
            top: 4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--surface);
            border: 4px solid var(--csa-blue);
            box-shadow: 0 0 0 2px var(--surface);
            /* Memastikan border luar bersih */
        }

        .timeline-dot.success {
            border-color: var(--csa-green);
        }

        .timeline-content {
            font-size: 0.95rem;
            color: var(--text-main);
            font-weight: 600;
            line-height: 1.5;
        }

        .timeline-time {
            display: block;
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 6px;
        }

        /* Visual Mockup Chart Stand-in (Kodingan Asli) */
        .ratio-container {
            padding: 32px 24px;
        }

        .ratio-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .ratio-label-item {
            display: flex;
            flex-direction: column;
        }

        .ratio-label-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .ratio-label-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .ratio-label-item.income .ratio-label-value {
            color: var(--csa-green);
        }

        .ratio-label-item.expense .ratio-label-value {
            color: #DC2626;
            text-align: right;
        }

        .ratio-bar-wrapper {
            height: 24px;
            background: var(--bg-body);
            border-radius: 12px;
            display: flex;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .ratio-bar-income {
            background: var(--csa-green);
            height: 100%;
            transition: width 1s ease-in-out;
        }

        .ratio-bar-expense {
            background: #DC2626;
            height: 100%;
            transition: width 1s ease-in-out;
        }

        .ratio-legend {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }
    </style>
@endpush

@section('content')
    @php
        /* ======================================================================
            EKSTRAKSI DATA REAL-TIME DI LEVEL VIEW
       ====================================================================== */
        $activities = collect();

        try {
            if (class_exists(\App\Models\News::class)) {
                $news = \App\Models\News::latest()->take(5)->get()->map(
                    fn($item) => (object) [
                        'desc' => 'Berita baru berjudul "' . $item->title . '" ditambahkan.',
                        'time' => $item->created_at,
                        'status' => $item->status === 'published' ? 'success' : '',
                    ],
                );
                $activities = $activities->merge($news);
            }

            if (class_exists(\App\Models\Event::class)) {
                $events = \App\Models\Event::latest()->take(5)->get()->map(
                    fn($item) => (object) [
                        'desc' => 'Event "' . $item->title . '" dijadwalkan.',
                        'time' => $item->created_at,
                        'status' => '',
                    ],
                );
                $activities = $activities->merge($events);
            }

            if (class_exists(\App\Models\Document::class)) {
                $docs = \App\Models\Document::latest()->take(5)->get()->map(
                    fn($item) => (object) [
                        'desc' => 'Dokumen "' . $item->title . '" diunggah ke sistem.',
                        'time' => $item->created_at,
                        'status' => 'success',
                    ],
                );
                $activities = $activities->merge($docs);
            }

            $recentActivities = $activities->sortByDesc('time')->take(5);
        } catch (\Exception $e) {
            $recentActivities = collect();
        }
    @endphp

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Dashboard Overview</h1>
            <p class="subtitle">Selamat datang kembali, {{ auth()->user()->name }}. Berikut adalah ringkasan sistem hari ini.
            </p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="solid-card h-100 accent-green">
                <div class="stat-card-body">
                    <div class="stat-wrapper">
                        <div class="stat-info">
                            <span class="stat-label">Total Income</span>
                            <span class="stat-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                        </div>
                        <div class="icon-box">
                            <i class="fas fa-arrow-trend-up"></i>
                        </div>
                    </div>
                    <div class="mini-chart">
                        <div class="bar" style="height: 30%"></div>
                        <div class="bar" style="height: 50%"></div>
                        <div class="bar" style="height: 40%"></div>
                        <div class="bar" style="height: 70%"></div>
                        <div class="bar" style="height: 60%"></div>
                        <div class="bar" style="height: 90%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="solid-card h-100 accent-red">
                <div class="stat-card-body">
                    <div class="stat-wrapper">
                        <div class="stat-info">
                            <span class="stat-label">Total Expense</span>
                            <span class="stat-value">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                        </div>
                        <div class="icon-box">
                            <i class="fas fa-arrow-trend-down"></i>
                        </div>
                    </div>
                    <div class="mini-chart">
                        <div class="bar" style="height: 40%"></div>
                        <div class="bar" style="height: 30%"></div>
                        <div class="bar" style="height: 60%"></div>
                        <div class="bar" style="height: 50%"></div>
                        <div class="bar" style="height: 80%"></div>
                        <div class="bar" style="height: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="solid-card h-100 accent-blue">
                <div class="stat-card-body">
                    <div class="stat-wrapper">
                        <div class="stat-info">
                            <span class="stat-label">Total Members</span>
                            <span class="stat-value">{{ $totalMembers }}</span>
                        </div>
                        <div class="icon-box">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mini-chart">
                        <div class="bar" style="height: 60%"></div>
                        <div class="bar" style="height: 65%"></div>
                        <div class="bar" style="height: 70%"></div>
                        <div class="bar" style="height: 75%"></div>
                        <div class="bar" style="height: 80%"></div>
                        <div class="bar" style="height: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="solid-card h-100 accent-purple">
                <div class="stat-card-body">
                    <div class="stat-wrapper">
                        <div class="stat-info">
                            <span class="stat-label">Total Events</span>
                            <span class="stat-value">{{ $totalEvents }}</span>
                        </div>
                        <div class="icon-box">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mini-chart">
                        <div class="bar" style="height: 20%"></div>
                        <div class="bar" style="height: 40%"></div>
                        <div class="bar" style="height: 30%"></div>
                        <div class="bar" style="height: 70%"></div>
                        <div class="bar" style="height: 50%"></div>
                        <div class="bar" style="height: 80%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="solid-card h-100">
                <div class="card-header-solid">
                    <h2 class="section-title">Financial Ratio Overview</h2>
                </div>

                <div class="ratio-container">
                    @php
                        $totalFinance = $totalIncome + $totalExpense;
                        $incomePercent = $totalFinance > 0 ? round(($totalIncome / $totalFinance) * 100) : 50;
                        $expensePercent = $totalFinance > 0 ? round(($totalExpense / $totalFinance) * 100) : 50;
                    @endphp

                    <div class="ratio-labels">
                        <div class="ratio-label-item income">
                            <span class="ratio-label-title">Total Income</span>
                            <span class="ratio-label-value">{{ $incomePercent }}%</span>
                        </div>
                        <div class="ratio-label-item expense">
                            <span class="ratio-label-title">Total Expense</span>
                            <span class="ratio-label-value">{{ $expensePercent }}%</span>
                        </div>
                    </div>

                    <div class="ratio-bar-wrapper">
                        <div class="ratio-bar-income" style="width: {{ $incomePercent }}%;"
                            title="Income: {{ $incomePercent }}%"></div>
                        <div class="ratio-bar-expense" style="width: {{ $expensePercent }}%;"
                            title="Expense: {{ $expensePercent }}%"></div>
                    </div>

                    <div class="ratio-legend">
                        <span><i class="fas fa-circle me-1" style="color: var(--csa-green); font-size: 0.6rem;"></i>
                            Inflow</span>
                        <span><i class="fas fa-circle me-1" style="color: #DC2626; font-size: 0.6rem;"></i> Outflow</span>
                    </div>

                    <div class="mt-4 text-muted"
                        style="font-size: 0.85rem; line-height: 1.6; text-align: center; background: var(--bg-body); padding: 16px; border-radius: 8px;">
                        <i class="fas fa-info-circle me-1"></i> Visualisasi ini merepresentasikan perbandingan langsung
                        antara total pemasukan dan pengeluaran organisasi.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="solid-card h-100">
                <div class="card-header-solid">
                    <h2 class="section-title">System Summary</h2>
                </div>
                <ul class="summary-list">
                    <li class="summary-item">
                        <div class="summary-item-label">
                            <i class="fas fa-user-shield"></i> System Users
                        </div>
                        <div class="summary-item-value">{{ $totalUsers }}</div>
                    </li>
                    <li class="summary-item">
                        <div class="summary-item-label">
                            <i class="fas fa-sitemap"></i> Divisions
                        </div>
                        <div class="summary-item-value">{{ $totalDivisions }}</div>
                    </li>
                    <li class="summary-item">
                        <div class="summary-item-label">
                            <i class="fas fa-id-badge"></i> Positions
                        </div>
                        <div class="summary-item-value">{{ $totalPositions }}</div>
                    </li>
                    <li class="summary-item">
                        <div class="summary-item-label">
                            <i class="fas fa-file-alt"></i> Documents
                        </div>
                        <div class="summary-item-value">{{ $totalDocuments }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-12">
            <div class="solid-card">
                <div class="card-header-solid">
                    <h2 class="section-title">Recent Activity</h2>
                </div>

                <div class="timeline">
                    @forelse ($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $activity->status }}"></div>
                            <div class="timeline-content">{{ $activity->desc }}</div>
                            <span
                                class="timeline-time">{{ \Carbon\Carbon::parse($activity->time)->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 40px 24px;">
                            <i class="fas fa-history"
                                style="font-size: 2rem; color: var(--border-hard); margin-bottom: 12px;"></i>
                            <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">
                                Sistem Kosong</h4>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">Belum ada aktivitas berita,
                                event, atau dokumen yang terekam di database.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
