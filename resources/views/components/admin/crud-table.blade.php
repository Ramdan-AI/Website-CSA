<div class="table-card">
    <div class="table-responsive">
        <table class="custom-table">
            @isset($head)
                <thead>
                    {{ $head }}
                </thead>
            @endisset

            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($pagination) && $pagination->hasPages())
        <div class="pagination-wrapper d-flex justify-content-end">
            {{ $pagination->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>