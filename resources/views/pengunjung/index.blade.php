<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengunjung.css') }}">

    <div class="dashboard-container">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- MAIN --}}
        <div class="main-content" id="mainContent">

            {{-- TOPBAR --}}
            <div class="topbar">

                <div class="topbar-right">

                    <div class="profile-dropdown">

                        <button class="profile-btn" id="profileBtn">

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

                        <div class="dropdown-menu" id="dropdownMenu">

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

                <div class="page-header">

                    <h1>Data Pengunjung</h1>

                    <a href="{{ route('pengunjung.create') }}"
                        class="add-btn">

                        + Tambah Pengunjung

                    </a>

                </div>

                <div class="table-box">

                    <table>

                        <thead>

                            <tr>

                                <th>Action</th>
                                <th>Nama Pengunjung</th>
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

                                    <a href="{{ route('pengunjung.edit', $p->id) }}"
                                        class="edit-btn">

                                        Edit

                                    </a>

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

                                <td>
                                    {{ $p->nama_pengunjung }}
                                </td>

                                <td>
<<<<<<< HEAD
                                   {{ $p->kelas->nama_kelas ?? '-' }}
                                </td>

                                <td>
                                    {{ $p->kelas->jurusan ?? '-' }}
=======
                                    {{ $p->kelas->nama_kelas }}
                                </td>

                                <td>
                                    {{ $p->kelas->jurusan }}
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
                                </td>

                                <td>
                                    {{ $p->tanggal_kunjung }}
                                </td>

                                <td>
                                    {{ $p->waktu_kunjung }}
                                </td>

                                <td>
                                    {{ $p->keperluan }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="7"
                                    class="empty-data">

                                    Belum ada data pengunjung

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>