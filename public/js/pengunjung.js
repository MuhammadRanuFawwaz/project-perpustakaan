$(document).ready(function () {
    /* SELECT2 */

    $("#id_kelas").select2({
        dropdownParent: $("#pengunjungModal"),
        width: "100%",
    });

    $("#jenis_pengunjung").select2({
        dropdownParent: $("#pengunjungModal"),
        width: "100%",
    });

    /* ELEMENT */

    const jenis = document.getElementById("jenis_pengunjung");

    const kelasGroup = document.getElementById("kelas-group");

    const jurusanGroup = document.getElementById("jurusan-group");

    const kelas = document.getElementById("id_kelas");

    const jurusan = document.getElementById("jurusan");

    /* TOGGLE FIELD */

    function toggleField() {
        if (jenis.value === "Guru") {
            kelasGroup.style.display = "none";

            jurusanGroup.style.display = "none";
        } else {
            kelasGroup.style.display = "block";

            jurusanGroup.style.display = "block";
        }
    }

    /* UPDATE JURUSAN */

    function updateJurusan() {
        const selected = kelas.options[kelas.selectedIndex];

        jurusan.value = selected?.dataset?.jurusan || "";
    }

    /* EVENT */

    $("#jenis_pengunjung").on("change", function () {
        toggleField();
    });

    $("#id_kelas").on("change", function () {
        updateJurusan();
    });

    /* INIT */

    toggleField();

    updateJurusan();
});

/* =========================
    MODAL
    ========================= */

const modal = document.getElementById("pengunjungModal");

const form = document.getElementById("pengunjungForm");

const modalTitle = document.getElementById("modalTitle");

const methodField = document.getElementById("methodField");

/* TAMBAH */

function openTambahModal() {
    modal.style.display = "flex";

    modalTitle.innerText = "Tambah Pengunjung";

    form.action = "/pengunjung";

    methodField.innerHTML = "";

    form.reset();

    $("#jenis_pengunjung").val("").trigger("change");

    $("#id_kelas").val("").trigger("change");

    $("#jurusan").val("");

    /* AUTO JAM SEKARANG */

    const now = new Date();

    const hours = String(now.getHours()).padStart(2, "0");

    const minutes = String(now.getMinutes()).padStart(2, "0");

    $("#waktu_kunjung").val(`${hours}:${minutes}`);
}

/* EDIT */

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

/* CLOSE */

function closeModal() {
    modal.style.display = "none";
}

/* KLIK LUAR */

window.addEventListener("click", function (e) {
    if (e.target == modal) {
        closeModal();
    }
});
