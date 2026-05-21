$(document).ready(function () {
    $(".select2-filter").select2({
        width: "100%",
    });

    $(".select2-modal").select2({
        width: "100%",
        dropdownParent: $("#pengunjungModal"),
    });

    const jenis = document.getElementById("jenis_pengunjung");
    const kelasGroup = document.getElementById("kelas-group");
    const jurusanGroup = document.getElementById("jurusan-group");
    const kelas = document.getElementById("id_kelas");
    const jurusan = document.getElementById("jurusan");

    function toggleField() {
        if (jenis.value === "Guru") {
            kelasGroup.style.display = "none";
            jurusanGroup.style.display = "none";

            $("#id_kelas").val("").trigger("change");
            $("#jurusan").val("");
        } else {
            kelasGroup.style.display = "block";
            jurusanGroup.style.display = "block";
        }
    }

    function updateJurusan() {
        const selected = kelas.options[kelas.selectedIndex];
        jurusan.value = selected?.dataset?.jurusan || "";
    }

    $("#jenis_pengunjung").on("change", function () {
        toggleField();
    });

    $("#id_kelas").on("change", function () {
        updateJurusan();
    });

    toggleField();
    updateJurusan();
});

const modal = document.getElementById("pengunjungModal");
const form = document.getElementById("pengunjungForm");
const modalTitle = document.getElementById("modalTitle");
const methodField = document.getElementById("methodField");

function openTambahModal() {
    $(".select2-filter").select2("close");
    $(".select2-modal").select2("close");

    modal.style.display = "flex";

    modalTitle.innerText = "Tambah Pengunjung";
    form.action = "/pengunjung";
    methodField.innerHTML = "";

    form.reset();

    $("#jenis_pengunjung").val("").trigger("change");
    $("#id_kelas").val("").trigger("change");
    $("#jurusan").val("");

    const now = new Date();
    const hours = String(now.getHours()).padStart(2, "0");
    const minutes = String(now.getMinutes()).padStart(2, "0");

    $("#waktu_kunjung").val(`${hours}:${minutes}`);
}

function openEditModal(id, nama, jenis, kelas, tanggal, waktu, keperluan) {
    modal.style.display = "flex";

    modalTitle.innerText = "Edit Pengunjung";
    form.action = `/pengunjung/${id}`;
    methodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

    $("#nama_pengunjung").val(nama);
    $("#jenis_pengunjung").val(jenis).trigger("change");
    $("#id_kelas").val(kelas).trigger("change");
    $("#tanggal_kunjung").val(tanggal);
    $("#waktu_kunjung").val(waktu.substring(0, 5));
    $("#keperluan").val(keperluan);
}

function closeModal() {
    modal.style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target == modal) {
        closeModal();
    }
});
