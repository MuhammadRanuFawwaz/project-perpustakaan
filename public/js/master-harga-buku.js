const hargaModal = document.getElementById("hargaModal");
const hargaForm = document.getElementById("hargaForm");
const hargaModalTitle = document.getElementById("hargaModalTitle");
const hargaMethodField = document.getElementById("hargaMethodField");

function initSelect2HargaBuku() {
    if (!window.jQuery || !$.fn.select2) {
        return;
    }

    const selectBuku = $("#kode_buku_harga");

    if (selectBuku.hasClass("select2-hidden-accessible")) {
        selectBuku.select2("destroy");
    }

    selectBuku.select2({
        dropdownParent: $("#hargaModal"),
        width: "100%",
        placeholder: "-- Pilih Buku --",
        allowClear: true,
    });
}

function openHargaModal() {
    hargaModal.style.display = "flex";

    hargaModalTitle.innerText = "Tambah Harga Buku";

    hargaForm.action = "/master/harga-buku";

    hargaMethodField.innerHTML = "";

    document.getElementById("kode_buku_harga").disabled = false;
    document.getElementById("harga").value = "";

    initSelect2HargaBuku();

    $("#kode_buku_harga").val("").trigger("change");
}

function openEditHargaModal(button) {
    hargaModal.style.display = "flex";

    hargaModalTitle.innerText = "Edit Harga Buku";

    hargaForm.action = `/master/harga-buku/${button.dataset.id}`;

    hargaMethodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    document.getElementById("kode_buku_harga").disabled = false;
    document.getElementById("harga").value = button.dataset.harga;

    initSelect2HargaBuku();

    $("#kode_buku_harga").val(button.dataset.kode).trigger("change");
}

function closeHargaModal() {
    hargaModal.style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target === hargaModal) {
        closeHargaModal();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    initSelect2HargaBuku();
});
