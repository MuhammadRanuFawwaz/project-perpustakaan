<div class="profile-modal" id="profileModal">

    <div class="profile-modal-content profile-modal-wide">

        <div class="profile-modal-header">
            <h2>Edit Akun</h2>

            <button type="button"
                class="profile-modal-close"
                onclick="closeProfileModal()">
                &times;
            </button>
        </div>

        <form method="POST"
            action="{{ route('profile.update') }}"
            enctype="multipart/form-data"
            class="profile-modal-body profile-grid">

            @csrf
            @method('PATCH')

            @if ($errors->any())
            <div class="profile-error-box">

                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach

            </div>
            @endif

            @if (session('status') === 'profile-updated')
            <div class="profile-success-box">
                Perubahan berhasil disimpan.
            </div>
            @endif

            <div class="profile-form-box">

                <h3>Informasi Akun</h3>

                <div class="profile-photo-box">

                    @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                        class="profile-photo-preview"
                        alt="Foto Profile">
                    @else
                    <div class="profile-photo-placeholder">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    @endif

                    <div class="form-group">
                        <label>Foto Profile</label>
                        <input type="file" name="photo" accept="image/*">
                    </div>

                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text"
                        name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required>
                </div>

            </div>

            <div class="profile-form-box">

                <h3>Ubah Password</h3>

                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password"
                        name="current_password"
                        autocomplete="off">
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password"
                        name="password"
                        autocomplete="off">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password"
                        name="password_confirmation"
                        autocomplete="off">
                </div>

            </div>

            <div class="profile-submit-full">
                <button type="submit" class="profile-save-btn">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>