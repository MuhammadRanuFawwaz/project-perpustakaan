document.getElementById('btnCek').addEventListener('click', async () => {

    const nomorInduk = document.querySelector('[name=\"nomor_induk\"]').value;

    if (!nomorInduk) {
        alert('Masukkan NIS / NIP');
        return;
    }

    try {

        const response = await fetch(`/pengunjung/lookup?nomor_induk=${nomorInduk}`);

        const result = await response.json();

        if (!result.status) {
            alert(result.message);
            return;
        }

        document.getElementById('status').value =
            result.data.jenis_pengunjung;

        document.getElementById('nama_pengunjung').value =
            result.data.nama_pengunjung;

        document.getElementById('kelas').value =
            result.data.nama_kelas;

        document.getElementById('jurusan').value =
            result.data.jurusan;

    } catch (error) {
        alert('Terjadi kesalahan');
        console.log(error);
    }

});
const alert = document.getElementById('success-alert');

if (alert) {
    setTimeout(() => {
        alert.style.transition = '0.5s';
        alert.style.opacity = '0';

        setTimeout(() => {
            alert.remove();
        }, 500);

    }, 3000);
}
const alertBox = document.getElementById('success-alert');

if (alertBox) {
    setTimeout(() => {
        alertBox.classList.add('hide');
    }, 3000);
}