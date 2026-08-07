{{-- resources/views/pages/admin/expenses/form.blade.php --}}
@extends('layouts.admin')

@section('content')

@php
    $isEdit = isset($expense);
    $title = $isEdit ? 'Edit Pengeluaran' : 'Tambah Pengeluaran';
@endphp

<div class="space-y-6">
    
    <div>
        <p class="text-xs font-semibold text-red-400 uppercase tracking-widest">Keuangan</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">
            {{ $isEdit ? 'Perbarui data pengeluaran.' : 'Catat pengeluaran baru organisasi.' }}
        </p>
    </div>

    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(239,68,68,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-red-400/5 to-rose-400/5 pointer-events-none"></div>
        
        <div class="relative p-6">
            <form action="{{ $isEdit ? route('admin.expenses.update', $expense) : route('admin.expenses.store') }}" 
                  method="POST" 
                  class="space-y-6">
                @csrf
                @if($isEdit) @method('PUT') @endif

                <div class="bg-gray-50/40 rounded-[24px] p-5">
                    <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-red-500"></span>
                        Informasi Pengeluaran
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Keterangan <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="title" id="title" 
                                   value="{{ old('title', $expense->title ?? '') }}"
                                   class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-400/15 transition-all bg-white/60"
                                   placeholder="Contoh: Beli konsumsi rapat, Cetak banner"
                                   required autofocus>
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="amount" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Jumlah (Rp) <span class="text-red-400">*</span>
                                </label>
                                <input type="number" name="amount" id="amount" 
                                       value="{{ old('amount', $expense->amount ?? '') }}"
                                       class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-400/15 transition-all bg-white/60"
                                       placeholder="100000"
                                       min="1"
                                       required>
                                @error('amount')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="transaction_date" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Tanggal Transaksi <span class="text-red-400">*</span>
                                </label>
                                <input type="date" name="transaction_date" id="transaction_date" 
                                       value="{{ old('transaction_date', isset($expense) ? $expense->transaction_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                       class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-400/15 transition-all bg-white/60"
                                       required>
                                @error('transaction_date')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-400/15 transition-all resize-none bg-white/60"
                                      placeholder="Detail pengeluaran...">{{ old('description', $expense->description ?? '') }}</textarea>
                            @error('description')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(239,68,68,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(239,68,68,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-[10px]"></i>
                            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengeluaran' }}
                        </span>
                    </button>
                    <a href="{{ route('admin.expenses.index') }}" 
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 text-[13px] font-semibold rounded-[18px] hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection