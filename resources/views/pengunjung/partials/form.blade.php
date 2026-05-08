<div class="form-group">

    <label>Nama Pengunjung</label>

    <input type="text"
        name="nama_pengunjung"
        value="{{ old('nama_pengunjung', $pengunjung->nama_pengunjung ?? '') }}"
        required>

</div>

<div class="form-group">

    <label>Pilih Kelas</label>

    <select name="id_kelas" required>

        <option value="">
            -- Pilih --
        </option>

        @foreach($kelas as $k)

        <option value="{{ $k->id }}"
            {{ old('id_kelas', $pengunjung->id_kelas ?? '') == $k->id ? 'selected' : '' }}>

            {{ $k->nama_kelas }}
            -
            {{ $k->jurusan }}

        </option>

        @endforeach

    </select>

</div>

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

</div>