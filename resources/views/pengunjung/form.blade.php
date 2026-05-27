<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengunjung</title>

    <link rel="stylesheet" href="{{ asset('css/pengunjung-form.css') }}">
</head>

<body>

    <div class="login-box">
        <img src="{{ asset('images/Smkn1Tarumajaya.png') }}" class="logo">

        <h2>PERPUSTAKAAN SMKN 1 TARUMAJAYA</h2>
        <p>Silakan isi data kunjungan</p>
        <div class="content-layout">
            <div class="left-side">

                <form action="{{ route('pengunjung.form.store') }}" method="POST">
                    @csrf

                    @if (session('success'))
                        <div class="alert-success" id="success-alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('pengunjung.form.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>NIS / NIP</label>

                            <div class="nis-group">

                                <input type="text" name="nomor_induk" class="form-control"
                                    placeholder="Masukkan NIS / NIP" autofocus required>

                                <button type="button" id="btnCek" class="btn-submit btn-cek">
                                    Cek
                                </button>

                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-col">

                                <div class="form-group">
                                    <label>Status</label>

                                    <input type="text" id="status" class="form-control"
                                        placeholder="Status otomatis" readonly>
                                </div>

                            </div>

                            <div class="form-col">

                                <div class="form-group">
                                    <label>Nama Pengunjung</label>

                                    <input type="text" id="nama_pengunjung" class="form-control"
                                        placeholder="Nama otomatis" readonly>
                                </div>

                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-col">

                                <div class="form-group">
                                    <label>Kelas</label>

                                    <input type="text" id="kelas" class="form-control"
                                        placeholder="Kelas otomatis" readonly>
                                </div>

                            </div>

                            <div class="form-col">

                                <div class="form-group">
                                    <label>Jurusan</label>

                                    <input type="text" id="jurusan" class="form-control"
                                        placeholder="Jurusan otomatis" readonly>
                                </div>

                            </div>

                        </div>

                        <div class="form-group">
                            <label>Keperluan</label>

                            <textarea name="keperluan" class="form-control textarea-keperluan" placeholder="Keperluan" required></textarea>
                        </div>

                        <button type="submit" class="btn-submit btn-full">
                            Simpan
                        </button>

                    </form>

                    <div class="footer-text">
                        © 2026 SMKN 1 TARUMAJAYA
                    </div>
            </div>

            <script src="{{ asset('js/pengunjung-form.js') }}"></script>

</body>

</html>
