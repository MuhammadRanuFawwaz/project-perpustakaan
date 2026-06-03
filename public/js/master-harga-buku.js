const hargaModal = document.getElementById("hargaModal");
const hargaForm = document.getElementById("hargaForm");
const hargaModalTitle = document.getElementById("hargaModalTitle");
const hargaMethodField = document.getElementById("hargaMethodField");

function openHargaModal() {
    hargaModal.style.display = "flex";

    hargaModalTitle.innerText = "Tambah Harga Buku";

    hargaForm.action = "/master/harga-buku";

    hargaMethodField.innerHTML = "";

    document.getElementById("kode_buku_harga").value = "";
    document.getElementById("kode_buku_harga").disabled = false;
    document.getElementById("harga").value = "";
}

function openEditHargaModal(button) {
    hargaModal.style.display = "flex";

    hargaModalTitle.innerText = "Edit Harga Buku";

    hargaForm.action = `/master/harga-buku/${button.dataset.id}`;

    hargaMethodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    document.getElementById("kode_buku_harga").value = button.dataset.kode;
    document.getElementById("kode_buku_harga").disabled = false;
    document.getElementById("harga").value = button.dataset.harga;
}

function closeHargaModal() {
    hargaModal.style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target == hargaModal) {
        closeHargaModal();
    }
});
