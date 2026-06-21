@props([
    'colspan' => 1,
    'icon' => 'folder-open',
    'title' => 'Database Kosong',
    'subtitle' => 'Belum ada data'
])

<tr>
    <td colspan="{{ $colspan }}" style="padding: 0;">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-{{ $icon }}"></i>
            </div>

            <div class="empty-state-text">
                {{ $title }}
            </div>

            <div class="empty-state-subtext">
                {{ $subtitle }}
            </div>
        </div>
    </td>
</tr>