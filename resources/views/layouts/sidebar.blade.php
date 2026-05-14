<div class="sidebar" id="sidebar">

    <div class="sidebar-top">

        <div class="sidebar-logo">

            <img src="{{ asset('images/Smkn1Tarumajaya.png') }}" alt="Logo Sekolah">

            <div class="logo-text">
                <span class="logo-title">Perpustakaan</span>
                <span class="logo-subtitle">SMKN 1 Tarumajaya</span>
            </div>

        </div>

    </div>

    <div class="sidebar-menu">

        <a href="{{ route('dashboard') }}"
            class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('pengunjung.index') }}"
            class="{{ request()->routeIs('pengunjung.*') ? 'active' : '' }}">
            Data Pengunjung
        </a>

        <a href="/buku">
            Data Buku
        </a>

        <a href="#">
            Peminjaman
        </a>

    </div>

</div>