@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-cyan-400 uppercase tracking-widest">Kegiatan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Acara & Study Club</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Daftar kegiatan, workshop, dan study club UKM CSA.</p>
        </div>
        @can('manage-events')
            <a href="{{ route('admin.events.create') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-cyan-600 text-white text-sm font-semibold rounded-xl hover:bg-cyan-700 transition-colors shadow-sm">
                <i class="fas fa-plus text-xs"></i>
                Tambah Acara
            </a>
        @endcan
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    @if($event->banner_url)
                        <div class="h-44 w-full bg-gray-100 overflow-hidden">
                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.closest('div').innerHTML='<div class=\'h-44 w-full bg-gray-50 flex items-center justify-center text-gray-300 border-b border-gray-100\'><i class=\'fas fa-calendar-alt text-4xl\'></i></div>'">
                        </div>
                    @else
                        <div class="h-44 w-full bg-gray-50 flex items-center justify-center text-gray-300 border-b border-gray-100">
                            <i class="fas fa-calendar-alt text-4xl"></i>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-center justify-between gap-2 mb-2">
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

                        <h3 class="text-base font-bold text-gray-900 line-clamp-2 mb-2">
                            <a href="{{ route('admin.events.show', $event) }}" class="hover:text-cyan-600 transition-colors">
                                {{ $event->title }}
                            </a>
                        </h3>

                        <p class="text-xs text-gray-500 line-clamp-3 mb-4">
                            {{ Str::limit(strip_tags($event->description), 120) }}
                        </p>

                        @if($event->location)
                            <p class="text-xs text-gray-400">
                                <i class="fas fa-map-marker-alt text-cyan-500 mr-1"></i> {{ $event->location }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('admin.events.show', $event) }}" class="text-xs font-semibold text-cyan-600 hover:underline">
                        Lihat Detail
                    </a>

                    @can('manage-events')
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.events.edit', $event) }}"
                               class="w-7 h-7 rounded-lg flex items-center justify-center bg-white text-cyan-600 border border-gray-200 hover:bg-cyan-50 transition-colors"
                               title="Edit">
                                <i class="fas fa-pen text-[10px]"></i>
                            </a>
                            <button onclick="openDeleteModal('deleteModal', '{{ route('admin.events.destroy', $event) }}')"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center bg-white text-red-500 border border-gray-200 hover:bg-red-50 transition-colors"
                                    title="Hapus">
                                <i class="fas fa-trash text-[10px]"></i>
                            </button>
                        </div>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p class="text-sm font-semibold">Belum ada acara tercatat.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $events->links() }}
    </div>

</div>
@endsection