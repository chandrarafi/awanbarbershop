<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Edit Paket</h1>
        <p class="mb-0 text-secondary">Edit data paket yang sudah ada</p>
    </div>
    <a href="<?= base_url('admin/paket') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<!-- Content Row -->
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Alert untuk error umum -->
                <div id="generalError" class="alert alert-danger" style="display: none;">
                    <ul class="mb-0"></ul>
                </div>

                <form id="formPaket" enctype="multipart/form-data">
                    <input type="hidden" id="idpaket" name="idpaket">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="namapaket" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-box"></i></span>
                                <input type="text" class="form-control" id="namapaket" name="namapaket" required>
                                <div class="invalid-feedback" id="namapaketError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" name="harga" required>
                                <div class="invalid-feedback" id="hargaError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="durasi" class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="number" class="form-control" id="durasi" name="durasi" required min="15" value="60">
                                <div class="invalid-feedback" id="durasiError"></div>
                            </div>
                            <small class="text-muted">Masukkan durasi layanan dalam menit (minimal 15 menit)</small>
                        </div>
                        <div class="col-md-6">
                            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                                <div class="invalid-feedback" id="deskripsiError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gambar" class="form-label">Gambar Paket</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this)">
                                <div class="invalid-feedback" id="gambarError"></div>
                            </div>
                            <small class="text-muted">Format: JPG, JPEG, PNG. Maks: 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preview Gambar</label>
                            <div class="border rounded p-2 text-center">
                                <img id="imagePreview" src="<?= base_url('assets/images/imgnotfound.jpg') ?>" alt="Preview" class="img-fluid" style="max-height: 200px;">
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSimpan">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        // Preview gambar yang dipilih
        window.previewImage = function(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#currentImageInfo').hide();
                }

                // Validasi ukuran dan tipe file
                const maxSize = 2 * 1024 * 1024; // 2MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                if (file.size > maxSize) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    Swal.fire({
                        title: 'Peringatan',
                        text: `Ukuran file (${fileSizeMB}MB) terlalu besar (maksimal 2MB)`,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    input.value = '';
                    return;
                }

                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        title: 'Peringatan',
                        text: 'Format file tidak didukung (gunakan JPG, JPEG, atau PNG)',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    input.value = '';
                    return;
                }

                reader.readAsDataURL(file);
            } else {
                const paket = response.data;
                if (paket && paket.image) {
                    $('#imagePreview').attr('src', '<?= base_url('uploads/paket') ?>/' + paket.image);
                    $('#currentImageName').text(paket.image);
                    $('#currentImageInfo').show();

                    // Tambahkan informasi gambar saat ini
                    const currentImageHtml = `
                        <div class="alert alert-info mt-2">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Gambar saat ini: <strong>${paket.image}</strong></small>
                        </div>`;
                    $('#gambar').closest('.input-group').after(currentImageHtml);
                } else {
                    $('#imagePreview').attr('src', '<?= base_url('assets/images/imgnotfound.jpg') ?>');
                    $('#currentImageInfo').hide();
                }
            }
        }

        // Reset form errors
        function resetErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            $('#generalError').hide().find('ul').empty();
            // Hapus alert informasi gambar saat ini jika ada
            $('#gambar').closest('.input-group').next('.alert-info').remove();
        }

        // Display validation errors
        function showErrors(errors) {
            if (typeof errors === 'object' && errors !== null) {
                let hasFieldErrors = false;
                let generalErrors = [];

                $.each(errors, function(field, message) {
                    const $field = $('#' + field);
                    if ($field.length) {
                        $field.addClass('is-invalid');
                        $('#' + field + 'Error').text(message);
                        hasFieldErrors = true;
                    } else {
                        generalErrors.push(message);
                    }
                });

                if (generalErrors.length > 0) {
                    const $generalError = $('#generalError');
                    const $errorList = $generalError.find('ul');
                    generalErrors.forEach(function(error) {
                        $errorList.append('<li>' + error + '</li>');
                    });
                    $generalError.show();
                }
            } else if (typeof errors === 'string') {
                $('#generalError').show().find('ul').append('<li>' + errors + '</li>');
            }
        }

        // Load data paket
        const paketId = '<?= $idpaket ?>'; // ID paket dari controller
        $.ajax({
            url: '<?= base_url('admin/paket/getById') ?>/' + paketId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const paket = response.data;
                    $('#idpaket').val(paket.idpaket);
                    $('#namapaket').val(paket.namapaket);
                    $('#deskripsi').val(paket.deskripsi);
                    $('#harga').val(paket.harga);
                    $('#durasi').val(paket.durasi);

                    // Set preview gambar jika ada
                    if (paket.image) {
                        $('#imagePreview').attr('src', '<?= base_url('uploads/paket') ?>/' + paket.image);
                        $('#currentImageName').text(paket.image);
                        $('#currentImageInfo').show();

                        // Tambahkan informasi gambar saat ini
                        const currentImageHtml = `
                            <div class="alert alert-info mt-2">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Gambar saat ini: <strong>${paket.image}</strong></small>
                            </div>`;
                        $('#gambar').closest('.input-group').after(currentImageHtml);
                    } else {
                        $('#imagePreview').attr('src', '<?= base_url('assets/images/imgnotfound.jpg') ?>');
                        $('#currentImageInfo').hide();
                    }
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal memuat data paket',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '<?= base_url('admin/paket') ?>';
                        }
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Gagal memuat data paket',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= base_url('admin/paket') ?>';
                    }
                });
            }
        });

        // Form Submit
        $('#btnSimpan').on('click', function(e) {
            e.preventDefault();
            resetErrors();

            // Validasi form
            let isValid = true;
            let errors = {};

            // Validasi nama paket
            if (!$('#namapaket').val().trim()) {
                errors.namapaket = 'Nama paket harus diisi';
                isValid = false;
            }

            // Validasi deskripsi
            if (!$('#deskripsi').val().trim()) {
                errors.deskripsi = 'Deskripsi harus diisi';
                isValid = false;
            }

            // Validasi harga
            if (!$('#harga').val()) {
                errors.harga = 'Harga harus diisi';
                isValid = false;
            } else if ($('#harga').val() <= 0) {
                errors.harga = 'Harga harus lebih dari 0';
                isValid = false;
            }

            // Validasi durasi
            if (!$('#durasi').val()) {
                errors.durasi = 'Durasi harus diisi';
                isValid = false;
            } else if ($('#durasi').val() < 15) {
                errors.durasi = 'Durasi minimal 15 menit';
                isValid = false;
            }

            // Validasi gambar jika ada
            const gambarInput = $('#gambar')[0];
            if (gambarInput.files && gambarInput.files.length > 0) {
                const file = gambarInput.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB dalam bytes
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                if (file.size > maxSize) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    errors.gambar = `Ukuran file (${fileSizeMB}MB) terlalu besar (maksimal 2MB)`;
                    isValid = false;
                }

                if (!allowedTypes.includes(file.type)) {
                    errors.gambar = 'Format file tidak didukung (gunakan JPG, JPEG, atau PNG)';
                    isValid = false;
                }
            }

            if (!isValid) {
                showErrors(errors);
                return;
            }

            // Buat FormData object untuk mengirim file
            const formData = new FormData($('#formPaket')[0]);

            // Kirim data ke server
            $.ajax({
                url: '<?= base_url('admin/paket/update') ?>/' + $('#idpaket').val(),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btnSimpan').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '<?= base_url('admin/paket') ?>';
                            }
                        });
                    } else {
                        showErrors(response.errors || response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                    if (xhr.responseJSON) {
                        errorMessage = xhr.responseJSON.message || errorMessage;
                        showErrors(xhr.responseJSON.errors || errorMessage);
                    } else {
                        showErrors(errorMessage);
                    }
                },
                complete: function() {
                    $('#btnSimpan').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Perubahan');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>