document.querySelectorAll(".stat-fill").forEach(function (bar) {
    bar.style.width = bar.dataset.width + "%";
});

/* =========================
   TAB STATISTIK
========================= */

const btnStatPengunjung = document.getElementById("btnStatPengunjung");
const btnStatBuku = document.getElementById("btnStatBuku");

const statistikPengunjung = document.getElementById("statistikPengunjung");
const statistikBuku = document.getElementById("statistikBuku");

if (btnStatPengunjung && btnStatBuku && statistikPengunjung && statistikBuku) {
    btnStatPengunjung.addEventListener("click", () => {
        statistikPengunjung.style.display = "block";
        statistikBuku.style.display = "none";

        btnStatPengunjung.classList.add("active");
        btnStatBuku.classList.remove("active");
    });

    btnStatBuku.addEventListener("click", () => {
        statistikPengunjung.style.display = "none";
        statistikBuku.style.display = "block";

        btnStatBuku.classList.add("active");
        btnStatPengunjung.classList.remove("active");
    });
}

/* =========================
   TAB JATUH TEMPO
========================= */

const btnTerlambat = document.getElementById("btnTerlambat");
const btnAkanJatuhTempo = document.getElementById("btnAkanJatuhTempo");

const dataTerlambat = document.getElementById("dataTerlambat");
const dataAkanJatuhTempo = document.getElementById("dataAkanJatuhTempo");

if (btnTerlambat && btnAkanJatuhTempo && dataTerlambat && dataAkanJatuhTempo) {
    btnTerlambat.addEventListener("click", () => {
        dataTerlambat.style.display = "block";
        dataAkanJatuhTempo.style.display = "none";

        btnTerlambat.classList.add("active");
        btnAkanJatuhTempo.classList.remove("active");
    });

    btnAkanJatuhTempo.addEventListener("click", () => {
        dataTerlambat.style.display = "none";
        dataAkanJatuhTempo.style.display = "block";

        btnAkanJatuhTempo.classList.add("active");
        btnTerlambat.classList.remove("active");
    });
}

/* =========================
   TAB AKTIVITAS TERAKHIR
========================= */

const btnPengunjung = document.getElementById("btnPengunjung");
const btnBuku = document.getElementById("btnBuku");
const btnPeminjaman = document.getElementById("btnPeminjaman");

const aktivitasPengunjung = document.getElementById("aktivitasPengunjung");
const aktivitasBuku = document.getElementById("aktivitasBuku");
const aktivitasPeminjaman = document.getElementById("aktivitasPeminjaman");

if (
    btnPengunjung &&
    btnBuku &&
    btnPeminjaman &&
    aktivitasPengunjung &&
    aktivitasBuku &&
    aktivitasPeminjaman
) {
    function resetAktivitasTab() {
        btnPengunjung.classList.remove("active");
        btnBuku.classList.remove("active");
        btnPeminjaman.classList.remove("active");

        aktivitasPengunjung.style.display = "none";
        aktivitasBuku.style.display = "none";
        aktivitasPeminjaman.style.display = "none";
    }

    btnPengunjung.addEventListener("click", () => {
        resetAktivitasTab();

        btnPengunjung.classList.add("active");
        aktivitasPengunjung.style.display = "block";
    });

    btnBuku.addEventListener("click", () => {
        resetAktivitasTab();

        btnBuku.classList.add("active");
        aktivitasBuku.style.display = "block";
    });

    btnPeminjaman.addEventListener("click", () => {
        resetAktivitasTab();

        btnPeminjaman.classList.add("active");
        aktivitasPeminjaman.style.display = "block";
    });
}
