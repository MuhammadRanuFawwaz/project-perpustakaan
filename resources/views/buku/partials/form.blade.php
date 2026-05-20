<div class="form-group">

    <label>Judul Buku</label>

    <input type="text"
        name="judul_buku"
        id="judul_buku"
        placeholder="Masukkan judul buku"
        required>

</div>

<div class="form-group">

    <label>Kategori / Bidang Studi</label>

    <select name="id_kategori"
        id="id_kategori"
        class="select2-modal"
        required>

        <option value="">
            -- Pilih Kategori --
        </option>

        @foreach($kategori as $k)

        <option value="{{ $k->id }}">
            {{ $k->nama_kategori }}
        </option>

        @endforeach

    </select>

</div>

<div class="form-group">

    <label>Jenjang Kelas</label>

    <select name="jenjang_kelas"
        id="jenjang_kelas"
        class="select2-modal">

        <option value="">
            -- Pilih Jenjang --
        </option>

        <option value="X">X</option>
        <option value="XI">XI</option>
        <option value="XII">XII</option>
        <option value="Umum">Umum / Novel / Tidak Ada Kelas</option>

    </select>

</div>

<div class="form-group">

    <label>Kode DDC</label>

    <select name="kode_ddc"
        id="kode_ddc"
        class="select2-modal">

        <option value="">
            -- Pilih Kode DDC --
        </option>

        <option value="000">000 - Karya Umum / Komputer</option>
        <option value="100">100 - Filsafat dan Psikologi</option>
        <option value="200">200 - Agama</option>
        <option value="300">300 - Ilmu Sosial</option>
        <option value="400">400 - Bahasa</option>
        <option value="500">500 - Ilmu Pengetahuan Alam</option>
        <option value="600">600 - Teknologi dan Ilmu Terapan</option>
        <option value="700">700 - Seni dan Olahraga</option>
        <option value="800">800 - Sastra</option>
        <option value="900">900 - Geografi dan Sejarah</option>

    </select>

</div>

<div class="form-group">

    <label>Stok / Jumlah Buku</label>

    <input type="number"
        name="stok"
        id="stok"
        placeholder="Masukkan jumlah buku"
        min="0"
        required>

</div>

<div class="form-group">

    <label>Tanggal Kirim</label>

    <input type="date"
        name="tanggal_kirim"
        id="tanggal_kirim"
        value="{{ date('Y-m-d') }}"
        required>

</div>