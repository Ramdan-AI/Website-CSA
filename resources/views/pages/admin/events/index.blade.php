@extends('layouts.admin')

@section('content')

<div class="mb-8 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest">EVENTS</span>
            <span class="text-zinc-700">/</span>
            <span class="text-xs font-mono text-zinc-300 uppercase tracking-widest">MANAGEMENT</span>
        </div>
        <h1 class="text-2xl font-semibold tracking-tight text-zinc-100">Events Management</h1>
        <p class="text-sm text-zinc-500 mt-1">Kelola seluruh event dan agenda kegiatan dalam sistem.</p>
    </div>
    
    <a href="{{ route('admin.events.create') }}" class="h-10 px-5 inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-950">
        <i class="fas fa-plus text-xs"></i> Tambah Event
    </a>
</div>

<div class="w-full bg-zinc-950 border border-zinc-800 rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-900 border-b border-zinc-800">
                    <th class="py-3 px-4 text-xs font-semibold text-zinc-400 uppercase tracking-widest w-16 text-center">Banner</th>
                    <th class="py-3 px-4 text-xs font-semibold text-zinc-400 uppercase tracking-widest min-w-[240px]">Judul Event</th>
                    <th class="py-3 px-4 text-xs font-semibold text-zinc-400 uppercase tracking-widest">Tanggal & Lokasi</th>
                    <th class="py-3 px-4 text-xs font-semibold text-zinc-400 uppercase tracking-widest w-28">Status</th>
                    <th class="py-3 px-4 text-xs font-semibold text-zinc-400 uppercase tracking-widest w-24 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse($events as $event)
                <tr class="hover:bg-zinc-900/30 transition-colors">
                    <td class="py-3 px-4 text-center align-middle">
                        @if($event->banner)
                            <div class="w-12 h-12 rounded border border-zinc-800 bg-zinc-900 overflow-hidden mx-auto flex items-center justify-center">
                                <img src="{{ $event->banner_url }}" alt="Banner" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded border border-zinc-800 border-dashed bg-zinc-900/50 mx-auto flex items-center justify-center text-zinc-600">
                                <i class="fas fa-image text-xs"></i>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4 align-middle">
                        <div class="font-semibold text-sm text-zinc-100">{{ $event->title }}</div>
                        <div class="font-mono text-[10px] text-zinc-500 mt-0.5">/{{ $event->slug }}</div>
                    </td>
                    <td class="py-3 px-4 align-middle">
                        <div class="text-sm text-zinc-300 font-mono">{{ $event->event_date?->format('d M Y') }}</div>
                        <div class="text-xs text-zinc-500 truncate max-w-[200px] mt-0.5" title="{{ $event->location }}">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location ?: '-' }}
                        </div>
                    </td>
                    <td class="py-3 px-4 align-middle">
                        @php
                            $statusClasses = [
                                'published' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'draft' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                'closed' => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20',
                            ];
                            $statusClass = $statusClasses[$event->status] ?? 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded border text-[10px] font-mono uppercase tracking-widest {{ $statusClass }}">
                            {{ $event->status }}
                        </span>
                    </td>
                    <td class="py-3 px-4 align-middle text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.events.edit', $event) }}" 
                               class="w-7 h-7 flex items-center justify-center rounded text-zinc-400 hover:text-blue-400 hover:bg-blue-400/10 transition-colors" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="w-7 h-7 flex items-center justify-center rounded text-zinc-400 hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Delete" onclick="if(confirm('Yakin hapus?')) this.form.submit()">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 px-4 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center">
                            <i class="fas fa-calendar-times text-zinc-500"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-100">Belum Ada Event</h3>
                        <p class="text-xs text-zinc-500 mt-1">Sistem belum memiliki data event apapun.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($events->hasPages())
    <div class="p-4 border-t border-zinc-800 bg-zinc-900/30">
        {{ $events->links() }}
    </div>
    @endif
</div>

@endsection