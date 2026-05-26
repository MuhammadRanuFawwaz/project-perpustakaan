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

    <label>Kode DDC</label>

    <input type="text"
        name="kode_ddc"
        id="kode_ddc"
        placeholder="Contoh: 420 / 510 / 297"
        required>

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