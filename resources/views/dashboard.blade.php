<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">

    <div class="dashboard-container">

        @include('layouts.sidebar')

        <div class="main-content" id="mainContent">

            @include('layouts.topbar')

            <div class="filter-box">

                <div class="filter-title">
                    Filter Periode
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="custom-filter">

                    <div class="filter-input-group">

                        <div class="filter-field">
                            <label>Periode</label>

                            <select name="periode">
                                <option value="hari_ini" {{ request('periode') == 'hari_ini' ? 'selected' : '' }}>
                                    Hari Ini
                                </option>

                                <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>
                                    Minggu Ini
                                </option>

                                <option value="bulan_ini"
                                    {{ request('periode', 'bulan_ini') == 'bulan_ini' ? 'selected' : '' }}>
                                    Bulan Ini
                                </option>

                                <option value="bulan_lalu" {{ request('periode') == 'bulan_lalu' ? 'selected' : '' }}>
                                    Bulan Lalu
                                </option>

                                <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>
                                    Tahun Ini
                                </option>
                            </select>
                        </div>

                        <div class="filter-field">
                            <label>Dari</label>
                            <input type="date" name="dari" value="{{ request('dari') }}">
                        </div>

                        <div class="filter-field">
                            <label>Sampai</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}">
                        </div>

                        <button type="submit" class="btn-filter">
                            Filter
                        </button>

                        <a href="{{ route('dashboard') }}" class="btn-filter" style="text-decoration:none;">
                            Reset
                        </a>

                    </div>

                </form>

            </div>



            <div class="cards">

                <div class="card">
                    <div class="card-icon"> <img src="{{ asset('icon/pengunjung.png') }}"></div>

                    <div>
                        <div class="card-title">PENGUNJUNG</div>
                        <div class="card-number">{{ $total_pengunjung }}</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon"><img src="{{ asset('icon/book.png') }}"></div>

                    <div>
                        <div class="card-title">BUKU</div>
                        <div class="card-number">{{ $total_buku }}</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon"><img src="{{ asset('icon/pinjam.png') }}"></div>

                    <div>
                        <div class="card-title">DIPINJAM</div>
                        <div class="card-number">{{ $total_dipinjam }}</div>
                    </div>
                </div>

            </div>

            <div class="box">

                <div class="box-title">
                    Statistik Pengunjung per Jurusan
                </div>

                @php
                    $maxTotal = $statistikJurusan->max('total') ?: 1;
                @endphp

                @forelse($statistikJurusan as $s)
                    @php
                        $persen = ($s->total / $maxTotal) * 100;
                    @endphp

                    <div class="stat-item">

                        <div class="stat-info">
                            <strong>
                                {{ $s->nama_kelas }} - {{ $s->jurusan }}
                            </strong>
                            <span>{{ $s->total }} kunjungan</span>
                        </div>

                        <div class="stat-bar">
                            <div class="stat-fill" data-width="{{ $persen }}"></div>
                        </div>

                    </div>

                @empty

                    <p>Belum ada data statistik.</p>
                @endforelse

            </div>

            <div class="bottom-grid">

                <div class="box">

                    <div class="box-title">
                        Aktivitas Terakhir
                    </div>

                    <div class="warning-sidebar">

                        <div class="warning-menu active" id="btnPengunjung">
                            Pengunjung
                        </div>

                        <div class="warning-menu" id="btnBuku">
                            Buku
                        </div>

                        <div class="warning-menu" id="btnPeminjaman">
                            Peminjaman
                        </div>

                    </div>

                    {{-- PENGUNJUNG --}}
                    <div id="aktivitasPengunjung" class="activity-list">

                        @forelse($aktivitasPengunjung as $a)
                            <div class="activity-item">

                                <strong>{{ $a['judul'] }}</strong>

                                <br>

                                <span>{{ $a['deskripsi'] }}</span>

                                <br>

                                <small>
                                    {{ \Carbon\Carbon::parse($a['waktu'])->format('d/m/Y H:i') }}
                                </small>

                            </div>

                        @empty

                            <p>Tidak ada aktivitas pengunjung.</p>
                        @endforelse

                    </div>

                    {{-- BUKU --}}
                    <div id="aktivitasBuku" class="activity-list" style="display:none;">

                        @forelse($aktivitasBuku as $a)
                            <div class="activity-item">

                                <strong>{{ $a['judul'] }}</strong>

                                <br>

                                <span>{{ $a['deskripsi'] }}</span>

                                <br>

                                <small>
                                    {{ \Carbon\Carbon::parse($a['waktu'])->format('d/m/Y H:i') }}
                                </small>

                            </div>

                        @empty

                            <p>Tidak ada aktivitas buku.</p>
                        @endforelse

                    </div>

                    {{-- PEMINJAMAN --}}
                    <div id="aktivitasPeminjaman" class="activity-list" style="display:none;">

                        @forelse($aktivitasPeminjaman as $a)
                            <div class="activity-item">

                                <strong>{{ $a['judul'] }}</strong>

                                <br>

                                <span>{{ $a['deskripsi'] }}</span>

                                <br>

                                <small>
                                    {{ \Carbon\Carbon::parse($a['waktu'])->format('d/m/Y H:i') }}
                                </small>

                            </div>

                        @empty

                            <p>Tidak ada aktivitas peminjaman.</p>
                        @endforelse

                    </div>
                </div>

                <div class="box">

                    <div class="box-title">
                        Peringatan Jatuh Tempo
                    </div>
                    <div class="warning-sidebar">

                        <div class="warning-menu active" id="btnTerlambat">
                            Jatuh Tempo
                        </div>

                        <div class="warning-menu" id="btnAkanJatuhTempo">
                            Akan Jatuh Tempo
                        </div>

                    </div>

                    <div id="dataTerlambat" class="activity-list">

                        @forelse($jatuhTempo as $j)

                            <div class="warning-item warning-danger">

                                <strong>
                                    {{ $j->pengunjung->nama_pengunjung ?? '-' }}
                                </strong>

                                <br>

                                <small>
                                    Batas kembali:
                                    {{ $j->batas_pengembalian ? \Carbon\Carbon::parse($j->batas_pengembalian)->format('d-m-Y') : '-' }}
                                </small>

                                <br>

                                <span>
                                    Buku:
                                    @foreach ($j->details->where('status_buku', 'dipinjam') as $detail)
                                        {{ $detail->buku->judul_buku ?? $detail->kode_buku }}

                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </span>

                            </div>

                        @empty

                            <p>Tidak ada buku yang jatuh tempo.</p>

                        @endforelse

                    </div>

                    <div id="dataAkanJatuhTempo" class="activity-list" style="display:none;">

                        @forelse($akanJatuhTempo as $j)

                            <div class="warning-item warning-warning">

                                <strong>
                                    {{ $j->pengunjung->nama_pengunjung ?? '-' }}
                                </strong>

                                <br>

                                <small>
                                    Akan jatuh tempo:
                                    {{ $j->batas_pengembalian ? \Carbon\Carbon::parse($j->batas_pengembalian)->format('d-m-Y') : '-' }}
                                </small>

                                <br>

                                <span>
                                    Buku:
                                    @foreach ($j->details->where('status_buku', 'dipinjam') as $detail)
                                        {{ $detail->buku->judul_buku ?? $detail->kode_buku }}

                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </span>

                            </div>

                        @empty

                            <p>Tidak ada buku yang akan jatuh tempo.</p>

                        @endforelse

                    </div>

                </div>

            </div>
        </div>

        @include('profile.modal')

        <script src="{{ asset('js/app-layout.js') }}"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>

</x-app-layout>
