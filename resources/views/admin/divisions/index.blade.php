@extends('layouts.admin')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Divisions Management</h1>
        <p class="page-subtitle">Kelola struktur dan informasi divisi dalam sistem operasional CSA.</p>
    </div>
    
    <a href="{{ route('admin.divisions.create') }}" class="btn-csa-primary">
        <i class="fas fa-plus"></i> Tambah Divisi
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="100">ID</th>
                    <th width="280">Nama Divisi</th>
                    <th>Deskripsi Opersional</th>
                    <th width="140" class="text-end">Aksi Sistem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($divisions as $division)
                    <tr>
                        <td>
                            <span class="data-id">#{{ str_pad($division->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div class="data-name">{{ $division->name }}</div>
                        </td>
                        <td>
                            <div class="data-description" title="{{ $division->description }}">
                                {{ $division->description ?: '-' }}
                            </div>
                        </td>
                        <td>
                            <div class="action-group justify-content-end">
                                <a href="{{ route('admin.divisions.edit', $division) }}" class="btn-icon edit" title="Edit Data">
                                    <i class="fas fa-pen"></i>
                                </a>
                                
                                <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete trigger-delete" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 0;">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <div class="empty-state-text">Database Kosong</div>
                                <div class="empty-state-subtext">Belum ada data divisi yang diregistrasikan. Silakan tambahkan entri baru.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($divisions->hasPages())
        <div class="pagination-wrapper d-flex justify-content-end">
            {{ $divisions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection