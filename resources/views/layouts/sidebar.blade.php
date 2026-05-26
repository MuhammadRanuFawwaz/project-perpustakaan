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

        @if(auth()->user()->role === 'superadmin')

        <a href="{{ route('master.murid.index') }}"
            class="{{ request()->routeIs('master.murid.*') ? 'active' : '' }}">
            Data Murid
        </a>

        <a href="{{ route('master.guru.index') }}"
            class="{{ request()->routeIs('master.guru.*') ? 'active' : '' }}">
            Data Guru
        </a>

        <a href="{{ route('master.kategori.index') }}"
            class="{{ request()->routeIs('master.kategori.*') ? 'active' : '' }}">
            Master Kategori
        </a>

        <a href="{{ route('master.ddc.index') }}"
            class="{{ request()->routeIs('master.ddc.*') ? 'active' : '' }}">
            Master DDC
        </a>

        @endif

        <a href="{{ route('pengunjung.index') }}"
            class="{{ request()->routeIs('pengunjung.*') ? 'active' : '' }}">
            Pengunjung
        </a>

        <a href="{{ route('buku.index') }}"
            class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
            Buku
        </a>

        <a href="{{ route('peminjaman.index') }}"
            class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            Peminjaman
        </a>

    </div>

</div>