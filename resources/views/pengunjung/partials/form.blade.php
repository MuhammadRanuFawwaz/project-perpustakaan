<div class="form-group">

    <label>Nama Pengunjung</label>

    <input type="text"
        name="nama_pengunjung"
        value="{{ old('nama_pengunjung', $pengunjung->nama_pengunjung ?? '') }}"
        required>

</div>

<div class="form-group">

<<<<<<< HEAD
    <label>Jenis Pengunjung</label>

    <select name="jenis_pengunjung"
        id="jenis_pengunjung"
        required>

        <option value="">
            -- Pilih Jenis --
        </option>

        <option value="Murid"
            {{ old('jenis_pengunjung', $pengunjung->jenis_pengunjung ?? '') == 'Murid' ? 'selected' : '' }}>
            Murid
        </option>

        <option value="Guru"
            {{ old('jenis_pengunjung', $pengunjung->jenis_pengunjung ?? '') == 'Guru' ? 'selected' : '' }}>
            Guru
        </option>

    </select>

</div>

<div class="form-group"
    id="kelas-group">

    <label>Pilih Kelas</label>

    <select name="id_kelas"
        id="id_kelas">
=======
    <label>Pilih Kelas</label>

    <select name="id_kelas" required>
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36

        <option value="">
            -- Pilih --
        </option>

        @foreach($kelas as $k)

        <option value="{{ $k->id }}"
<<<<<<< HEAD
            data-jurusan="{{ $k->jurusan }}"
            {{ old('id_kelas', $pengunjung->id_kelas ?? '') == $k->id ? 'selected' : '' }}>

            {{ $k->nama_kelas }} - {{ $k->jurusan }}
=======
            {{ old('id_kelas', $pengunjung->id_kelas ?? '') == $k->id ? 'selected' : '' }}>

            {{ $k->nama_kelas }}
            -
            {{ $k->jurusan }}
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36

        </option>

        @endforeach

    </select>

</div>

<<<<<<< HEAD
<div class="form-group"
    id="jurusan-group">

    <label>Jurusan</label>

    <input type="text"
        id="jurusan"
        readonly>

</div>

=======
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
<div class="form-group">

    <label>Tanggal Kunjung</label>

    <input type="date"
        name="tanggal_kunjung"
        value="{{ date('Y-m-d') }}">

</div>

<div class="form-group">

    <label>Waktu Kunjung</label>

    <input type="time"
        name="waktu_kunjung"
        value="{{ date('H:i') }}">

</div>

<div class="form-group">

    <label>Keperluan</label>

    <input type="text"
        name="keperluan"
        value="{{ old('keperluan', $pengunjung->keperluan ?? '') }}"
        required>

<<<<<<< HEAD
</div>

<script src="{{ asset('js/pengunjung.js') }}"></script>
=======
</div>
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
