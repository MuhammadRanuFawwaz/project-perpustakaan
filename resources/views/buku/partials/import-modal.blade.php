<div class="modal"
    id="importModal">

    <div class="modal-content">

        <div class="modal-header">

            <h2>
                Import Excel Buku
            </h2>

            <span class="close"
                onclick="closeImportModal()">

                &times;

            </span>

        </div>

        <form method="POST"
            action="{{ route('buku.import') }}"
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