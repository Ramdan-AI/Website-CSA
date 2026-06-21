@if (session('success'))
    <div id="csa-sonner-toast" class="sonner-toast" role="alert">
        <div class="toast-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
            <p class="toast-title">Berhasil</p>
            <p class="toast-description">{{ session('success') }}</p>
        </div>
    </div>
@endif