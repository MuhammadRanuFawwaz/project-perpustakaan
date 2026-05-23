<div class="modal"
    id="guruModal">

    <div class="modal-content">

        <div class="modal-header">

            <h2>
                {{ isset($editGuru) ? 'Edit Guru' : 'Tambah Guru' }}
            </h2>

            <span class="close"
                onclick="closeGuruModal()">

                &times;

            </span>

        </div>

        <form method="POST"
            action="{{ isset($editGuru) ? route('master.guru.update', $editGuru->id) : route('master.guru.store') }}">

            @csrf

            @if(isset($editGuru))
            @method('PUT')
            @endif

            <div class="form-group">

                <label>NIP</label>

                <input type="text"
                    name="nip"
                    placeholder="Masukkan NIP"
                    value="{{ old('nip', $editGuru->nip ?? '') }}"
                    required>

            </div>

            <div class="form-group">

                <label>Nama Guru</label>

                <input type="text"
                    name="nama_guru"
                    placeholder="Masukkan nama guru"
                    value="{{ old('nama_guru', $editGuru->nama_guru ?? '') }}"
                    required>

            </div>

            <button type="submit"
                class="save-btn">

                {{ isset($editGuru) ? 'Update' : 'Simpan' }}

            </button>

        </form>

    </div>

</div>