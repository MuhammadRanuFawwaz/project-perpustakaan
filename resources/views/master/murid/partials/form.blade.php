<div class="modal"
    id="muridModal">

    <div class="modal-content">

        <div class="modal-header">

            <h2>
                {{ isset($editMurid) ? 'Edit Murid' : 'Tambah Murid' }}
            </h2>

            <span class="close"
                onclick="closeMuridModal()">

                &times;

            </span>

        </div>

        <form method="POST"
            action="{{ isset($editMurid) ? route('master.murid.update', $editMurid->id) : route('master.murid.store') }}">

            @csrf

            @if(isset($editMurid))
            @method('PUT')
            @endif

            <div class="form-group">

                <label>NIS</label>

                <input type="text"
                    name="nis"
                    placeholder="Masukkan NIS"
                    value="{{ old('nis', $editMurid->nis ?? '') }}"
                    required>

            </div>

            <div class="form-group">

                <label>Nama Murid</label>

                <input type="text"
                    name="nama_murid"
                    placeholder="Masukkan nama murid"
                    value="{{ old('nama_murid', $editMurid->nama_murid ?? '') }}"
                    required>

            </div>

            <div class="form-group">

                <label>Kelas</label>

                <select name="id_kelas"
                    class="select2-modal"
                    required>

                    <option value="">-- Pilih Kelas --</option>

                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}"
                        {{ old('id_kelas', $editMurid->id_kelas ?? '') == $k->id ? 'selected' : '' }}>

                        {{ $k->nama_kelas }} - {{ $k->jurusan }}

                    </option>
                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status"
                    class="select2-modal"
                    required>

                    <option value="aktif"
                        {{ old('status', $editMurid->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="lulus"
                        {{ old('status', $editMurid->status ?? '') == 'lulus' ? 'selected' : '' }}>
                        Lulus
                    </option>

                    <option value="nonaktif"
                        {{ old('status', $editMurid->status ?? '') == 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Tahun Ajaran</label>

                <input type="text"
                    name="tahun_ajaran"
                    placeholder="Contoh: 2025/2026"
                    value="{{ old('tahun_ajaran', $editMurid->tahun_ajaran ?? '') }}">

            </div>

            <button type="submit"
                class="save-btn">

                {{ isset($editMurid) ? 'Update' : 'Simpan' }}

            </button>

        </form>

    </div>

</div>