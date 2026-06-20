@props([
    'editRoute' => null,
    'deleteRoute' => null
])

<div class="action-group justify-content-end">

    @if($editRoute)
        <a href="{{ $editRoute }}" class="btn-icon edit" title="Edit Data">
            <i class="fas fa-pen"></i>
        </a>
    @endif

    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn-icon delete trigger-delete" title="Hapus Data">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif

</div>