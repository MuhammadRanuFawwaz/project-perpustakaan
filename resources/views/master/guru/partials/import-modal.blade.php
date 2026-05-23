<div class="modal"
    id="importGuruModal">

    <div class="modal-content">

        <div class="modal-header">

            <h2>
                Import Excel Guru
            </h2>

            <span class="close"
                onclick="closeImportGuruModal()">

                &times;

            </span>

        </div>

        <form method="POST"
            action="{{ route('master.guru.import') }}"
            enctype="multipart/form-data">

            @csrf

            <div class="form-group">

                <label>File Excel</label>

                <input type="file"
                    name="file_excel"
                    accept=".xlsx,.xls"
                    required>

            </div>

            <button type="submit"
                class="save-btn">

                Import Excel

            </button>

        </form>

    </div>

</div>