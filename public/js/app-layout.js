document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("mainContent");

    if (toggleBtn && sidebar && mainContent) {
        toggleBtn.addEventListener("click", function () {
            sidebar.classList.toggle("sidebar-hide");
            mainContent.classList.toggle("main-full");
        });
    }

    const profileBtn = document.getElementById("profileBtn");
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (profileBtn && dropdownMenu) {
        profileBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show-dropdown");
        });

        window.addEventListener("click", function (e) {
            if (
                !profileBtn.contains(e.target) &&
                !dropdownMenu.contains(e.target)
            ) {
                dropdownMenu.classList.remove("show-dropdown");
            }
        });
    }

    window.openProfileModal = function () {
        const profileModal = document.getElementById("profileModal");

        if (profileModal) {
            profileModal.style.display = "flex";
        }

        if (dropdownMenu) {
            dropdownMenu.classList.remove("show-dropdown");
        }
    };

    window.closeProfileModal = function () {
        const profileModal = document.getElementById("profileModal");

        if (profileModal) {
            profileModal.style.display = "none";
        }
    };

    const profileModal = document.getElementById("profileModal");

    if (profileModal) {
        profileModal.addEventListener("click", function (e) {
            if (e.target === profileModal) {
                profileModal.style.display = "none";
            }
        });
    }
});
