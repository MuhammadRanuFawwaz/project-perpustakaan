<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengunjung.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-modal.css') }}">

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

                <div class="page-header">
                    <h1>Hak Akses Admin</h1>
                </div>

                <div class="table-box">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Admin</th>
                                <th>Email</th>
                                <th>Edit Peminjaman</th>
                                <th>Hapus Peminjaman</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($admins as $admin)
                            <tr>
                                <form action="{{ route('master.akses-admin.update', $admin->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>

                                    <td>
                                        <input type="checkbox"
                                            name="can_edit_peminjaman"
                                            value="1"
                                            {{ $admin->can_edit_peminjaman ? 'checked' : '' }}>
                                    </td>

                                    <td>
                                        <input type="checkbox"
                                            name="can_delete_peminjaman"
                                            value="1"
                                            {{ $admin->can_delete_peminjaman ? 'checked' : '' }}>
                                    </td>

                                    <td>
                                        <button type="submit" class="edit-btn">
                                            Simpan Akses
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="empty-data">
                                    Belum ada akun admin.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>