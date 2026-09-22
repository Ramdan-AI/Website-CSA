@props([
    'id' => 'chart',
    'title',
    'description' => null,
    'labels' => [],
    'incomes' => [],
    'expenses' => [],
])

<article {{ $attributes->merge(['class' => 'csa-card p-4 sm:p-5']) }}>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-5">
        <div>
            <h2 class="text-[15px] font-semibold text-[var(--text-primary)]">{{ $title }}</h2>
            @if ($description)
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">{{ $description }}</p>
            @endif
        </div>
        <div class="flex items-center gap-4 text-[11px] text-[var(--text-secondary)]">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-0.5 rounded bg-emerald-500"></span>Pemasukan</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-0.5 rounded bg-red-500"></span>Pengeluaran</span>
        </div>
    </div>
    <div class="h-64 sm:h-72"><canvas id="{{ $id }}"></canvas></div>
</article>

@push('scripts')
<script>
    (() => {
        const initChart = () => {
            const canvas = document.getElementById(@json($id));
            if (!canvas || !window.Chart || canvas.dataset.initialized === 'true') return;

            const context = canvas.getContext('2d');
            const incomeFill = context.createLinearGradient(0, 0, 0, 300);
            incomeFill.addColorStop(0, 'rgba(16, 185, 129, 0.20)');
            incomeFill.addColorStop(1, 'rgba(16, 185, 129, 0)');
            const expenseFill = context.createLinearGradient(0, 0, 0, 300);
            expenseFill.addColorStop(0, 'rgba(239, 68, 68, 0.14)');
            expenseFill.addColorStop(1, 'rgba(239, 68, 68, 0)');
            const dark = document.documentElement.classList.contains('dark');
            const muted = dark ? '#8b92a8' : '#64748b';
            const grid = dark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.08)';

            new window.Chart(canvas, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        { label: 'Pemasukan', data: @json($incomes), borderColor: '#10b981', backgroundColor: incomeFill, borderWidth: 2, tension: .4, pointRadius: 0, pointHoverRadius: 4, pointHoverBackgroundColor: '#10b981', pointHoverBorderColor: '#fff', pointHoverBorderWidth: 2, fill: true },
                        { label: 'Pengeluaran', data: @json($expenses), borderColor: '#ef4444', backgroundColor: expenseFill, borderWidth: 2, tension: .4, pointRadius: 0, pointHoverRadius: 4, pointHoverBackgroundColor: '#ef4444', pointHoverBorderColor: '#fff', pointHoverBorderWidth: 2, fill: true },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: dark ? '#1a1f2e' : '#ffffff', titleColor: dark ? '#e6e9f0' : '#0f172a', bodyColor: muted, borderColor: grid, borderWidth: 1, cornerRadius: 10, padding: 12, titleFont: { weight: '600', family: 'Plus Jakarta Sans' } }
                    },
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: muted, font: { size: 11, family: 'Plus Jakarta Sans' } } },
                        y: { grid: { color: grid }, border: { display: false }, ticks: { color: muted, font: { size: 11, family: 'Plus Jakarta Sans' }, maxTicksLimit: 5, callback: (value) => value >= 1000000 ? (value / 1000000) + 'M' : value >= 1000 ? (value / 1000) + 'K' : value } }
                    }
                }
            });
            canvas.dataset.initialized = 'true';
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChart);
        } else {
            initChart();
        }
        window.addEventListener('load', initChart);
        let retries = 0;
        const interval = setInterval(() => {
            initChart();
            retries++;
            if (retries > 10 || document.getElementById(@json($id))?.dataset.initialized === 'true') {
                clearInterval(interval);
            }
        }, 150);
    })();
</script>
@endpush
