$(document).ready(function () {
    $(".select2-filter").select2({
        width: "100%",
    });

    toggleKelasJurusan();
});

const modal = document.getElementById("pengunjungModal");
const form = document.getElementById("pengunjungForm");
const modalTitle = document.getElementById("modalTitle");
const methodField = document.getElementById("methodField");

function toggleKelasJurusan() {
    const jenis = $("#jenis_pengunjung").val();

    const kelasGroup = document.getElementById("kelas-group");
    const jurusanGroup = document.getElementById("jurusan-group");

    if (jenis === "Guru") {
        kelasGroup.style.display = "none";
        jurusanGroup.style.display = "none";
    } else {
        kelasGroup.style.display = "block";
        jurusanGroup.style.display = "block";
    }
}

function resetIdentitas() {
    $("#nomor_induk").val("");
    $("#jenis_pengunjung").val("");
    $("#nama_pengunjung").val("");
    $("#id_kelas").val("");
    $("#nama_kelas").val("");
    $("#jurusan").val("");

    $("#lookup-message").text("").removeClass("success error");
}

function cekNomorInduk() {
    const nomorInduk = $("#nomor_induk").val();

    if (!nomorInduk) {
        $("#lookup-message")
            .text("Masukkan NIS atau NIP dulu.")
            .removeClass("success")
            .addClass("error");

        return;
    }

    $.ajax({
        url: "/pengunjung/lookup",
        method: "GET",
        data: {
            nomor_induk: nomorInduk,
        },

        success: function (response) {
            const data = response.data;

            $("#jenis_pengunjung").val(data.jenis_pengunjung);
            $("#nama_pengunjung").val(data.nama_pengunjung);
            $("#id_kelas").val(data.id_kelas);
            $("#nama_kelas").val(data.nama_kelas);
            $("#jurusan").val(data.jurusan);

            $("#lookup-message")
                .text("Data ditemukan.")
                .removeClass("error")
                .addClass("success");

            toggleKelasJurusan();
        },

        error: function (xhr) {
            $("#jenis_pengunjung").val("");
            $("#nama_pengunjung").val("");
            $("#id_kelas").val("");
            $("#nama_kelas").val("");
            $("#jurusan").val("");

            const message =
                xhr.responseJSON?.message || "Data tidak ditemukan.";

            $("#lookup-message")
                .text(message)
                .removeClass("success")
                .addClass("error");
        },
    });
}

function openTambahModal() {
    $(".select2-filter").select2("close");

    modal.style.display = "flex";

    modalTitle.innerText = "Tambah Pengunjung";
    form.action = "/pengunjung";
    methodField.innerHTML = "";

    form.reset();

    resetIdentitas();
}

function openEditModal(
    id,
    nomorInduk,
    nama,
    jenis,
    kelas,
    namaKelas,
    jurusan,
    keperluan,
) {
    modal.style.display = "flex";

    modalTitle.innerText = "Edit Pengunjung";
    form.action = `/pengunjung/${id}`;
    methodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    $("#jenis_pengunjung").val(jenis).trigger("change");
    $("#nomor_induk").val(nomorInduk);
    $("#nama_pengunjung").val(nama);
    $("#id_kelas").val(kelas);
    $("#nama_kelas").val(namaKelas);
    $("#jurusan").val(jurusan);
    $("#keperluan").val(keperluan);

    $("#lookup-message").text("").removeClass("success error");

    toggleKelasJurusan();
}

function closeModal() {
    modal.style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target == modal) {
        closeModal();
    }
});
