@props(['logs' => collect()])

@if ($logs->isNotEmpty())
    <section class="csa-card overflow-hidden">
        <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)] inline-flex items-center gap-2">
                <x-admin.icon name="scroll-text" :size="16" class="text-[var(--text-muted)]" />
                Aktivitas Sistem
            </h2>
            <a href="{{ route('admin.activity-log.index') }}" class="text-[12px] font-medium hover:underline" style="color: var(--accent);">
                Log lengkap
            </a>
        </div>

        <div class="divide-y divide-[var(--border-soft)]">
            @foreach ($logs as $log)
                <div class="flex items-start gap-3 p-4">
                    <img src="{{ $log->user?->avatar ? asset('storage/' . $log->user->avatar) : asset('storage/profile/profile-placeholder.png') }}"
                        alt="{{ $log->user?->name ?? 'Pengguna' }}" class="w-8 h-8 rounded-full object-cover bg-[var(--bg-muted)] shrink-0">

                    <div class="min-w-0 flex-1">
                        <p class="text-[13px] text-[var(--text-primary)]">
                            <span class="font-medium">{{ $log->user?->name ?? 'Sistem' }}</span>
                            <span class="text-[var(--text-secondary)]">{{ $log->description }}</span>
                        </p>
                        <p class="text-[12px] text-[var(--text-muted)] mt-1">
                            <span class="csa-chip {{ $log->action_classes }} mr-2">{{ $log->action_label }}</span>
                            {{ $log->created_at?->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
