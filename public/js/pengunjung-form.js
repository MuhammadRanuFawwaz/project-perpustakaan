document.getElementById("btnCek").addEventListener("click", async () => {
    const nomorIndukInput = document.querySelector('[name="nomor_induk"]');
    const nomorInduk = nomorIndukInput.value.trim();

    if (!nomorInduk) {
        window.alert("Masukkan NIS / NIP");
        return;
    }

    try {
        const response = await fetch(
            `/pengunjung/lookup?nomor_induk=${encodeURIComponent(nomorInduk)}`,
        );
        const result = await response.json();

        if (!response.ok || !result.status) {
            window.alert(result.message || "NIS / NIP tidak ditemukan");
            return;
        }

        document.getElementById("status").value =
            result.data.jenis_pengunjung || "";
        document.getElementById("nama_pengunjung").value =
            result.data.nama_pengunjung || "";
        document.getElementById("kelas").value = result.data.nama_kelas || "-";
        document.getElementById("jurusan").value = result.data.jurusan || "-";
    } catch (error) {
        window.alert("Terjadi kesalahan saat mengecek data");
        console.error(error);
    }
});

const successAlert = document.getElementById("success-alert");

if (successAlert) {
    setTimeout(() => {
        successAlert.style.transition = "0.5s";
        successAlert.style.opacity = "0";

        setTimeout(() => {
            successAlert.remove();
        }, 500);
    }, 3000);
}
