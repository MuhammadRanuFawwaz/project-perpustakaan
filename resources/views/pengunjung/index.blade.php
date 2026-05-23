<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengunjung.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">

    {{-- SELECT2 --}}
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

                <div class="filter-box">

                    <form method="GET"
                        action="{{ route('pengunjung.index') }}">

                        <div class="filter-grid">

                            <div class="filter-group">
                                <label>Tanggal Awal</label>

                                <input type="date"
                                    name="start_date"
                                    value="{{ request('start_date') }}">
                            </div>

                            <div class="filter-group">
                                <label>Tanggal Akhir</label>

                                <input type="date"
                                    name="end_date"
                                    value="{{ request('end_date') }}">
                            </div>

                            <div class="filter-group">
                                <label>Filter Kelas</label>

                                <select name="nama_kelas" class="select2-filter">
                                    <option value="">Semua Kelas</option>

                                    @foreach ($kelas->filter(fn($k) => !str_contains($k->nama_kelas, 'TKR'))->unique('nama_kelas') as $k)
                                    <option value="{{ $k->nama_kelas }}"
                                        {{ request('nama_kelas') == $k->nama_kelas ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Jurusan</label>

                                <select name="jurusan" class="select2-filter">
                                    <option value="">Semua Jurusan</option>

                                    @foreach($kelas->unique('jurusan') as $k)
                                    <option value="{{ $k->jurusan }}"
                                        {{ request('jurusan') == $k->jurusan ? 'selected' : '' }}>
                                        {{ $k->jurusan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Cari Nama / NIS / NIP</label>

                                <input type="text"
                                    name="search"
                                    placeholder="Cari pengunjung..."
                                    value="{{ request('search') }}">
                            </div>

                        </div>

                        <div class="filter-action">

                            <button type="submit"
                                class="search-btn">

                                Search

                            </button>

                            <a href="{{ route('pengunjung.index') }}"
                                class="refresh-btn">

                                Refresh

                            </a>

                            <a href="{{ route('pengunjung.export', request()->query()) }}"
                                class="export-btn">

                                Export Excel

                            </a>

                        </div>

                    </form>

                </div>

                <div class="page-header">
                    <button class="add-btn"
                        onclick="openTambahModal()">

                        + Tambah Pengunjung

                    </button>
                </div>

                <div class="table-top">

                    <form method="GET" action="{{ route('pengunjung.index') }}">

                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="nama_kelas" value="{{ request('nama_kelas') }}">
                        <input type="hidden" name="jurusan" value="{{ request('jurusan') }}">
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
                                <th>NIS / NIP</th>
                                <th>Nama Pengunjung</th>
                                <th>Jenis Pengunjung</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Tanggal Kunjung</th>
                                <th>Waktu Kunjung</th>
                                <th>Keperluan</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($pengunjung as $p)

                            <tr>

                                <td class="action-column">

                                    <button type="button"
                                        class="edit-btn"
                                        onclick="openEditModal(
                                                '{{ $p->id }}',
                                                '{{ $p->nomor_induk }}',
                                                '{{ $p->nama_pengunjung }}',
                                                '{{ $p->jenis_pengunjung }}',
                                                '{{ $p->id_kelas }}',
                                                '{{ $p->kelas->nama_kelas ?? '-' }}',
                                                '{{ $p->kelas->jurusan ?? '-' }}',
                                                '{{ $p->keperluan }}'
                                            )">

                                        Edit

                                    </button>

                                    <form action="{{ route('pengunjung.destroy', $p->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="delete-btn">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                                <td>{{ $p->nomor_induk ?? '-' }}</td>
                                <td>{{ $p->nama_pengunjung }}</td>
                                <td>{{ $p->jenis_pengunjung }}</td>
                                <td>{{ $p->kelas->nama_kelas ?? '-' }}</td>
                                <td>{{ $p->kelas->jurusan ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_kunjung)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->waktu_kunjung)->format('H:i') }}</td>
                                <td>{{ $p->keperluan }}</td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="9" class="empty-data">
                                    Belum ada data pengunjung
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="pagination-simple">

                    @if ($pengunjung->onFirstPage())
                    <span class="page-disabled">Previous</span>
                    @else
                    <a href="{{ $pengunjung->previousPageUrl() }}" class="page-btn">Previous</a>
                    @endif

                    <span class="page-info">
                        Page {{ $pengunjung->currentPage() }} of {{ $pengunjung->lastPage() }}
                    </span>

                    @if ($pengunjung->hasMorePages())
                    <a href="{{ $pengunjung->nextPageUrl() }}" class="page-btn">Next</a>
                    @else
                    <span class="page-disabled">Next</span>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="modal"
        id="pengunjungModal">

        <div class="modal-content">

            <div class="modal-header">

                <h2 id="modalTitle">
                    Tambah Pengunjung
                </h2>

                <span class="close"
                    onclick="closeModal()">

                    &times;

                </span>

            </div>

            <form id="pengunjungForm"
                method="POST">

                @csrf

                <div id="methodField"></div>

                @include('pengunjung.partials.form')

                <button type="submit"
                    class="save-btn">

                    Simpan

                </button>

            </form>

        </div>

    </div>

    @include('profile.modal')

    {{-- JQUERY --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- SELECT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- JS --}}
    <script src="{{ asset('js/pengunjung.js') }}"></script>
    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>