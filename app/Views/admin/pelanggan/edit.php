<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Edit Pelanggan</h1>
        <p class="mb-0 text-secondary">Edit data pelanggan dalam sistem</p>
    </div>
    <a href="<?= base_url('admin/pelanggan') ?>" class="btn btn-secondary">
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

                <form id="formPelanggan">
                    <input type="hidden" id="id" name="id" value="<?= $pelanggan['id'] ?>">

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="createUser" name="createUser" value="1" <?= !empty($pelanggan['user_id']) ? 'checked disabled' : '' ?>>
                                <label class="form-check-label" for="createUser">
                                    <?= !empty($pelanggan['user_id']) ? 'Pelanggan sudah memiliki akun user' : 'Buatkan akun user untuk pelanggan ini' ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form User Account -->
                    <div id="userAccountForm" style="display: <?= !empty($pelanggan['user_id']) ? 'block' : 'none' ?>;">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $pelanggan['email'] ?? '' ?>" <?= !empty($pelanggan['user_id']) ? 'readonly' : '' ?>>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= $pelanggan['username'] ?? '' ?>" <?= !empty($pelanggan['user_id']) ? 'readonly' : '' ?>>
                                <div class="invalid-feedback" id="usernameError"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="password" class="form-label">Password <?= empty($pelanggan['user_id']) ? '<span class="text-danger">*</span>' : '' ?></label>
                                <input type="password" class="form-control" id="password" name="password" <?= !empty($pelanggan['user_id']) ? 'placeholder="Kosongkan jika tidak ingin mengubah password"' : '' ?>>
                                <div class="invalid-feedback" id="passwordError"></div>
                                <?php if (!empty($pelanggan['user_id'])): ?>
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="idpelanggan" class="form-label">ID Pelanggan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                <input type="text" class="form-control" id="idpelanggan" name="idpelanggan" value="<?= $pelanggan['idpelanggan'] ?>" readonly>
                                <div class="invalid-feedback" id="idpelangganError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $pelanggan['nama_lengkap'] ?>" required>
                                <div class="invalid-feedback" id="nama_lengkapError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jeniskelamin" class="form-label">Jenis Kelamin</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select class="form-select" id="jeniskelamin" name="jeniskelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" <?= $pelanggan['jeniskelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= $pelanggan['jeniskelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                                <div class="invalid-feedback" id="jeniskelaminError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No HP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $pelanggan['no_hp'] ?>">
                                <div class="invalid-feedback" id="no_hpError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $pelanggan['tanggal_lahir'] ?>">
                                <div class="invalid-feedback" id="tanggal_lahirError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="alamat" class="form-label">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= $pelanggan['alamat'] ?></textarea>
                                <div class="invalid-feedback" id="alamatError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSimpan">
                            <i class="bi bi-save me-1"></i> Simpan
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
        // Toggle user account form
        $('#createUser').change(function() {
            if (!$(this).prop('disabled')) {
                $('#userAccountForm').slideToggle(this.checked);
                if (this.checked) {
                    $('#email, #username, #password').prop('required', true);
                } else {
                    $('#email, #username, #password').prop('required', false);
                }
            }
        });

        // Reset form errors
        function resetErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            $('#generalError').hide().find('ul').empty();
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

        // Form Submit
        $('#btnSimpan').on('click', function(e) {
            e.preventDefault();
            resetErrors();

            // Disable button and show loading state
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            var formData = new FormData($('#formPelanggan')[0]);

            // Pastikan nilai createUser terkirim dengan benar
            if (!$('#createUser').is(':checked')) {
                formData.delete('createUser');
            }

            // Log form data untuk debugging
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            $.ajax({
                url: '<?= base_url('admin/pelanggan/update') ?>/' + $('#id').val(),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href = '<?= base_url('admin/pelanggan') ?>';
                        });
                    } else {
                        showErrors(response.errors || {
                            general: response.message
                        });
                        $('#btnSimpan').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        showErrors(response.errors);
                    } else {
                        $('#generalError').show().find('ul').html('<li>Terjadi kesalahan saat menyimpan data</li>');
                    }
                    $('#btnSimpan').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>