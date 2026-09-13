@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Hasil Pencarian</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">"{{ $query }}"</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Hasil pencarian di seluruh data sistem CSA.</p>
    </div>

    {{-- Members --}}
    @if($members->count() > 0)
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-users text-blue-500"></i> Anggota ({{ $members->count() }})
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($members as $member)
                    <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $member->nim ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Events --}}
    @if($events->count() > 0)
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-cyan-500"></i> Acara ({{ $events->count() }})
            </h3>
            <div class="space-y-2">
                @foreach($events as $event)
                    <a href="{{ route('admin.events.show', $event) }}" class="block p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <p class="text-sm font-semibold text-gray-900">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $event->event_date?->format('d M Y') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- News --}}
    @if($news->count() > 0)
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-newspaper text-emerald-500"></i> Berita ({{ $news->count() }})
            </h3>
            <div class="space-y-2">
                @foreach($news as $article)
                    <div class="p-3 rounded-xl border border-gray-100">
                        <p class="text-sm font-semibold text-gray-900">{{ $article->title }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($members->isEmpty() && $events->isEmpty() && $documents->isEmpty() && $news->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
            <i class="fas fa-search text-4xl mb-3"></i>
            <p class="text-sm font-semibold">Tidak ada data yang cocok dengan "{{ $query }}"</p>
        </div>
    @endif

</div>
@endsection