document.addEventListener('DOMContentLoaded', function () {

    const jenis = document.getElementById('jenis_pengunjung');

    const kelasGroup = document.getElementById('kelas-group');

    const jurusanGroup = document.getElementById('jurusan-group');

    const kelas = document.getElementById('id_kelas');

    const jurusan = document.getElementById('jurusan');

    function toggleField() {

        if (jenis.value === 'Guru') {

            kelasGroup.style.display = 'none';

            jurusanGroup.style.display = 'none';

        } else {

            kelasGroup.style.display = 'block';

            jurusanGroup.style.display = 'block';
        }
    }

    function updateJurusan() {

        const selected = kelas.options[kelas.selectedIndex];

        jurusan.value = selected.dataset.jurusan || '';
    }

    jenis.addEventListener('change', toggleField);

    kelas.addEventListener('change', updateJurusan);

    toggleField();

    updateJurusan();
});