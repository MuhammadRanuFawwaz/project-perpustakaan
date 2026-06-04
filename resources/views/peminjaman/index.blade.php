<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/peminjaman.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="dashboard-container">

        @include('layouts.sidebar')

        <div class="main-content" id="mainContent">

            @include('layouts.topbar')

            <div class="content">

                <div class="filter-box">

                    <form method="GET" action="{{ route('peminjaman.index') }}">

                        <div class="filter-grid">

                            <div class="filter-group">
                                <label>Tanggal Awal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}">
                            </div>

                            <div class="filter-group">
                                <label>Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}">
                            </div>

                            <div class="filter-group">
                                <label>Kelas</label>

                                <select name="nama_kelas" class="select2-filter">
                                    <option value="">Semua Kelas</option>

                                    @foreach(['X-A','X-B','X-C','XI-A','XI-B','XI-C','XII-A','XII-B','XII-C'] as $kelasOption)
                                    <option value="{{ $kelasOption }}" {{ request('nama_kelas') == $kelasOption ? 'selected' : '' }}>
                                        {{ $kelasOption }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Jurusan</label>
                                <select name="jurusan" class="select2-filter">
                                    <option value="">Semua Jurusan</option>
                                    @isset($kelas)
                                    @foreach($kelas->unique('jurusan') as $k)
                                    <option value="{{ $k->jurusan }}" {{ request('jurusan') == $k->jurusan ? 'selected' : '' }}>
                                        {{ $k->jurusan }}
                                    </option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Status</label>
                                <select name="status_peminjaman" class="select2-filter">
                                    <option value="">Semua Status</option>
                                    <option value="dipinjam" {{ request('status_peminjaman') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="kembali" {{ request('status_peminjaman') == 'kembali' ? 'selected' : '' }}>Kembali</option>
                                    <option value="hilang" {{ request('status_peminjaman') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Cari Peminjaman</label>
                                <input type="text" name="search" placeholder="Cari nama / kode / judul..." value="{{ request('search') }}">
                            </div>

                        </div>

                        <div class="filter-action">
                            <button type="submit" class="search-btn">Search</button>
                            <a href="{{ route('peminjaman.index') }}" class="refresh-btn">Refresh</a>
                            <a href="{{ route('peminjaman.export', request()->query()) }}" class="export-btn">Export Excel</a>
                        </div>

                    </form>

                </div>

                <div class="page-header">
                    <button class="add-btn" onclick="openTambahModal()">+ Tambah Peminjaman</button>
                </div>

                @if(session('success'))
                <div class="success-alert">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                <div class="error-alert">
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <div class="table-top">
                    <form method="GET" action="{{ route('peminjaman.index') }}">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="nama_kelas" value="{{ request('nama_kelas') }}">
                        <input type="hidden" name="jurusan" value="{{ request('jurusan') }}">
                        <input type="hidden" name="status_peminjaman" value="{{ request('status_peminjaman') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <div class="show-entries">
                            <span>Show</span>
                            <select name="per_page" onchange="this.form.submit()">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span>entries</span>
                        </div>
                    </form>
                </div>

                <div class="table-box">

                    <table>
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Nama Peminjam</th>
                                <th>Jenis</th>
                                <th>Kelas</th>
                                <th>Buku Dipinjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($peminjaman as $p)
                            <tr>
                                <td class="action-column">
                                    <button type="button"
                                        class="edit-btn"
                                        data-id="{{ $p->id }}"
                                        data-pengunjung="{{ $p->id_pengunjung }}"
                                        data-tanggal="{{ $p->tanggal_peminjaman }}"
                                        data-batas="{{ $p->batas_pengembalian }}"
                                        onclick="openEditModalFromButton(this)">
                                        Edit
                                    </button>

                                    <form action="{{ route('peminjaman.destroy', $p->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus data peminjaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Hapus</button>
                                    </form>
                                </td>

                                <td>{{ $p->pengunjung->nama_pengunjung ?? '-' }}</td>
                                <td>{{ $p->pengunjung->jenis_pengunjung ?? '-' }}</td>

                                <td>
                                    @if($p->pengunjung && $p->pengunjung->kelas)
                                    {{ $p->pengunjung->kelas->nama_kelas }} {{ $p->pengunjung->kelas->jurusan }}
                                    @else
                                    -
                                    @endif
                                </td>

                                <td>
                                    <div class="book-summary">
                                        <strong>{{ $p->details->count() }} buku</strong>
                                        <small>
                                            {{ $p->details->where('status_buku', 'dipinjam')->count() }} dipinjam,
                                            {{ $p->details->where('status_buku', 'kembali')->count() }} kembali,
                                            {{ $p->details->where('status_buku', 'hilang')->count() }} hilang
                                        </small>

                                        <button type="button"
                                            class="detail-btn"
                                            onclick="openDetailModal('detailModal{{ $p->id }}')">
                                            Detail
                                        </button>
                                    </div>
                                </td>

                                <td>{{ \Carbon\Carbon::parse($p->tanggal_peminjaman)->format('d-m-Y') }}</td>

                                <td>
                                    {{ $p->batas_pengembalian ? \Carbon\Carbon::parse($p->batas_pengembalian)->format('d-m-Y') : '-' }}
                                </td>

                                <td>

                                    @if($p->status_peminjaman == 'dipinjam')

                                    <span class="status-dipinjam">
                                        Dipinjam
                                    </span>

                                    @elseif($p->status_peminjaman == 'hilang')

                                    <span class="status-hilang">
                                        Hilang
                                    </span>

                                    @else

                                    <span class="status-kembali">
                                        Kembali
                                    </span>

                                    @endif

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="empty-data">Belum ada data peminjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                <div class="pagination-simple">
                    @if ($peminjaman->onFirstPage())
                    <span class="page-disabled">Previous</span>
                    @else
                    <a href="{{ $peminjaman->previousPageUrl() }}" class="page-btn">Previous</a>
                    @endif

                    <span class="page-info">Page {{ $peminjaman->currentPage() }} of {{ $peminjaman->lastPage() }}</span>

                    @if ($peminjaman->hasMorePages())
                    <a href="{{ $peminjaman->nextPageUrl() }}" class="page-btn">Next</a>
                    @else
                    <span class="page-disabled">Next</span>
                    @endif
                </div>

                @foreach($peminjaman as $p)
                <div class="modal" id="detailModal{{ $p->id }}">

                    <div class="modal-content detail-modal-content">

                        <div class="modal-header">
                            <h2>Detail Buku Dipinjam</h2>
                            <span class="close" onclick="closeDetailModal('detailModal{{ $p->id }}')">&times;</span>
                        </div>

                        <div class="detail-info">
                            <p><strong>Peminjam:</strong> {{ $p->pengunjung->nama_pengunjung ?? '-' }}</p>
                            <p><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($p->tanggal_peminjaman)->format('d-m-Y') }}</p>
                            <p><strong>Batas Kembali:</strong> {{ $p->batas_pengembalian ? \Carbon\Carbon::parse($p->batas_pengembalian)->format('d-m-Y') : '-' }}</p>
                            <p><strong>Tanggal Dikembalikan:</strong> {{ $p->tanggal_pengembalian ? \Carbon\Carbon::parse($p->tanggal_pengembalian)->format('d-m-Y') : '-' }}</p>
                        </div>

                        <div class="detail-book-list">
                            @foreach($p->details as $detail)
                            <div class="detail-book-item">
                                <div>
                                    <strong>{{ $detail->buku->judul_buku ?? '-' }}</strong>
                                    <small>{{ $detail->kode_buku }}</small>
                                </div>

                                <div class="detail-action">
                                    @if($detail->status_buku == 'dipinjam')

                                    <form action="{{ route('kembali', $detail->id) }}?open_detail={{ $p->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin buku ini sudah dikembalikan?')">
                                        @csrf
                                        <button type="submit" class="return-btn">Kembalikan</button>
                                    </form>

                                    <form action="{{ route('hilang', $detail->id) }}?open_detail={{ $p->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin buku ini hilang? Stok tidak akan dikembalikan.')">
                                        @csrf
                                        <button type="submit" class="delete-btn">Hilang</button>
                                    </form>

                                    @elseif($detail->status_buku == 'hilang')

                                    <span class="status-hilang">
                                        Buku Hilang - Rp {{ number_format($detail->harga_ganti ?? 0, 0, ',', '.') }}
                                    </span>

                                    @else

                                    <span class="status-kembali">Sudah Kembali</span>

                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>

                </div>
                @endforeach

            </div>

        </div>

    </div>

    <div class="modal" id="peminjamanModal">

        <div class="modal-content">

            <div class="modal-header">
                <h2 id="modalTitle">Tambah Peminjaman</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <form id="peminjamanForm" method="POST">
                @csrf
                <div id="methodField"></div>

                @include('peminjaman.partials.form')

                <button type="submit" class="save-btn">Simpan</button>
            </form>

        </div>

    </div>

    @include('profile.modal')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/peminjaman.js') }}"></script>
    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>