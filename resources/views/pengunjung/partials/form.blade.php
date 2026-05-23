<div class="form-group">

    <label>NIS / NIP</label>

    <div class="lookup-row">

        <input type="text"
            name="nomor_induk"
            id="nomor_induk"
            placeholder="Masukkan NIS atau NIP"
            required>

        <button type="button"
            class="lookup-btn"
            onclick="cekNomorInduk()">

            Cek

        </button>

    </div>

    <small id="lookup-message"></small>

</div>

<div class="form-group">

    <label>Status</label>

    <input type="text"
        name="jenis_pengunjung"
        id="jenis_pengunjung"
        placeholder="Status otomatis"
        readonly>

</div>

<div class="form-group">

    <label>Nama Pengunjung</label>

    <input type="text"
        name="nama_pengunjung"
        id="nama_pengunjung"
        placeholder="Nama otomatis setelah NIS/NIP dicek"
        readonly>

</div>

<input type="hidden"
    name="id_kelas"
    id="id_kelas">

<div class="form-group" id="kelas-group">

    <label>Kelas</label>

    <input type="text"
        id="nama_kelas"
        placeholder="Kelas otomatis"
        readonly>

</div>

<div class="form-group" id="jurusan-group">

    <label>Jurusan</label>

    <input type="text"
        id="jurusan"
        placeholder="Jurusan otomatis"
        readonly>

</div>

<div class="form-group">

    <label>Tanggal Kunjung</label>

    <input type="text"
        value="Otomatis oleh sistem"
        readonly>

</div>

<div class="form-group">

    <label>Waktu Kunjung</label>

    <input type="text"
        value="Otomatis oleh sistem"
        readonly>

</div>

<div class="form-group">

    <label>Keperluan</label>

    <input type="text"
        name="keperluan"
        id="keperluan"
        placeholder="Masukkan keperluan"
        required>

</div>