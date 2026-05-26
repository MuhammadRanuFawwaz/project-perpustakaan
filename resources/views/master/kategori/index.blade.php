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

                    <form method="GET"
                        action="{{ route('master.kategori.index') }}">

                        <div class="filter-grid">

                            <div class="filter-group">
                                <label>Cari Kategori</label>

                                <input type="text"
                                    name="search"
                                    placeholder="Cari kategori..."
                                    value="{{ request('search') }}">
                            </div>

                        </div>

                        <div class="filter-action">

                            <button type="submit"
                                class="search-btn">

                                Search

                            </button>

                            <a href="{{ route('master.kategori.index') }}"
                                class="refresh-btn">

                                Refresh

                            </a>

                        </div>

                    </form>

                </div>

                <div class="page-header">

                    <button class="add-btn"
                        onclick="openKategoriModal()">

                        + Tambah Kategori

                    </button>

                </div>

                <div class="table-top">

                    <form method="GET"
                        action="{{ route('master.kategori.index') }}">

                        <input type="hidden"
                            name="search"
                            value="{{ request('search') }}">

                        <div class="show-entries">

                            <span>Show</span>

                            <select name="per_page"
                                onchange="this.form.submit()">

                                <option value="5"
                                    {{ request('per_page') == 5 ? 'selected' : '' }}>
                                    5
                                </option>

                                <option value="10"
                                    {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
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
                                <th>Nama Kategori</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($kategori as $k)

                            <tr>

                                <td class="action-column">

                                    <button type="button"
                                        class="edit-btn"
                                        data-id="{{ $k->id }}"
                                        data-nama="{{ $k->nama_kategori }}"
                                        onclick="openEditKategoriModal(this)">

                                        Edit

                                    </button>

                                    <form action="{{ route('master.kategori.destroy', $k->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus kategori ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="delete-btn">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                                <td>{{ $k->nama_kategori }}</td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="2"
                                    class="empty-data">

                                    Belum ada data kategori

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="pagination-simple">

                    @if ($kategori->onFirstPage())
                    <span class="page-disabled">Previous</span>
                    @else
                    <a href="{{ $kategori->previousPageUrl() }}" class="page-btn">Previous</a>
                    @endif

                    <span class="page-info">
                        Page {{ $kategori->currentPage() }} of {{ $kategori->lastPage() }}
                    </span>

                    @if ($kategori->hasMorePages())
                    <a href="{{ $kategori->nextPageUrl() }}" class="page-btn">Next</a>
                    @else
                    <span class="page-disabled">Next</span>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="modal"
        id="kategoriModal">

        <div class="modal-content">

            <div class="modal-header">

                <h2 id="kategoriModalTitle">
                    Tambah Kategori
                </h2>

                <span class="close"
                    onclick="closeKategoriModal()">

                    &times;

                </span>

            </div>

            <form id="kategoriForm"
                method="POST"
                action="{{ route('master.kategori.store') }}">

                @csrf

                <div id="kategoriMethodField"></div>

                <div class="form-group">

                    <label>Nama Kategori</label>

                    <input type="text"
                        name="nama_kategori"
                        id="nama_kategori"
                        placeholder="Masukkan nama kategori"
                        required>

                </div>

                <button type="submit"
                    class="save-btn">

                    Simpan

                </button>

            </form>

        </div>

    </div>

    @include('profile.modal')

    <script src="{{ asset('js/master-kategori.js') }}"></script>
    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>