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
                    <div class="box-title">Statistik Siswa per Kelas</div>
                    <p>Belum ada data statistik.</p>
                </div>

                <div class="bottom-grid">

                    <div class="box">
                        <div class="box-title">Aktivitas Terakhir</div>

                        @forelse($aktivitas as $a)

                        <div class="activity-item">
                            <strong>
                                {{ $a->peminjaman->pengunjung->nama_pengunjung ?? '-' }}
                            </strong>

                            <br>

                            {{ $a->status_buku }}
                        </div>

                        @empty

                        Tidak ada aktivitas

                        @endforelse
                    </div>

                    <div class="box">
                        <div class="box-title">Peringatan Jatuh Tempo</div>

                        @forelse($jatuhTempo as $j)

                        <div class="warning-item">
                            <strong>
                                {{ $j->pengunjung->nama_pengunjung ?? '-' }}
                            </strong>

                            <br>

                            Belum mengembalikan buku
                        </div>

                        @empty

                        Tidak ada keterlambatan

                        @endforelse
                    </div>

                </div>

            </div>

        </div>

    </div>

    @include('profile.modal')

    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>