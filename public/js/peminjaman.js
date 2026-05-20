const modal = document.getElementById("peminjamanModal");
const form = document.getElementById("peminjamanForm");
const modalTitle = document.getElementById("modalTitle");
const methodField = document.getElementById("methodField");

let selectedBooks = [];

function renderSelectedBooks() {
    const list = document.getElementById("selectedBookList");

    if (!list) {
        return;
    }

    list.innerHTML = "";

    if (selectedBooks.length === 0) {
        list.innerHTML = `<div class="empty-book">Belum ada buku dipilih</div>`;
        return;
    }

    selectedBooks.forEach((book) => {
        const item = document.createElement("div");
        item.className = "selected-book-item";

        item.innerHTML = `
            <div>
                <strong>${book.title}</strong>
                <small>Stok: ${book.stock}</small>
                <input type="hidden" name="kode_buku[]" value="${book.code}">
            </div>

            <button type="button" class="remove-book-btn" onclick="hapusBukuDipinjam('${book.code}')">
                Hapus
            </button>
        `;

        list.appendChild(item);
    });
}

function tambahBukuDipinjam() {
    const select = document.getElementById("pilih_buku");
    const selectedOption = select.options[select.selectedIndex];

    if (!select.value) {
        alert("Pilih buku dulu.");
        return;
    }

    const code = select.value;
    const title = selectedOption.dataset.title;
    const stock = selectedOption.dataset.stock;

    const alreadyExists = selectedBooks.some((book) => book.code === code);

    if (alreadyExists) {
        alert("Buku ini sudah dipilih.");
        return;
    }

    selectedBooks.push({
        code: code,
        title: title,
        stock: stock,
    });

    renderSelectedBooks();

    if (window.jQuery) {
        $("#pilih_buku").val("").trigger("change");
    } else {
        select.value = "";
    }
}

function hapusBukuDipinjam(code) {
    selectedBooks = selectedBooks.filter((book) => book.code !== code);
    renderSelectedBooks();
}

function openTambahModal() {
    modal.style.display = "flex";

    modalTitle.innerText = "Tambah Peminjaman";
    form.action = "/peminjaman";

    methodField.innerHTML = "";

    document.getElementById("id_pengunjung").value = "";
    document.getElementById("tanggal_peminjaman").value = new Date()
        .toISOString()
        .split("T")[0];

    const batasDate = new Date();
    batasDate.setDate(batasDate.getDate() + 7);

    document.getElementById("batas_pengembalian").value = batasDate
        .toISOString()
        .split("T")[0];

    const bukuGroup = document.getElementById("bukuGroup");

    bukuGroup.style.display = "block";

    selectedBooks = [];
    renderSelectedBooks();

    if (window.jQuery) {
        $("#id_pengunjung").val("").trigger("change");
        $("#pilih_buku").val("").trigger("change");
    }
}

function openEditModalFromButton(button) {
    modal.style.display = "flex";

    modalTitle.innerText = "Edit Peminjaman";

    const id = button.dataset.id;
    const pengunjung = button.dataset.pengunjung;
    const tanggal = button.dataset.tanggal;
    const batas = button.dataset.batas;

    form.action = "/peminjaman/" + id;

    methodField.innerHTML = `
        <input type="hidden" name="_method" value="PUT">
    `;

    document.getElementById("id_pengunjung").value = pengunjung;
    document.getElementById("tanggal_peminjaman").value = tanggal;
    document.getElementById("batas_pengembalian").value = batas;

    const bukuGroup = document.getElementById("bukuGroup");

    bukuGroup.style.display = "none";

    selectedBooks = [];
    renderSelectedBooks();

    if (window.jQuery) {
        $("#id_pengunjung").val(pengunjung).trigger("change");
    }
}

function closeModal() {
    modal.style.display = "none";
}

function openDetailModal(id) {
    document.getElementById(id).style.display = "flex";
}

function closeDetailModal(id) {
    document.getElementById(id).style.display = "none";
}

window.onclick = function (event) {
    if (event.target === modal) {
        closeModal();
    }
};

$(document).ready(function () {
    $(".select2-filter").select2({
        width: "100%",
    });

    $("#id_pengunjung").select2({
        dropdownParent: $("#peminjamanModal"),
        width: "100%",
    });

    $("#pilih_buku").select2({
        dropdownParent: $("#peminjamanModal"),
        width: "100%",
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const openDetail = params.get("open_detail");

    if (openDetail) {
        const modalId = "detailModal" + openDetail;
        const detailModal = document.getElementById(modalId);

        if (detailModal) {
            detailModal.style.display = "flex";
        }
    }
});
