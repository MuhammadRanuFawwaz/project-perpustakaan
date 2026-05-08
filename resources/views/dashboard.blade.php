<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="dashboard-container">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- MAIN CONTENT --}}
        <div class="main-content" id="mainContent">

            {{-- TOPBAR --}}
            <div class="topbar">

                {{-- TOGGLE --}}
                <button class="toggle-btn"
                    id="toggleSidebar">

                    ☰

                </button>

                {{-- PROFILE --}}
                <div class="topbar-right">

                    <div class="profile-dropdown">

                        <button class="profile-btn"
                            id="profileBtn">

                            <div class="user-info">

                                <div class="user-name">
                                    {{ auth()->user()->name }}
                                </div>

                                <div class="user-role">
                                    Administrator
                                </div>

                            </div>

                            <div class="user-avatar"></div>

                        </button>

                        {{-- DROPDOWN --}}
                        <div class="dropdown-menu"
                            id="dropdownMenu">

                            <form method="POST"
                                action="{{ route('logout') }}">

                                @csrf

                                <button type="submit"
                                    class="logout-btn">

                                    Logout

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            {{-- CONTENT --}}
            <div class="content">

                {{-- CARDS --}}
                <div class="cards">

                    <div class="card">

                        <div class="card-icon">
                            👤
                        </div>

                        <div>

                            <div class="card-title">
                                PENGUNJUNG
                            </div>

                            <div class="card-number">
                                {{ $total_pengunjung }}
                            </div>

                        </div>

                    </div>

                    <div class="card">

                        <div class="card-icon">
                            📚
                        </div>

                        <div>

                            <div class="card-title">
                                BUKU
                            </div>

                            <div class="card-number">
                                {{ $total_buku }}
                            </div>

                        </div>

                    </div>

                    <div class="card">

                        <div class="card-icon">
                            📄
                        </div>

                        <div>

                            <div class="card-title">
                                DIPINJAM
                            </div>

                            <div class="card-number">
                                {{ $total_dipinjam }}
                            </div>

                        </div>

                    </div>

                </div>

                {{-- STATISTIK --}}
                <div class="box">

                    <div class="box-title">
                        Statistik Siswa per Kelas
                    </div>

                    <p>Belum ada data statistik.</p>

                </div>

                {{-- BOTTOM --}}
                <div class="bottom-grid">

                    {{-- AKTIVITAS --}}
                    <div class="box">

                        <div class="box-title">
                            Aktivitas Terakhir
                        </div>

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

                    {{-- WARNING --}}
                    <div class="box">

                        <div class="box-title">
                            Peringatan Jatuh Tempo
                        </div>

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

    {{-- SCRIPT --}}
    <script>
        // ======================
        // SIDEBAR TOGGLE
        // ======================

        const toggleBtn =
            document.getElementById('toggleSidebar');

        const sidebar =
            document.getElementById('sidebar');

        const mainContent =
            document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {

            sidebar.classList.toggle('sidebar-hide');

            mainContent.classList.toggle('main-full');

        });

        // ======================
        // PROFILE DROPDOWN
        // ======================

        const profileBtn =
            document.getElementById('profileBtn');

        const dropdownMenu =
            document.getElementById('dropdownMenu');

        profileBtn.addEventListener('click', () => {

            dropdownMenu.classList.toggle('show-dropdown');

        });

        // CLOSE DROPDOWN
        window.addEventListener('click', function(e) {

            if (!profileBtn.contains(e.target) &&
                !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show-dropdown');
            }

        });
    </script>

</x-app-layout>