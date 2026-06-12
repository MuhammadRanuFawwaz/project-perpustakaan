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

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <img src="{{ asset('icon/dashboard.png') }}" class="menu-icon">
            Dashboard
        </a>

        @if (auth()->user()->role === 'superadmin')
            <a href="{{ route('master.murid.index') }}"
                class="{{ request()->routeIs('master.murid.*') ? 'active' : '' }}">
                <img src="{{ asset('icon/murid.png') }}" class="menu-icon">
                Data Murid
            </a>

            <a href="{{ route('master.guru.index') }}"
                class="{{ request()->routeIs('master.guru.*') ? 'active' : '' }}">
                <img src="{{ asset('icon/guru.png') }}" class="menu-icon">
                Data Guru
            </a>

            <a href="{{ route('master.kategori.index') }}"
                class="{{ request()->routeIs('master.kategori.*') ? 'active' : '' }}">
                <img src="{{ asset('icon/kategori.png') }}" class="menu-icon">
                Master Kategori
            </a>

            <a href="{{ route('master.ddc.index') }}"
                class="{{ request()->routeIs('master.ddc.*') ? 'active' : '' }}">
                <img src="{{ asset('icon/ddc.png') }}" class="menu-icon">
                Master DDC
            </a>

            <a href="{{ route('master.harga-buku.index') }}"
                class="{{ request()->routeIs('master.harga-buku.*') ? 'active' : '' }}">
                <img src="{{ asset('icon/harga.png') }}" class="menu-icon">
                Master Harga Buku
            </a>

            <a href="{{ route('master.akses-admin.index') }}"
                class="{{ request()->routeIs('master.akses-admin.*') ? 'active' : '' }}">
                <img src="{{ asset('icon/akses.png') }}" class="menu-icon">
                Hak Akses Admin
            </a>
        @endif

        <a href="{{ route('pengunjung.index') }}" class="{{ request()->routeIs('pengunjung.*') ? 'active' : '' }}">
            <img src="{{ asset('icon/pengunjung.png') }}" class="menu-icon">
            Pengunjung
        </a>

        <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <img src="{{ asset('icon/book.png') }}" class="menu-icon">
            Buku
        </a>

        <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <img src="{{ asset('icon/pinjam.png') }}" class="menu-icon">
            Peminjaman dan Pengembalian
        </a>

    </div>

</div>
