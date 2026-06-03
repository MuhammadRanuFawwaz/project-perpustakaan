<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buku.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">

    <div class="dashboard-container">

        @include('layouts.sidebar')

        <div class="main-content" id="mainContent">

            @include('layouts.topbar')

            <div class="content">

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

                @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <div class="filter-box">

                    <form method="GET" action="{{ route('master.harga-buku.index') }}">

                        <div class="filter-grid">

                            <div class="filter-group">
                                <label>Cari Buku</label>

                                <input type="text"
                                    name="search"
                                    placeholder="Cari kode / judul buku..."
                                    value="{{ request('search') }}">
                            </div>

                        </div>

                        <div class="filter-action">

                            <button type="submit" class="search-btn">
                                Search
                            </button>

                            <a href="{{ route('master.harga-buku.index') }}" class="refresh-btn">
                                Refresh
                            </a>

                        </div>

                    </form>

                </div>

                <div class="page-header">

                    <button class="add-btn" onclick="openHargaModal()">
                        + Tambah Harga Buku
                    </button>

                </div>

                <div class="table-top">

                    <form method="GET" action="{{ route('master.harga-buku.index') }}">

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
                                <th>Kode Buku</th>
                                <th>Judul Buku</th>
                                <th>Harga</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($hargaBuku as $h)

                            <tr>

                                <td class="action-column">

                                    <button type="button"
                                        class="edit-btn"
                                        data-id="{{ $h->id }}"
                                        data-kode="{{ $h->kode_buku }}"
                                        data-harga="{{ $h->harga }}"
                                        onclick="openEditHargaModal(this)">

                                        Edit

                                    </button>

                                    <form action="{{ route('master.harga-buku.destroy', $h->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus harga buku ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-btn">
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                                <td>{{ $h->kode_buku }}</td>
                                <td>{{ $h->buku->judul_buku ?? '-' }}</td>
                                <td>Rp {{ number_format($h->harga, 0, ',', '.') }}</td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="4" class="empty-data">
                                    Belum ada data harga buku
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="pagination-simple">

                    @if ($hargaBuku->onFirstPage())
                    <span class="page-disabled">Previous</span>
                    @else
                    <a href="{{ $hargaBuku->previousPageUrl() }}" class="page-btn">Previous</a>
                    @endif

                    <span class="page-info">
                        Page {{ $hargaBuku->currentPage() }} of {{ $hargaBuku->lastPage() }}
                    </span>

                    @if ($hargaBuku->hasMorePages())
                    <a href="{{ $hargaBuku->nextPageUrl() }}" class="page-btn">Next</a>
                    @else
                    <span class="page-disabled">Next</span>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="modal" id="hargaModal">

        <div class="modal-content">

            <div class="modal-header">

                <h2 id="hargaModalTitle">
                    Tambah Harga Buku
                </h2>

                <span class="close" onclick="closeHargaModal()">
                    &times;
                </span>

            </div>

            <form id="hargaForm" method="POST" action="{{ route('master.harga-buku.store') }}">

                @csrf

                <div id="hargaMethodField"></div>

                <div class="form-group">

                    <label>Buku</label>

                    <select name="kode_buku" id="kode_buku_harga" required>
                        <option value="">-- Pilih Buku --</option>

                        @foreach($semuaBuku as $b)
                        <option value="{{ $b->kode_buku }}">
                            {{ $b->kode_buku }} - {{ $b->judul_buku }}
                        </option>
                        @endforeach
                    </select>

                </div>

                <div class="form-group">

                    <label>Harga Buku</label>

                    <input type="number"
                        name="harga"
                        id="harga"
                        placeholder="Masukkan harga buku"
                        min="0"
                        step="100"
                        required>

                </div>

                <button type="submit" class="save-btn">
                    Simpan
                </button>

            </form>

        </div>

    </div>

    @include('profile.modal')

    <script src="{{ asset('js/master-harga-buku.js') }}"></script>
    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>