const kategoriModal = document.getElementById("kategoriModal");
const kategoriForm = document.getElementById("kategoriForm");
const kategoriModalTitle = document.getElementById("kategoriModalTitle");
const kategoriMethodField = document.getElementById("kategoriMethodField");

function openKategoriModal() {
    kategoriModal.style.display = "flex";

    kategoriModalTitle.innerText = "Tambah Kategori";

    kategoriForm.action = "/master/kategori";

    kategoriMethodField.innerHTML = "";

    document.getElementById("nama_kategori").value = "";
}

function openEditKategoriModal(button) {
    kategoriModal.style.display = "flex";

    kategoriModalTitle.innerText = "Edit Kategori";

    kategoriForm.action = `/master/kategori/${button.dataset.id}`;

    kategoriMethodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    document.getElementById("nama_kategori").value = button.dataset.nama;
}

function closeKategoriModal() {
    kategoriModal.style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target == kategoriModal) {
        closeKategoriModal();
    }
});
