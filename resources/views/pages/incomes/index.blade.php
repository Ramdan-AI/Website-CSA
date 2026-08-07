{{-- resources/views/pages/admin/incomes/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-emerald-400 uppercase tracking-widest">Keuangan</p>
                <h1 class="text-3xl font-bold text-gray-900 mt-1">Pemasukan</h1>
                <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola data pemasukan dan keuangan organisasi CSA.</p>
            </div>
            <a href="{{ route('admin.incomes.create') }}"
                class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(16,185,129,0.3)] hover:shadow-[0_4px_25px_rgba(16,185,129,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                <div
                    class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <span class="relative flex items-center gap-2">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Pemasukan
                </span>
            </a>
        </div>

        <x-admin.table :headers="[
            ['label' => 'Keterangan', 'width' => ''],
            ['label' => 'Jumlah', 'width' => ''],
            ['label' => 'Tanggal', 'width' => ''],
            ['label' => 'Dicatat Oleh', 'width' => ''],
            ['label' => 'Aksi', 'width' => 'w-24'],
        ]" :hasPages="$incomes->hasPages()" :paginator="$incomes">
            @forelse($incomes as $income)
                <tr class="hover:bg-emerald-50/30 transition-colors duration-200">
                    <td class="py-4 px-6">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $income->title }}</p>
                            @if ($income->description)
                                <p class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">{{ $income->description }}</p>
                            @endif
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        <span class="inline-flex items-center gap-1 text-sm font-bold text-emerald-600">
                            <span class="text-[10px]">Rp</span>
                            {{ number_format($income->amount, 0, ',', '.') }}
                        </span>
                    </td>

                    <td class="py-4 px-6">
                        <span class="text-xs text-gray-600">{{ $income->transaction_date->format('d M Y') }}</span>
                    </td>

                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-[9px] ring-2 ring-white shadow-[0_2px_6px_rgba(16,185,129,0.2)]">
                                {{ strtoupper(substr($income->user->name, 0, 2)) }}
                            </div>
                            <span class="text-xs text-gray-600">{{ $income->user->name }}</span>
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.incomes.edit', $income) }}"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(16,185,129,0.3)]"
                                title="Edit">
                                <i class="fas fa-pen text-[10px]"></i>
                            </a>
                            <button
                                onclick="openDeleteModal('deleteModal', '{{ route('admin.incomes.destroy', $income) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
                                title="Hapus">
                                <i class="fas fa-trash text-[10px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <x-admin.empty-state icon="fa-arrow-trend-up" title="Belum ada pemasukan"
                    description="Tambahkan data pemasukan pertama" :createRoute="route('admin.incomes.create')" createLabel="Tambah Pemasukan"
                    :colspan="5" />
            @endforelse
        </x-admin.table>
    </div>
@endsection
