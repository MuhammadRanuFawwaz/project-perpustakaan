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
                        action="{{ route('master.ddc.index') }}">

                        <div class="filter-grid">

                            <div class="filter-group">
                                <label>Cari DDC</label>

                                <input type="text"
                                    name="search"
                                    placeholder="Cari kode / nama DDC..."
                                    value="{{ request('search') }}">
                            </div>

                        </div>

                        <div class="filter-action">

                            <button type="submit"
                                class="search-btn">

                                Search

                            </button>

                            <a href="{{ route('master.ddc.index') }}"
                                class="refresh-btn">

                                Refresh

                            </a>

                        </div>

                    </form>

                </div>

                <div class="page-header">

                    <button class="add-btn"
                        onclick="openDdcModal()">

                        + Tambah DDC

                    </button>

                </div>

                <div class="table-top">

                    <form method="GET"
                        action="{{ route('master.ddc.index') }}">

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
                                <th>Kode DDC</th>
                                <th>Nama Klasifikasi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($ddc as $d)

                            <tr>

                                <td class="action-column">

                                    <button type="button"
                                        class="edit-btn"
                                        data-id="{{ $d->id }}"
                                        data-kode="{{ $d->kode_ddc }}"
                                        data-nama="{{ $d->nama_ddc }}"
                                        onclick="openEditDdcModal(this)">

                                        Edit

                                    </button>

                                    <form action="{{ route('master.ddc.destroy', $d->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus DDC ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="delete-btn">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                                <td>{{ $d->kode_ddc }}</td>
                                <td>{{ $d->nama_ddc }}</td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="3"
                                    class="empty-data">

                                    Belum ada data DDC

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="pagination-simple">

                    @if ($ddc->onFirstPage())
                    <span class="page-disabled">Previous</span>
                    @else
                    <a href="{{ $ddc->previousPageUrl() }}" class="page-btn">Previous</a>
                    @endif

                    <span class="page-info">
                        Page {{ $ddc->currentPage() }} of {{ $ddc->lastPage() }}
                    </span>

                    @if ($ddc->hasMorePages())
                    <a href="{{ $ddc->nextPageUrl() }}" class="page-btn">Next</a>
                    @else
                    <span class="page-disabled">Next</span>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="modal"
        id="ddcModal">

        <div class="modal-content">

            <div class="modal-header">

                <h2 id="ddcModalTitle">
                    Tambah DDC
                </h2>

                <span class="close"
                    onclick="closeDdcModal()">

                    &times;

                </span>

            </div>

            <form id="ddcForm"
                method="POST"
                action="{{ route('master.ddc.store') }}">

                @csrf

                <div id="ddcMethodField"></div>

                <div class="form-group">

                    <label>Kode DDC</label>

                    <input type="text"
                        name="kode_ddc"
                        id="kode_ddc_input"
                        placeholder=""
                        required>

                </div>

                <div class="form-group">

                    <label>Nama Klasifikasi</label>

                    <input type="text"
                        name="nama_ddc"
                        id="nama_ddc"
                        placeholder=""
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

    <script src="{{ asset('js/master-ddc.js') }}"></script>
    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>