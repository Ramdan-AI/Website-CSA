document.addEventListener("DOMContentLoaded", () => {
    // Logic Sonner Toast
    const toast = document.getElementById("csa-sonner-toast");
    if (toast) {
        setTimeout(() => {
            toast.classList.add("hide");
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // Logic Global Delete Modal
    const deleteModalElement = document.getElementById("globalDeleteModal");
    if (deleteModalElement) {
        const deleteModal = new bootstrap.Modal(deleteModalElement);
        const confirmBtn = document.getElementById("confirmDeleteBtn");
        let currentFormToSubmit = null;

        document.querySelectorAll(".trigger-delete").forEach((button) => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                currentFormToSubmit = this.closest("form");
                deleteModal.show();
            });
        });

        confirmBtn.addEventListener("click", function () {
            if (currentFormToSubmit) {
                this.innerHTML =
                    '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
                this.style.opacity = "0.7";
                this.style.pointerEvents = "none";
                currentFormToSubmit.submit();
            }
        });
    }
});
