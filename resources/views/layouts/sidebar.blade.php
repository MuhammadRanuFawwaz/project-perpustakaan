<div class="sidebar" id="sidebar">

    <div class="sidebar-top">

        <div class="sidebar-logo">
            Perpustakaan
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