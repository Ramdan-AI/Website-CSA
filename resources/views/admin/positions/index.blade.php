@extends('layouts.admin')

@push('styles')
<style>
/* Page Header */
.page-header {
    margin-bottom: 32px;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-main);
    letter-spacing: -0.02em;
    margin-bottom: 6px;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
    font-weight: 500;
    margin: 0;
}

/* Custom Buttons */
.btn-csa-primary {
    background-color: var(--csa-blue);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 10px 24px;
    border-radius: 10px;
    border: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(54, 100, 175, 0.1);
}

.btn-csa-primary:hover {
    background-color: #2a508f;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(54, 100, 175, 0.2);
}

/* Solid Card Container */
.table-card {
    background: var(--surface);
    border: 1px solid var(--border-soft);
    border-radius: 16px;
    overflow: hidden;
}

/* Custom Table */
.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.custom-table th {
    background-color: #F8FAFC;
    color: #64748B;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-soft);
    white-space: nowrap;
}

.custom-table td {
    padding: 16px 24px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-soft);
    color: var(--text-main);
    font-size: 0.9rem;
}

.custom-table tbody tr {
    transition: background-color 0.2s ease;
}

.custom-table tbody tr:hover {
    background-color: #F8FAFC;
}

.custom-table tbody tr:last-child td {
    border-bottom: none;
}

/* Typography Hierarchy inside Table */
.data-id {
    font-family: 'JetBrains Mono', monospace, sans-serif;
    color: var(--csa-blue);
    font-size: 0.8rem;
    font-weight: 600;
    background: #EFF6FF;
    padding: 4px 10px;
    border-radius: 6px;
    border: 1px solid #DBEAFE;
}

.data-name {
    font-weight: 700;
    color: var(--text-main);
    font-size: 0.95rem;
}

.data-description {
    color: var(--text-muted);
    max-width: 450px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Action Icon Buttons */
.action-group {
    display: flex;
    gap: 10px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid transparent;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    background-color: var(--bg-body);
}

.btn-icon.edit {
    color: var(--csa-blue);
}

.btn-icon.edit:hover {
    background-color: #EFF6FF;
    border-color: #BFDBFE;
}

.btn-icon.delete {
    color: #EF4444;
}

.btn-icon.delete:hover {
    background-color: #FEF2F2;
    border-color: #FECACA;
}

/* Empty State */
.empty-state {
    padding: 64px 24px;
    text-align: center;
}

.empty-state-icon {
    width: 64px;
    height: 64px;
    background: #F8FAFC;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-state-icon i {
    font-size: 1.8rem;
    color: #94A3B8;
}

.empty-state-text {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--text-main);
    margin-bottom: 8px;
}

.empty-state-subtext {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* Pagination Wrapper (SaaS Style) */
.pagination-wrapper {
    padding: 20px 24px;
    border-top: 1px solid var(--border-soft);
    background: var(--surface);
}

.pagination { 
    margin: 0; 
    gap: 6px;
}

.page-item .page-link { 
    color: var(--text-muted); 
    border: 1px solid var(--border-soft);
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.page-item .page-link:hover {
    background-color: #F8FAFC;
    color: var(--text-main);
}

.page-item.active .page-link { 
    background-color: var(--csa-blue); 
    border-color: var(--csa-blue);
    color: white; 
}

.page-item.disabled .page-link {
    background-color: transparent;
    opacity: 0.5;
}
</style>
@endpush

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Positions Management</h1>
        <p class="page-subtitle">Kelola daftar jabatan operasional dan peran entitas dalam sistem.</p>
    </div>
    
    <a href="{{ route('admin.positions.create') }}" class="btn-csa-primary">
        <i class="fas fa-plus"></i> Tambah Posisi
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="100">ID</th>
                    <th width="280">Nama Posisi</th>
                    <th>Deskripsi Peran</th>
                    <th width="140" class="text-end">Aksi Sistem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $position)
                    <tr>
                        <td>
                            <span class="data-id">#{{ str_pad($position->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div class="data-name">{{ $position->name }}</div>
                        </td>
                        <td>
                            <div class="data-description" title="{{ $position->description }}">
                                {{ $position->description ?: '-' }}
                            </div>
                        </td>
                        <td>
                            <div class="action-group justify-content-end">
                                <a href="{{ route('admin.positions.edit', $position) }}" class="btn-icon edit" title="Edit Data">
                                    <i class="fas fa-pen"></i>
                                </a>
                                
                                <form action="{{ route('admin.positions.destroy', $position) }}" method="POST" class="d-inline" onsubmit="return confirm('Proses ini tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus posisi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" title="Hapus Data">
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
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="empty-state-text">Database Kosong</div>
                                <div class="empty-state-subtext">Belum ada data posisi atau jabatan yang diregistrasikan. Silakan tambahkan entri baru.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($positions->hasPages())
        <div class="pagination-wrapper d-flex justify-content-end">
            {{ $positions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection