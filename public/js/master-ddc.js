const ddcModal = document.getElementById("ddcModal");
const ddcForm = document.getElementById("ddcForm");
const ddcModalTitle = document.getElementById("ddcModalTitle");
const ddcMethodField = document.getElementById("ddcMethodField");

function openDdcModal() {
    ddcModal.style.display = "flex";

    ddcModalTitle.innerText = "Tambah DDC";

    ddcForm.action = "/master/ddc";

    ddcMethodField.innerHTML = "";

    document.getElementById("kode_ddc_input").value = "";
    document.getElementById("nama_ddc").value = "";
}

function openEditDdcModal(button) {
    ddcModal.style.display = "flex";

    ddcModalTitle.innerText = "Edit DDC";

    ddcForm.action = `/master/ddc/${button.dataset.id}`;

    ddcMethodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    document.getElementById("kode_ddc_input").value = button.dataset.kode;
    document.getElementById("nama_ddc").value = button.dataset.nama;
}

function closeDdcModal() {
    ddcModal.style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target == ddcModal) {
        closeDdcModal();
    }
});
