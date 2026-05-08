document.addEventListener("DOMContentLoaded", function () {
    // ======================
    // SIDEBAR
    // ======================

    const toggleBtn = document.getElementById("toggleSidebar");

    const sidebar = document.getElementById("sidebar");

    const mainContent = document.getElementById("mainContent");

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function () {
            sidebar.classList.toggle("sidebar-hide");

            mainContent.classList.toggle("main-full");
        });
    }

    // ======================
    // PROFILE
    // ======================

    const profileBtn = document.getElementById("profileBtn");

    const dropdownMenu = document.getElementById("dropdownMenu");

    if (profileBtn) {
        profileBtn.addEventListener("click", function () {
            dropdownMenu.classList.toggle("show-dropdown");
        });
    }

    // ======================
    // MODAL
    // ======================

    const modal = document.getElementById("modalPengunjung");

    const openModal = document.getElementById("openModal");

    const closeModal = document.getElementById("closeModal");

    if (openModal) {
        openModal.addEventListener("click", function () {
            modal.style.display = "flex";
        });
    }

    if (closeModal) {
        closeModal.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }
});
