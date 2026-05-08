<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengunjung.css') }}">

    <div class="dashboard-container">

        @include('layouts.sidebar')

        <div class="main-content" id="mainContent">

            <div class="content">

                <div class="form-box">

                    <h1>Tambah Pengunjung</h1>

                    <form action="{{ route('pengunjung.store') }}"
                        method="POST">

                        @csrf

                        @include('pengunjung.partials.form')

                        <button type="submit"
                            class="save-btn">

                            Simpan

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script src="{{ asset('js/app-layout.js') }}"></script>

</x-app-layout>