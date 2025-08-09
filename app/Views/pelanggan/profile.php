<?= $this->extend('templates/main') ?>
<?= $this->section('content') ?>
<!-- Profile Section -->
<section class="pt-24 sm:pt-28 lg:pt-32 pb-12 sm:pb-14 lg:pb-16">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-10 lg:mb-12" data-aos="fade-up">
            <h1 class="text-2xl sm:text-3xl font-bold gradient-text mb-2">Profil Saya</h1>
            <p class="text-sm sm:text-base text-gray-600">Kelola informasi profil Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Profile Card -->
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 card-hover">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 mb-3 sm:mb-4 rounded-full bg-gradient-to-br from-[#E74C3C] to-[#F1C40F] p-1">
                            <div class="w-full h-full rounded-full overflow-hidden bg-white">
                                <img src="<?= base_url('assets/images/default-avatar.jpg') ?>" alt="Foto Profil"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($pelanggan['nama_lengkap']) ?>&background=E74C3C&color=fff&size=256'">
                            </div>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800 mt-2" id="profile-name"><?= $pelanggan['nama_lengkap'] ?></h2>
                        <p class="text-xs sm:text-sm text-gray-500 mb-2">ID: <?= isset($pelanggan['idpelanggan']) ? $pelanggan['idpelanggan'] : 'Belum tersedia' ?></p>
                        <p class="text-xs sm:text-sm text-gray-500">Member sejak: <?= date('d F Y', strtotime($pelanggan['created_at'])) ?></p>

                        <div class="w-full mt-4 sm:mt-6 border-t border-gray-200 pt-3 sm:pt-4">
                            <a href="<?= base_url('customer/booking') ?>" class="w-full btn-primary px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg inline-flex items-center justify-center transform hover:scale-105 transition-all duration-300 mb-2 sm:mb-3 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Riwayat Booking</span>
                            </a>
                            <a href="<?= base_url('customer/change-password') ?>" class="w-full bg-gray-100 hover:bg-gray-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg inline-flex items-center justify-center transition-all duration-300 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Ubah Password</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 card-hover">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-gray-200">Informasi Pribadi</h3>

                    <!-- Alert untuk success & error akan ditampilkan di sini -->
                    <div id="alert-success" class="p-3 sm:p-4 mb-4 sm:mb-6 text-xs sm:text-sm text-green-700 bg-green-100 rounded-lg hidden" role="alert">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="success-message"></span>
                        </div>
                    </div>

                    <div id="alert-error" class="p-3 sm:p-4 mb-4 sm:mb-6 text-xs sm:text-sm text-red-700 bg-red-100 rounded-lg hidden" role="alert">
                        <div class="flex">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div id="error-message-container">
                                <ul class="list-disc pl-4 sm:pl-5 space-y-1" id="error-messages">
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form id="profileForm">
                        <?= csrf_field() ?>
                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="col-span-full">
                                <label for="nama_lengkap" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= $pelanggan['nama_lengkap'] ?>" required
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
                            </div>

                            <div>
                                <label for="jeniskelamin" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Jenis Kelamin</label>
                                <select id="jeniskelamin" name="jeniskelamin"
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" <?= isset($pelanggan['jeniskelamin']) && $pelanggan['jeniskelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= isset($pelanggan['jeniskelamin']) && $pelanggan['jeniskelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?= isset($pelanggan['tanggal_lahir']) ? $pelanggan['tanggal_lahir'] : '' ?>"
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
                            </div>

                            <div>
                                <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email</label>
                                <input type="email" id="email" value="<?= $pelanggan['email'] ?>" disabled
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-gray-300 bg-gray-50">
                            </div>

                            <div>
                                <label for="no_hp" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">No. HP</label>
                                <input type="text" id="no_hp" name="no_hp" value="<?= isset($pelanggan['no_hp']) ? $pelanggan['no_hp'] : '' ?>"
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
                            </div>

                            <div class="col-span-full">
                                <label for="alamat" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3"
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent resize-none"><?= isset($pelanggan['alamat']) ? $pelanggan['alamat'] : '' ?></textarea>
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-0 sm:justify-end">
                            <button type="submit" id="saveButton" class="w-full sm:w-auto btn-primary px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg inline-flex items-center justify-center transform hover:scale-105 transition-all duration-300">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->section('custom_script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profileForm');
        const saveButton = document.getElementById('saveButton');
        const alertSuccess = document.getElementById('alert-success');
        const alertError = document.getElementById('alert-error');
        const successMessage = document.getElementById('success-message');
        const errorMessages = document.getElementById('error-messages');
        const profileName = document.getElementById('profile-name');

        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Tampilkan loading state
            saveButton.disabled = true;
            saveButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `;

            // Sembunyikan alert
            alertSuccess.classList.add('hidden');
            alertError.classList.add('hidden');

            const formData = new FormData(profileForm);

            fetch('<?= base_url('customer/update-profil') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Kembalikan status button
                    saveButton.disabled = false;
                    saveButton.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                `;

                    if (data.status === 'success') {
                        // Tampilkan success alert
                        alertSuccess.classList.remove('hidden');
                        successMessage.textContent = data.message;

                        // Update nama di profile card
                        profileName.textContent = formData.get('nama_lengkap');

                        // Scroll ke alert
                        alertSuccess.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        // Sembunyikan alert setelah 5 detik
                        setTimeout(() => {
                            alertSuccess.classList.add('hidden');
                        }, 5000);
                    } else {
                        // Tampilkan error alert
                        alertError.classList.remove('hidden');
                        errorMessages.innerHTML = '';

                        // Tambahkan semua pesan error
                        if (typeof data.errors === 'object') {
                            for (const field in data.errors) {
                                const li = document.createElement('li');
                                li.textContent = data.errors[field];
                                errorMessages.appendChild(li);
                            }
                        } else {
                            const li = document.createElement('li');
                            li.textContent = data.message || 'Terjadi kesalahan saat memperbarui profil';
                            errorMessages.appendChild(li);
                        }

                        // Scroll ke alert
                        alertError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                })
                .catch(error => {
                    // Kembalikan status button
                    saveButton.disabled = false;
                    saveButton.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                `;

                    // Tampilkan error
                    alertError.classList.remove('hidden');
                    errorMessages.innerHTML = '';
                    const li = document.createElement('li');
                    li.textContent = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
                    errorMessages.appendChild(li);

                    // Scroll ke alert
                    alertError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    console.error('Error:', error);
                });
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>