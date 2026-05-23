const guruModal = document.getElementById("guruModal");
const importGuruModal = document.getElementById("importGuruModal");

document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("edit-mode").value === "true") {
        guruModal.style.display = "flex";
    }
});

function openGuruModal() {
    guruModal.style.display = "flex";
}

function closeGuruModal() {
    window.location.href = "/master/guru";
}

function openImportGuruModal() {
    importGuruModal.style.display = "flex";
}

function closeImportGuruModal() {
    importGuruModal.style.display = "none";
}

window.addEventListener("click", function (event) {
    if (event.target === guruModal) {
        closeGuruModal();
    }

    if (event.target === importGuruModal) {
        closeImportGuruModal();
    }
});
