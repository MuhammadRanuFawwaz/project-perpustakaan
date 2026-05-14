<div class="form-group">

    <label>Nama Pengunjung</label>

    <input type="text"
        name="nama_pengunjung"
        id="nama_pengunjung"
        placeholder="Masukkan nama pengunjung"
        required>

</div>

<div class="form-group">


    <label>Pengunjung</label>

    <label>Jenis Pengunjung</label>
    <select name="jenis_pengunjung"
        id="jenis_pengunjung"
        required>

        <option value="">
            -- Pilih Pengunjung --
        </option>

        <option value="Murid">Murid</option>
        <option value="Guru">Guru</option>

    </select>

</div>

<div class="form-group"
    id="kelas-group">

    <label>Pilih Kelas</label>

    <select name="id_kelas"
        id="id_kelas">

        <option value="">
            -- Pilih Kelas --
        </option>

        @foreach($kelas as $k)

        <option value="{{ $k->id }}"

            data-jurusan="{{ $k->jurusan }}">
      
            data-jurusan="{{ $k->jurusan }}"
            {{ old('id_kelas', $pengunjung->id_kelas ?? '') == $k->id ? 'selected' : '' }}>

            {{ $k->nama_kelas }} - {{ $k->jurusan }}

        </option>

        @endforeach

    </select>

</div>

<div class="form-group"
    id="jurusan-group">

    <label>Jurusan</label>

    <input type="text"
        id="jurusan"
        placeholder="Jurusan otomatis"
        readonly>

</div>

<div class="form-group">

    <label>Tanggal Kunjung</label>

    <input type="date"
        name="tanggal_kunjung"
        id="tanggal_kunjung"
        value="{{ date('Y-m-d') }}">

</div>

<!-- FIX UTAMA DI SINI -->
<div class="form-group">

    <label>Waktu Kunjung</label>

    <input type="time"
        name="waktu_kunjung"
        id="waktu_kunjung"
        step="60"
        value="">

</div>

<div class="form-group">

    <label>Keperluan</label>

    <input type="text"
        name="keperluan"
        id="keperluan"
        placeholder="Masukkan keperluan"
        required>


</div>

</div>

<script src="{{ asset('js/pengunjung.js') }}"></script>