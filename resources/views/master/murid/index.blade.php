<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengunjung.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/master-murid.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
                        <span class="stat-label">Murid Aktif</span>
                        <strong>{{ $totalAktif }}</strong>
                    </div>

                    <div class="stat-card">
                        <span class="stat-label">Murid Lulus</span>
                        <strong>{{ $totalLulus }}</strong>
                    </div>

                    <div class="stat-card">
                        <span class="stat-label">Murid Nonaktif</span>
                        <strong>{{ $totalNonaktif }}</strong>
                    </div>

                </div>

                <div class="filter-box">

                    <form method="GET"
                        action="{{ route('master.murid.index') }}">

                        <div class="filter-grid">

                            <div class="filter-group">
                                <label>Cari Murid</label>

                                <input type="text"
                                    name="search"
                                    placeholder="Cari NIS / nama..."
                                    value="{{ request('search') }}">
                            </div>

                            <div class="filter-group">
                                <label>Filter Kelas</label>

                                <select name="tingkat"
                                    class="select2-filter">

                                    <option value="">Semua Kelas</option>

                                    <option value="X"
                                        {{ request('tingkat') == 'X' ? 'selected' : '' }}>
                                        X
                                    </option>

                                    <option value="XI"
                                        {{ request('tingkat') == 'XI' ? 'selected' : '' }}>
                                        XI
                                    </option>

                                    <option value="XII"
                                        {{ request('tingkat') == 'XII' ? 'selected' : '' }}>
                                        XII
                                    </option>

                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Filter Jurusan</label>

                                <select name="jurusan"
                                    class="select2-filter">

                                    <option value="">Semua Jurusan</option>

                                    @foreach($jurusan as $j)
                                    <option value="{{ $j }}"
                                        {{ request('jurusan') == $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Status</label>

                                <select name="status"
                                    class="select2-filter">

                                    <option value="">Aktif</option>

                                    <option value="aktif"
                                        {{ request('status') == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="lulus"
                                        {{ request('status') == 'lulus' ? 'selected' : '' }}>
                                        Lulus
                                    </option>

                                    <option value="nonaktif"
                                        {{ request('status') == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div class="filter-action">

                            <button type="submit"
                                class="search-btn">

                                Search

                            </button>

                            <a href="{{ route('master.murid.index') }}"
                                class="refresh-btn">

                                Refresh

                            </a>

                            <a href="{{ route('master.murid.export', request()->query()) }}"
                                class="export-btn">

                                Export Excel

                            </a>

                        </div>

                    </form>

                </div>

                <div class="page-header">

                    <div class="header-action">

                        <button class="add-btn"
                            onclick="openMuridModal()">

                            + Tambah Murid

                        </button>

                        <button class="export-btn"
                            type="button"
                            onclick="openImportModal()">

                            Import Excel

                        </button>

                        <form method="POST"
                            action="{{ route('master.murid.luluskan') }}">

                            @csrf

                            <button type="submit"
                                class="delete-btn"
                                onclick="return confirm('Yakin luluskan semua murid kelas XII aktif?')">

                                Luluskan XII

                            </button>

                        </form>

                    </div>

                </div>

                <div class="table-top">

                    <form method="GET"
                        action="{{ route('master.murid.index') }}">

                        <input type="hidden"
                            name="search"
                            value="{{ request('search') }}">

                        <input type="hidden"
                            name="tingkat"
                            value="{{ request('tingkat') }}">

                        <input type="hidden"
                            name="jurusan"
                            value="{{ request('jurusan') }}">

                        <input type="hidden"
                            name="status"
                            value="{{ request('status') }}">

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
                                <th>NIS</th>
                                <th>Nama Murid</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($murid as $m)

                            <tr>

                                <td class="action-column">

                                    <a href="{{ route('master.murid.edit', $m->id) }}"
                                        class="edit-btn">

                                        Edit

                                    </a>

                                    <form action="{{ route('master.murid.destroy', $m->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Data murid tidak akan dihapus permanen, hanya dinonaktifkan. Lanjutkan?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="delete-btn">

                                            Nonaktifkan

                                        </button>

                                    </form>

                                </td>

                                <td>{{ $m->nis }}</td>
                                <td>{{ $m->nama_murid }}</td>
                                <td>{{ $m->kelas->nama_kelas ?? '-' }}</td>
                                <td>{{ $m->kelas->jurusan ?? '-' }}</td>
                                <td>{{ ucfirst($m->status) }}</td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="6"
                                    class="empty-data">

                                    Belum ada data murid

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="pagination-simple">

                    @if ($murid->onFirstPage())
                    <span class="page-disabled">
                        Previous
                    </span>
                    @else
                    <a href="{{ $murid->previousPageUrl() }}"
                        class="page-btn">

                        Previous

                    </a>
                    @endif

                    <span class="page-info">
                        Page {{ $murid->currentPage() }} of {{ $murid->lastPage() }}
                    </span>

                    @if ($murid->hasMorePages())
                    <a href="{{ $murid->nextPageUrl() }}"
                        class="page-btn">

                        Next

                    </a>
                    @else
                    <span class="page-disabled">
                        Next
                    </span>
                    @endif

                </div>

            </div>

        </div>

    </div>

    @include('master.murid.partials.form')

    @include('master.murid.partials.import-modal')

    <input type="hidden"
        id="edit-mode"
        value="{{ isset($editMurid) ? 'true' : 'false' }}">

    @include('profile.modal')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/master-murid.js') }}"></script>

    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>