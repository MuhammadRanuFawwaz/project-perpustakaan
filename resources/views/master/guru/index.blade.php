<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengunjung.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/master-guru.css') }}">

    <div class="dashboard-container">

        @include('layouts.sidebar')

        <div class="main-content" id="mainContent">

            @include('layouts.topbar')

            <div class="content">

                @if(session('success'))
                <div class="alert-success">
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

                <div class="stat-grid">

                    <div class="stat-card">
                        <span class="stat-label">Total Guru</span>
                        <strong>{{ $totalGuru }}</strong>
                    </div>

                </div>

                <div class="filter-box">

                    <form method="GET"
                        action="{{ route('master.guru.index') }}">

                        <div class="filter-grid">

                            <div class="filter-group">
                                <label>Cari Guru</label>

                                <input type="text"
                                    name="search"
                                    placeholder="Cari NIP / nama..."
                                    value="{{ request('search') }}">
                            </div>

                        </div>

                        <div class="filter-action">

                            <button type="submit"
                                class="search-btn">
                                Search
                            </button>

                            <a href="{{ route('master.guru.index') }}"
                                class="refresh-btn">
                                Refresh
                            </a>

                            <a href="{{ route('master.guru.export', request()->query()) }}"
                                class="export-btn">
                                Export Excel
                            </a>

                        </div>

                    </form>

                </div>

                <div class="page-header">

                    <div class="header-action">

                        <button class="add-btn"
                            onclick="openGuruModal()">

                            + Tambah Guru

                        </button>

                        <button class="export-btn"
                            type="button"
                            onclick="openImportGuruModal()">

                            Import Excel

                        </button>

                    </div>

                </div>

                <div class="table-top">

                    <form method="GET"
                        action="{{ route('master.guru.index') }}">

                        <input type="hidden"
                            name="search"
                            value="{{ request('search') }}">

                        <div class="show-entries">

                            <span>Show</span>

                            <select name="per_page"
                                onchange="this.form.submit()">

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
                                <th>NIP</th>
                                <th>Nama Guru</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($guru as $g)

                            <tr>

                                <td class="action-column">

                                    <a href="{{ route('master.guru.edit', $g->id) }}"
                                        class="edit-btn">
                                        Edit
                                    </a>

                                    <form action="{{ route('master.guru.destroy', $g->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus data guru ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="delete-btn">
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                                <td>{{ $g->nip }}</td>
                                <td>{{ $g->nama_guru }}</td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="3"
                                    class="empty-data">
                                    Belum ada data guru
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="pagination-simple">

                    @if ($guru->onFirstPage())
                    <span class="page-disabled">Previous</span>
                    @else
                    <a href="{{ $guru->previousPageUrl() }}" class="page-btn">Previous</a>
                    @endif

                    <span class="page-info">
                        Page {{ $guru->currentPage() }} of {{ $guru->lastPage() }}
                    </span>

                    @if ($guru->hasMorePages())
                    <a href="{{ $guru->nextPageUrl() }}" class="page-btn">Next</a>
                    @else
                    <span class="page-disabled">Next</span>
                    @endif

                </div>

            </div>

        </div>

    </div>

    @include('master.guru.partials.form')

    @include('master.guru.partials.import-modal')

    <input type="hidden"
        id="edit-mode"
        value="{{ isset($editGuru) ? 'true' : 'false' }}">

    @include('profile.modal')

    <script src="{{ asset('js/master-guru.js') }}"></script>
    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>