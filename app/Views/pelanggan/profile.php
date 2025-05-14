<?= $this->extend('pelanggan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<?= base_url('assets/img/default-avatar.png') ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                    <h5 class="my-3"><?= $pelanggan['nama_lengkap'] ?></h5>
                    <p class="text-muted mb-1">ID: <?= $pelanggan['idpelanggan'] ?></p>
                    <p class="text-muted mb-4">Member sejak: <?= date('d F Y', strtotime($pelanggan['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Profil Saya</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('pelanggan/update-profile') ?>" method="post">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <p class="mb-0">Nama Lengkap</p>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nama_lengkap" value="<?= $pelanggan['nama_lengkap'] ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <p class="mb-0">Jenis Kelamin</p>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-select" name="jeniskelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" <?= $pelanggan['jeniskelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= $pelanggan['jeniskelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <p class="mb-0">Tanggal Lahir</p>
                            </div>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tanggal_lahir" value="<?= $pelanggan['tanggal_lahir'] ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <p class="mb-0">No. HP</p>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="no_hp" value="<?= $pelanggan['no_hp'] ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <p class="mb-0">Alamat</p>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alamat" rows="3"><?= $pelanggan['alamat'] ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>