<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buku.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="dashboard-container">

        @include('layouts.sidebar')

        <div class="main-content" id="mainContent">

            @include('layouts.topbar')

            <div class="content">

                <div class="filter-box">

                    <form method="GET" action="{{ route('buku.index') }}">

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
                                <label>Kategori</label>

                                <select name="id_kategori" class="select2">
                                    <option value="">Semua Kategori</option>

                                    @foreach($kategori as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('id_kategori') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Jenjang Kelas</label>

                                <select name="jenjang_kelas" class="select2">
                                    <option value="">Semua Jenjang</option>
                                    <option value="X" {{ request('jenjang_kelas') == 'X' ? 'selected' : '' }}>X</option>
                                    <option value="XI" {{ request('jenjang_kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                                    <option value="XII" {{ request('jenjang_kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                                    <option value="Umum" {{ request('jenjang_kelas') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Cari Buku</label>
                                <input type="text"
                                    name="search"
                                    placeholder="Cari kode / judul..."
                                    value="{{ request('search') }}">
                            </div>

                        </div>

                        <div class="filter-action">

                            <button type="submit" class="search-btn">
                                Search
                            </button>

                            <a href="{{ route('buku.index') }}" class="refresh-btn">
                                Refresh
                            </a>

                            <a href="{{ route('buku.export', request()->query()) }}"
                                class="export-btn">
                                Export Excel
                            </a>
                        </div>

                    </form>

                </div>

                <div class="page-header">

                    <div class="header-action">

                        <button class="add-btn" onclick="openTambahModal()">
                            + Tambah Buku
                        </button>

                        <button class="export-btn"
                            type="button"
                            onclick="openImportModal()">

                            Import Excel

                        </button>

                    </div>

                </div>

                @if(session('success'))
                <div class="success-alert">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
                @endif

                <div class="table-top">

                    <form method="GET" action="{{ route('buku.index') }}">

                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="id_kategori" value="{{ request('id_kategori') }}">
                        <input type="hidden" name="jenjang_kelas" value="{{ request('jenjang_kelas') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <div class="show-entries">

                            <span>Show</span>

                            <select name="per_page"
                                onchange="this.form.submit()">

                                <option value="5"
                                    {{ request('per_page') == 5 ? 'selected' : '' }}>
                                    5
                                </option>

                                <option value="10"
                                    {{ request('per_page',10) == 10 ? 'selected' : '' }}>
                                    10
                                </option>

                                <option value="25"
                                    {{ request('per_page') == 25 ? 'selected' : '' }}>
                                    25
                                </option>

                                <option value="50"
                                    {{ request('per_page') == 50 ? 'selected' : '' }}>
                                    50
                                </option>

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
                                <th>Kode Buku</th>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th>Jenjang</th>
                                <th>DDC</th>
                                <th>Stok</th>
                                <th>Tanggal Kirim</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($buku as $b)

                            <tr>

                                <td class="action-column">

                                    <button type="button"
                                        class="edit-btn"

                                        data-kode="{{ $b->kode_buku }}"
                                        data-judul="{{ $b->judul_buku }}"
                                        data-kategori="{{ $b->id_kategori }}"
                                        data-jenjang="{{ $b->jenjang_kelas }}"
                                        data-ddc="{{ $b->kode_ddc }}"
                                        data-stok="{{ $b->stok }}"
                                        data-tanggal="{{ $b->tanggal_kirim }}"

                                        onclick="openEditModalFromButton(this)">

                                        Edit

                                    </button>

                                    <form action="{{ route('buku.destroy', $b->kode_buku) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus data buku ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-btn">
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                                <td>{{ $b->kode_buku }}</td>
                                <td>{{ $b->judul_buku }}</td>
                                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $b->jenjang_kelas ?? 'Umum' }}</td>
                                <td>{{ $b->kode_ddc ?? '-' }}</td>
                                <td>{{ $b->stok }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->tanggal_kirim)->format('d-m-Y') }}</td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="8" class="empty-data">
                                    Belum ada data buku
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="pagination-simple">

                    @if ($buku->onFirstPage())
                    <span class="page-disabled">Previous</span>
                    @else
                    <a href="{{ $buku->previousPageUrl() }}" class="page-btn">Previous</a>
                    @endif

                    <span class="page-info">
                        Page {{ $buku->currentPage() }} of {{ $buku->lastPage() }}
                    </span>

                    @if ($buku->hasMorePages())
                    <a href="{{ $buku->nextPageUrl() }}" class="page-btn">Next</a>
                    @else
                    <span class="page-disabled">Next</span>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="modal" id="bukuModal">

        <div class="modal-content">

            <div class="modal-header">

                <h2 id="modalTitle">
                    Tambah Buku
                </h2>

                <span class="close" onclick="closeModal()">
                    &times;
                </span>

            </div>

            <form id="bukuForm" method="POST">

                @csrf

                <div id="methodField"></div>

                @include('buku.partials.form')

                <button type="submit" class="save-btn">
                    Simpan
                </button>

            </form>

        </div>

    </div>

    @include('buku.partials.import-modal')

    @include('profile.modal')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/buku.js') }}"></script>
    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>