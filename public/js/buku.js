const modal = document.getElementById("bukuModal");

const form = document.getElementById("bukuForm");

const modalTitle = document.getElementById("modalTitle");

const methodField = document.getElementById("methodField");

function initSelect2() {
    $(".select2").select2({
        width: "100%",
    });

    $(".select2-modal").select2({
        width: "100%",
        dropdownParent: $("#bukuModal"),
    });
}

function openTambahModal() {
    modal.style.display = "flex";

    modalTitle.innerText = "Tambah Buku";

    form.action = "/buku";

    methodField.innerHTML = "";

    form.reset();

    document.getElementById("tanggal_kirim").value = new Date()
        .toISOString()
        .split("T")[0];

    $("#id_kategori").val("").trigger("change");
    $("#jenjang_kelas").val("").trigger("change");
    $("#kode_ddc").val("").trigger("change");
}

function openEditModalFromButton(button) {
    modal.style.display = "flex";

    modalTitle.innerText = "Edit Buku";

    form.action = `/buku/${button.dataset.kode}`;

    methodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    document.getElementById("judul_buku").value = button.dataset.judul;

    document.getElementById("stok").value = button.dataset.stok;

    document.getElementById("tanggal_kirim").value = button.dataset.tanggal;

    $("#id_kategori").val(button.dataset.kategori).trigger("change");
    $("#jenjang_kelas").val(button.dataset.jenjang).trigger("change");
    $("#kode_ddc").val(button.dataset.ddc).trigger("change");
}

function closeModal() {
    modal.style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target == modal) {
        closeModal();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    initSelect2();
});
