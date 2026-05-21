<div class="form-group">
    <label>Nama Peminjam</label>

    <select name="id_pengunjung" id="id_pengunjung" class="select2-modal" required>
        <option value="">-- Pilih Peminjam --</option>

        @foreach ($pengunjung as $item)
        <option value="{{ $item->id }}">
            {{ $item->nama_pengunjung }}
            -
            {{ $item->jenis_pengunjung }}

            @if ($item->kelas)
            -
            {{ $item->kelas->nama_kelas }}
            {{ $item->kelas->jurusan }}
            @endif
        </option>
        @endforeach
    </select>
</div>

<div class="form-group" id="bukuGroup">
    <label>Buku Dipinjam</label>

    <div class="book-picker-row">
        <select id="pilih_buku" class="select2-modal">
            <option value="">-- Pilih Buku --</option>

            @foreach ($buku as $item)
            <option value="{{ $item->kode_buku }}"
                data-title="{{ $item->judul_buku }}"
                data-stock="{{ $item->stok }}"
                data-jenjang="{{ $item->jenjang_kelas }}"
                {{ $item->stok <= 0 ? 'disabled' : '' }}>

                [{{ $item->jenjang_kelas }}]
                {{ $item->judul_buku }}
                - Stok: {{ $item->stok }}

            </option>
            @endforeach
        </select>

        <button type="button" class="add-book-btn" onclick="tambahBukuDipinjam()">
            Tambah Buku
        </button>
    </div>

    <div id="selectedBookList" class="selected-book-list">
        <div class="empty-book">Belum ada buku dipilih</div>
    </div>
</div>

<div class="form-group">
    <label>Tanggal Peminjaman</label>

    <input type="date"
        name="tanggal_peminjaman"
        id="tanggal_peminjaman"
        required>
</div>

<div class="form-group">
    <label>Batas Pengembalian</label>

    <input type="date"
        name="batas_pengembalian"
        id="batas_pengembalian"
        required>
</div>