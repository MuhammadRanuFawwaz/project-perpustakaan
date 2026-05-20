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

            <div class="content">

                <div class="cards">

                    <div class="card">
                        <div class="card-icon">👤</div>

                        <div>
                            <div class="card-title">PENGUNJUNG</div>
                            <div class="card-number">{{ $total_pengunjung }}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-icon">📚</div>

                        <div>
                            <div class="card-title">BUKU</div>
                            <div class="card-number">{{ $total_buku }}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-icon">📄</div>

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
                            <strong>{{ $s->jurusan }}</strong>
                            <span>{{ $s->total }} kunjungan</span>
                        </div>

                        <div class="stat-bar">
                            <<div class="stat-fill" data-width="{{ $persen }}">
                        </div>
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

                    <div class="activity-list">

                        @forelse($aktivitas as $a)

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

                        <p>Tidak ada aktivitas</p>

                        @endforelse

                    </div>

                </div>

                <div class="box">

                    <div class="box-title">
                        Peringatan Jatuh Tempo
                    </div>

                    <div class="activity-list">

                        @forelse($jatuhTempo as $j)

                        <div class="warning-item">

                            <strong>
                                {{ $j->pengunjung->nama_pengunjung ?? '-' }}
                            </strong>

                            <br>

                            Belum mengembalikan buku

                        </div>

                        @empty

                        <p>Tidak ada keterlambatan</p>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>

    @include('profile.modal')

    <script src="{{ asset('js/app-layout.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>

</x-app-layout>