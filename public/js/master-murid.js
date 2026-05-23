const muridModal = document.getElementById("muridModal");
const importModal = document.getElementById("importModal");

$(document).ready(function () {
    $(".select2-filter").select2({
        width: "100%",
    });

    $(".select2-modal").select2({
        width: "100%",
        dropdownParent: $("#muridModal"),
    });

    if ($("#edit-mode").val() === "true") {
        muridModal.style.display = "flex";
    }
});

function openMuridModal() {
    muridModal.style.display = "flex";
}

function closeMuridModal() {
    window.location.href = "/master/murid";
}

function openImportModal() {
    importModal.style.display = "flex";
}

function closeImportModal() {
    importModal.style.display = "none";
}

window.addEventListener("click", function (event) {
    if (event.target === muridModal) {
        closeMuridModal();
    }

    if (event.target === importModal) {
        closeImportModal();
    }
});
