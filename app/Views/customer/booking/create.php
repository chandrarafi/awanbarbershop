<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<!-- Modal untuk data profil tidak lengkap -->
<?php if (!$isProfileComplete): ?>
    <div id="profileIncompleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Data Profil Belum Lengkap
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Untuk melakukan booking, Anda perlu melengkapi data profil terlebih dahulu. Data yang belum lengkap:
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a href="<?= site_url('customer/profil') ?>" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Lengkapi Profil Sekarang
                    </a>
                    <a href="<?= site_url('/') ?>" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Sukses Booking -->
<div id="booking-success-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="relative bg-white p-6 rounded-xl shadow-2xl max-w-md w-11/12 transform transition-all">
        <div class="text-center">
            <div class="w-24 h-24 mx-auto mb-5 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-14 h-14 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold mb-3 text-gray-800">Booking Berhasil!</h2>
            <p class="text-gray-600 mb-6 text-lg">Booking Anda berhasil dibuat. Anda akan dialihkan ke halaman pembayaran dalam beberapa saat.</p>
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div id="countdown-bar" class="bg-green-500 h-3 rounded-full"></div>
            </div>
            <div class="text-gray-500 text-sm"><span id="countdown-timer">3</span> detik...</div>
        </div>
    </div>
</div>

<div class="py-16">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-3xl md:text-4xl font-bold gradient-text">Form Booking</h1>
            <p class="text-gray-300 mt-3">Silahkan isi form booking untuk mendapatkan layanan terbaik kami</p>
            <div class="w-20 h-1 bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] mx-auto mt-4 rounded-full"></div>
        </div>

        <?php if (!$isProfileComplete): ?>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Data profil Anda belum lengkap. Harap lengkapi data profil Anda terlebih dahulu.
                            <a href="<?= site_url('customer/profil') ?>" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                Lengkapi profil sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Informasi Batas Waktu Pembayaran -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 rounded-lg border border-amber-200 shadow-sm mb-6">
            <div class="flex items-center">
                <div class="bg-amber-100 rounded-full p-2 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-medium text-gray-800">Perhatian: Batas Waktu Pembayaran</h3>
                    <p class="text-sm text-gray-600 mt-1">Setelah booking berhasil dibuat, Anda memiliki waktu <span class="font-semibold">5 menit</span> untuk menyelesaikan pembayaran. Jika melewati batas waktu, booking akan otomatis dibatalkan.</p>
                </div>
            </div>
        </div>

        <div class="form-card rounded-xl shadow-xl p-6 md:p-8 animated-border" data-aos="zoom-in" data-aos-delay="200">
            <div id="booking-alert" class="hidden mb-6 p-4 rounded-lg shadow-sm border"></div>

            <form id="bookingForm" class="space-y-6" enctype="multipart/form-data">
                <!-- Data Pelanggan -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm form-section">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-orange-50 p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#E74C3C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Data Pelanggan</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-gray-700 font-medium">Nama</label>
                            <input type="text" class="bg-white border border-gray-300 rounded-lg w-full p-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all" value="<?= $pelanggan['nama_lengkap'] ?>" readonly>
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700 font-medium">No. HP</label>
                            <input type="text" class="bg-white border border-gray-300 rounded-lg w-full p-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all" value="<?= $pelanggan['no_hp'] ?>" readonly>
                        </div>
                    </div>
                </div>

                <!-- Data Booking -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm form-section">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-blue-50 p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Layanan</h2>
                    </div>

                    <div class="space-y-5">
                        <?php if ($selectedPaket): ?>
                            <!-- Jika paket sudah dipilih dari landing page -->
                            <div>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-start">
                                    <div class="flex-shrink-0 mr-4">
                                        <?php if (!empty($selectedPaket['gambar'])): ?>
                                            <img src="<?= base_url('uploads/paket/' . $selectedPaket['gambar']) ?>" alt="<?= $selectedPaket['namapaket'] ?>" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                        <?php elseif (!empty($selectedPaket['image'])): ?>
                                            <img src="<?= base_url('uploads/paket/' . $selectedPaket['image']) ?>" alt="<?= $selectedPaket['namapaket'] ?>" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                        <?php else: ?>
                                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-lg text-gray-800"><?= $selectedPaket['namapaket'] ?></h3>
                                        <p class="text-gray-600 text-sm mb-2"><?= $selectedPaket['deskripsi'] ?? 'Tidak ada deskripsi' ?></p>
                                        <p class="font-bold text-indigo-600">Rp. <?= number_format($selectedPaket['harga'], 0, ',', '.') ?></p>
                                        <div class="mt-2">
                                            <button type="button" id="addMorePaket" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Tambah Paket Lain
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="selectedPakets" name="selectedPakets" value="<?= $selectedPaket['idpaket'] ?>" data-harga="<?= $selectedPaket['harga'] ?>" data-durasi="<?= $selectedPaket['durasi'] ?? 60 ?>">
                            </div>
                        <?php else: ?>
                            <!-- Jika paket belum dipilih, tampilkan pilihan paket -->
                            <div>
                                <div id="paketContainer">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        <?php foreach ($paketList as $paket): ?>
                                            <div class="paket-card border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                                                data-id="<?= $paket['idpaket'] ?>"
                                                data-harga="<?= $paket['harga'] ?>"
                                                data-durasi="<?= $paket['durasi'] ?? 60 ?>">
                                                <div class="relative h-40 bg-gray-100">
                                                    <?php if (!empty($paket['gambar'])): ?>
                                                        <img src="<?= base_url('uploads/paket/' . $paket['gambar']) ?>" alt="<?= $paket['namapaket'] ?>"
                                                            class="w-full h-full object-cover">
                                                    <?php elseif (!empty($paket['image'])): ?>
                                                        <img src="<?= base_url('uploads/paket/' . $paket['image']) ?>" alt="<?= $paket['namapaket'] ?>"
                                                            class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="absolute bottom-0 right-0 bg-gradient-to-l from-indigo-600 to-indigo-500 text-white px-3 py-1 text-sm font-medium">
                                                        <?= $paket['durasi'] ?? 60 ?> menit
                                                    </div>
                                                </div>
                                                <div class="p-4">
                                                    <h3 class="font-semibold text-gray-800 mb-1"><?= $paket['namapaket'] ?></h3>
                                                    <p class="text-sm text-gray-600 line-clamp-2 mb-2"><?= $paket['deskripsi'] ?? 'Tidak ada deskripsi' ?></p>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-indigo-600 font-bold">Rp. <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                                                        <button type="button" class="add-paket-btn bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-full p-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div id="selectedPaketsContainer" class="mt-4 space-y-3"></div>
                                <input type="hidden" id="selectedPaketIds" name="selectedPakets" value="">
                            </div>
                        <?php endif; ?>

                        <div>
                            <label for="tanggal_booking" class="block mb-2 text-gray-700 font-medium">Pilih Tanggal</label>
                            <div class="relative">
                                <input type="date" id="tanggal_booking" name="tanggal_booking" class="bg-white border border-gray-300 rounded-lg w-full p-3 focus:ring-2 focus:ring-purple-300 focus:border-purple-500 transition-all" min="<?= date('Y-m-d') ?>" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pilihan Waktu -->
                <div id="timeSlotContainer" class="hidden">
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm form-section" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center mb-4">
                            <div class="rounded-full bg-yellow-50 p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Pilih Waktu</h2>
                        </div>

                        <div class="mb-4">
                            <div class="bg-gray-50 p-4 rounded-lg mb-5 border border-gray-200 shadow-sm">
                                <p id="bookingDateDisplay" class="text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Silakan pilih tanggal terlebih dahulu
                                </p>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3" id="timeSlotGrid">
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="09:00">09:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="10:00">10:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="11:00">11:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="12:00">12:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="13:00">13:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="14:00">14:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="15:00">15:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="16:00">16:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="17:00">17:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="18:00">18:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="19:00">19:00</div>
                                <div class="time-slot cursor-pointer py-3 px-1 text-center border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all font-medium" data-time="20:00">20:00</div>
                            </div>

                            <div class="flex flex-wrap items-center mt-4 text-sm bg-white p-3 rounded-lg border border-gray-100">
                                <div class="flex items-center mr-4 mb-2">
                                    <div class="w-4 h-4 bg-white border border-gray-300 mr-1 rounded"></div>
                                    <span>Tersedia</span>
                                </div>
                                <div class="flex items-center mr-4 mb-2">
                                    <div class="w-4 h-4 bg-green-500 mr-1 rounded"></div>
                                    <span>Dipilih</span>
                                </div>
                                <div class="flex items-center mr-4 mb-2">
                                    <div class="w-4 h-4 bg-red-200 mr-1 rounded"></div>
                                    <span>Tidak tersedia</span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <div class="w-4 h-4 bg-gray-200 mr-1 rounded"></div>
                                    <span>Waktu sudah lewat</span>
                                </div>
                            </div>
                            <input type="hidden" id="jamstart" name="jamstart" required>
                            <input type="hidden" id="jamend" name="jamend">
                        </div>
                    </div>
                </div>

                <!-- Pilihan Karyawan -->
                <div id="karyawanContainer" class="hidden">
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm form-section" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center mb-5">
                            <div class="rounded-full bg-green-50 p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Pilih Karyawan</h2>
                        </div>

                        <p class="text-gray-600 mb-5 bg-gray-50 p-3 rounded-lg border border-gray-200 shadow-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Silakan pilih salah satu karyawan yang tersedia
                        </p>

                        <div id="karyawanList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                            <!-- Karyawan akan ditambahkan melalui JavaScript -->
                        </div>
                        <input type="hidden" id="idkaryawan" name="idkaryawan" required>
                    </div>
                </div>

                <!-- Ringkasan Booking -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm form-section mt-6">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-green-50 p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Ringkasan Booking</h2>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 mb-1">Total Durasi:</p>
                                <p class="text-lg font-semibold text-gray-800" id="totalDurasi">
                                    <?php if (isset($selectedPaket['durasi'])): ?>
                                        <?= $selectedPaket['durasi'] ?> menit
                                    <?php else: ?>
                                        0 menit
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Total Harga:</p>
                                <p class="text-lg font-semibold text-indigo-600" id="totalHarga">
                                    <?php if (isset($selectedPaket['harga'])): ?>
                                        Rp. <?= number_format($selectedPaket['harga'], 0, ',', '.') ?>
                                    <?php else: ?>
                                        Rp. 0
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input untuk menyimpan total -->
                    <input type="hidden" id="total" name="total" value="<?= isset($selectedPaket['harga']) ? $selectedPaket['harga'] : 0 ?>">
                    <input type="hidden" id="durasi_total" name="durasi_total" value="<?= isset($selectedPaket['durasi']) ? $selectedPaket['durasi'] : 0 ?>">
                </div>

                <!-- Opsi Pembayaran -->
                <div class="mt-6 bg-white p-5 rounded-lg border border-gray-200 shadow-sm hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Opsi Pembayaran
                    </h3>
                    <p class="text-gray-600 mb-4 text-sm">Silakan pilih jenis pembayaran dan metode pembayaran yang diinginkan</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-gray-700 font-medium">Jenis Pembayaran</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="payment-option-wrapper">
                                    <input type="radio" id="payment_dp" name="jenis_pembayaran" value="DP" class="hidden payment-radio">
                                    <label for="payment_dp" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition w-full h-full">
                                        <div class="radio-circle mr-2 h-4 w-4 rounded-full border border-gray-400 flex items-center justify-center">
                                            <div class="radio-dot h-2 w-2 rounded-full bg-blue-600 hidden"></div>
                                        </div>
                                        <span class="text-gray-700">DP (50%)</span>
                                    </label>
                                </div>
                                <div class="payment-option-wrapper">
                                    <input type="radio" id="payment_full" name="jenis_pembayaran" value="Lunas" class="hidden payment-radio">
                                    <label for="payment_full" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition w-full h-full">
                                        <div class="radio-circle mr-2 h-4 w-4 rounded-full border border-gray-400 flex items-center justify-center">
                                            <div class="radio-dot h-2 w-2 rounded-full bg-blue-600 hidden"></div>
                                        </div>
                                        <span class="text-gray-700">Bayar Penuh</span>
                                    </label>
                                </div>
                            </div>
                            <div id="minPaymentInfo" class="mt-2 p-2 bg-yellow-50 border border-yellow-100 rounded text-sm text-gray-700 hidden">
                                Minimal pembayaran DP: <span id="minPayment" class="font-semibold text-orange-600">Rp 0</span>
                            </div>
                        </div>

                        <div id="metode-pembayaran-container" class="mt-5 hidden">
                            <label class="block mb-2 text-gray-700 font-medium">Metode Pembayaran</label>
                            <p class="text-sm text-gray-600 mb-2">Pilih salah satu metode pembayaran di bawah ini:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="method-option-wrapper">
                                    <input type="radio" id="method_transfer" name="metode_pembayaran" value="transfer" class="hidden method-radio">
                                    <label for="method_transfer" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition w-full h-full">
                                        <div class="radio-circle mr-2 h-4 w-4 rounded-full border border-gray-400 flex items-center justify-center">
                                            <div class="radio-dot h-2 w-2 rounded-full bg-blue-600 hidden"></div>
                                        </div>
                                        <span class="text-gray-700">Transfer Bank</span>
                                    </label>
                                </div>
                                <div class="method-option-wrapper">
                                    <input type="radio" id="method_qris" name="metode_pembayaran" value="qris" class="hidden method-radio">
                                    <label for="method_qris" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition w-full h-full">
                                        <div class="radio-circle mr-2 h-4 w-4 rounded-full border border-gray-400 flex items-center justify-center">
                                            <div class="radio-dot h-2 w-2 rounded-full bg-blue-600 hidden"></div>
                                        </div>
                                        <span class="text-gray-700">QRIS</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="paymentInfo" class="p-4 bg-blue-50 border border-blue-100 rounded-lg hidden">
                            <h4 class="font-medium text-gray-800 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Pembayaran
                            </h4>

                            <!-- Transfer Bank Info -->
                            <div id="transferInfo">
                                <p class="text-gray-600 text-sm mb-2">Silakan transfer ke rekening berikut:</p>
                                <div class="bg-white p-3 rounded border border-gray-200 mb-2">
                                    <p class="text-gray-800">Bank BCA</p>
                                    <p class="text-gray-800 font-semibold">1234567890</p>
                                    <p class="text-gray-800">a.n. Awan Barbershop</p>
                                </div>
                            </div>

                            <!-- QRIS Info -->
                            <div id="qrisInfo" class="text-center hidden">
                                <p class="text-gray-600 text-sm mb-2">Scan kode QRIS berikut untuk melakukan pembayaran:</p>
                                <div class="bg-white p-3 rounded border border-gray-200 mb-2 flex justify-center">
                                    <img src="<?= base_url('assets/images/qris-sample.png') ?>" alt="QRIS Code" class="max-w-[200px] h-auto" onerror="this.src='https://placehold.co/200x200/e2e8f0/64748b?text=QRIS+Code'">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                                    Upload Bukti Pembayaran
                                </label>
                                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100">
                                <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau GIF (Maks. 2MB)</p>

                                <!-- Preview Bukti Pembayaran -->
                                <div id="buktiPreviewContainer" class="mt-3 hidden">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                    <div class="relative">
                                        <img id="buktiPreview" src="#" alt="Bukti Pembayaran" class="max-h-60 rounded-lg border border-gray-200 shadow-sm">
                                        <button type="button" id="removeBukti" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between pt-6">
                    <a href="<?= site_url('/') ?>" class="flex items-center justify-center btn-secondary px-6 py-3 rounded-full hover:shadow-lg transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" id="btnSubmit" class="flex items-center justify-center btn-primary px-8 py-3 rounded-full hover:shadow-lg transition-all duration-300 transform hover:scale-105" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Booking Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom_script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Inisialisasi variabel global
        let totalHarga = 0;
        let totalDurasi = 0;
        let selectedPakets = [];

        // Debug mode untuk membantu pencarian bug
        const DEBUG = true;

        function debug(message, data) {
            if (DEBUG) console.log(message, data || '');
        }

        // Ketika paket dipilih dari landing page, langsung update summary
        if ($('#selectedPakets').length) {
            // Ambil data paket yang sudah dipilih
            const paketId = $('#selectedPakets').val();
            const paketHarga = parseFloat($('#selectedPakets').data('harga'));
            const paketDurasi = parseInt($('#selectedPakets').data('durasi') || 60);

            debug('Paket dari URL detected', {
                id: paketId,
                harga: paketHarga,
                durasi: paketDurasi
            });

            // Inisialisasi nilai awal
            totalHarga = paketHarga;
            totalDurasi = paketDurasi;

            // Update tampilan
            $('#totalHarga').text('Rp. ' + formatNumber(totalHarga));
            $('#totalDurasi').text(totalDurasi + ' menit');

            // Update hidden input untuk total
            $('#total').val(totalHarga);
            $('#durasi_total').val(totalDurasi);

            // Log untuk debugging
            debug('Inisialisasi paket dari URL', {
                id: paketId,
                harga: paketHarga,
                durasi: paketDurasi,
                totalHarga: totalHarga,
                totalDurasi: totalDurasi
            });

            // Tampilkan informasi durasi
            setTimeout(function() {
                updateDurasiInfo(true);
            }, 500);

            // Pastikan tombol Submit aktif jika paket sudah dipilih
            if (!$('#tanggal_booking').val()) {
                $('#btnSubmit').prop('disabled', true);
            }
        }

        // Event handler untuk tombol tambah paket
        // Hapus event handler yang mungkin sudah terdaftar sebelumnya untuk mencegah duplikasi
        $(document).off('click', '#addPaket, #addMorePaket');

        // Daftarkan event handler baru
        $(document).on('click', '#addPaket, #addMorePaket', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Tambah paket diklik');
            tambahPaket();

            // Pastikan tanggal booking tidak hilang dengan men-trigger event change
            // jika sudah ada tanggal yang dipilih sebelumnya
            if ($('#tanggal_booking').val()) {
                $('#tanggal_booking').trigger('change');
            }

            return false;
        });

        // Event handler untuk klik pada paket card
        $(document).on('click', '.paket-card', function(e) {
            // Jangan trigger jika yang diklik adalah tombol add
            if ($(e.target).closest('.add-paket-btn').length) {
                return;
            }

            const $card = $(this);
            const id = $card.data('id');
            const nama = $card.find('h3').text();
            const harga = $card.data('harga');
            const durasi = $card.data('durasi');

            tambahPaketKeSelected(id, nama, harga, durasi);
        });

        // Event handler untuk tombol add pada paket card
        $(document).on('click', '.add-paket-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $card = $(this).closest('.paket-card');
            const id = $card.data('id');
            const nama = $card.find('h3').text();
            const harga = $card.data('harga');
            const durasi = $card.data('durasi');

            tambahPaketKeSelected(id, nama, harga, durasi);
        });

        // Event handler untuk menghapus paket yang dipilih
        $(document).on('click', '.remove-selected-paket', function() {
            $(this).closest('.selected-paket-item').remove();
            hitungTotal();
        });

        // Event handler untuk tombol booking
        $('#bookingForm').on('submit', function(e) {
            e.preventDefault();

            // Debug form data sebelum submit
            console.log('Form data before submit:');
            console.log('Total:', $('#total').val(), 'Type:', typeof $('#total').val());
            console.log('Selected pakets:', $('#selectedPaketIds').val());
            console.log('Karyawan ID:', $('#idkaryawan').val());

            let isValid = true;
            let missingFields = [];

            // Validasi paket dipilih
            let paketSelected = false;

            // Cek jika ada paket yang dipilih
            if ($('#selectedPakets').length) {
                // Jika menggunakan UI lama
                paketSelected = true;
            } else if ($('.selected-paket-item').length > 0) {
                // Jika menggunakan UI baru
                paketSelected = true;
            }

            if (!paketSelected) {
                isValid = false;
                missingFields.push('paket layanan');
            }

            // Validasi tanggal booking
            if (!$('#tanggal_booking').val()) {
                isValid = false;
                missingFields.push('tanggal booking');
            }

            // Validasi jam mulai
            if (!$('#jamstart').val()) {
                isValid = false;
                missingFields.push('jam booking');
            }

            // Validasi karyawan
            const karyawanId = $('#idkaryawan').val();
            console.log('Validasi karyawan:', karyawanId); // Log untuk debugging

            if (!karyawanId) {
                isValid = false;
                missingFields.push('karyawan');
            }

            if (!isValid) {
                // Scroll ke bagian atas form
                $('html, body').animate({
                    scrollTop: $('#bookingForm').offset().top - 100
                }, 500);

                let messageHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Harap isi semua field yang diperlukan</h3>
                            <p class="mt-1 text-xs text-red-700">Field yang harus diisi: ${missingFields.join(', ')}</p>
                        </div>
                    </div>
                `;

                $('#booking-alert')
                    .removeClass('hidden bg-green-100 text-green-800 border-green-200')
                    .addClass('bg-red-100 text-red-800 border-red-200')
                    .html(messageHTML)
                    .fadeIn();
                return;
            }

            // Disable tombol submit untuk mencegah double submit
            $('#btnSubmit').prop('disabled', true).html(`
                <svg class="animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `);

            // Scroll ke bagian atas form
            $('html, body').animate({
                scrollTop: $('#bookingForm').offset().top - 100
            }, 500);

            // Ensure total is a number before submitting
            const totalValue = parseInt($('#total').val()) || 0;
            $('#total').val(totalValue);

            // Buat form data untuk upload file
            var formData = new FormData(this);

            // Log form data untuk debugging
            console.log('Form data:');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Double check total value after FormData creation
            console.log('Final total value:', formData.get('total'));

            $.ajax({
                url: '<?= site_url('customer/booking/store') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        // Hapus alert lama jika ada
                        $('#booking-alert').hide();

                        // Tampilkan modal sukses yang sudah ada di HTML
                        showSuccessModal(response.kdbooking);
                    } else {
                        let errorHTML = `
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Gagal membuat booking</h3>
                                    <p class="mt-2 text-sm text-red-700">${response.message}</p>
                                    ${response.debug ? `<pre class="mt-2 text-xs bg-gray-100 p-2 overflow-auto">${JSON.stringify(response.debug, null, 2)}</pre>` : ''}
                                </div>
                            </div>
                        `;

                        $('#booking-alert')
                            .removeClass('hidden bg-green-100 text-green-800 border-green-200')
                            .addClass('bg-red-100 text-red-800 border-red-200')
                            .html(errorHTML)
                            .fadeIn();

                        // Re-enable tombol submit
                        $('#btnSubmit').prop('disabled', false).html(`
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Booking Sekarang
                        `);
                    }
                },
                error: function() {
                    let errorHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                                <p class="mt-2 text-sm text-red-700">Sistem tidak dapat memproses booking Anda. Silakan coba lagi.</p>
                            </div>
                        </div>
                    `;

                    $('#booking-alert')
                        .removeClass('hidden bg-green-100 text-green-800 border-green-200')
                        .addClass('bg-red-100 text-red-800 border-red-200')
                        .html(errorHTML)
                        .fadeIn();

                    // Re-enable tombol submit
                    $('#btnSubmit').prop('disabled', false).html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Booking Sekarang
                    `);
                }
            });
        });

        // Setup custom radio buttons
        function setupCustomRadios() {
            // Setup jenis pembayaran radios
            $('.payment-radio').each(function() {
                const $radio = $(this);
                const $label = $('label[for="' + $radio.attr('id') + '"]');
                const $circle = $label.find('.radio-circle');
                const $dot = $circle.find('.radio-dot');

                // Initial state
                if ($radio.is(':checked')) {
                    $label.addClass('border-blue-500');
                    $circle.addClass('border-blue-600');
                    $dot.removeClass('hidden');
                }

                // Click handler for label
                $label.on('click', function() {
                    // Uncheck all radios in the group
                    $('.payment-radio').prop('checked', false);
                    $('.payment-radio').each(function() {
                        const $r = $(this);
                        const $l = $('label[for="' + $r.attr('id') + '"]');
                        $l.removeClass('border-blue-500');
                        $l.find('.radio-circle').removeClass('border-blue-600');
                        $l.find('.radio-dot').addClass('hidden');
                    });

                    // Check the clicked radio
                    $radio.prop('checked', true);
                    $label.addClass('border-blue-500');
                    $circle.addClass('border-blue-600');
                    $dot.removeClass('hidden');

                    // Trigger change event
                    $radio.trigger('change');
                });
            });

            // Setup metode pembayaran radios
            $('.method-radio').each(function() {
                const $radio = $(this);
                const $label = $('label[for="' + $radio.attr('id') + '"]');
                const $circle = $label.find('.radio-circle');
                const $dot = $circle.find('.radio-dot');

                // Initial state
                if ($radio.is(':checked')) {
                    $label.addClass('border-blue-500');
                    $circle.addClass('border-blue-600');
                    $dot.removeClass('hidden');
                }

                // Click handler for label
                $label.on('click', function() {
                    // Uncheck all radios in the group
                    $('.method-radio').prop('checked', false);
                    $('.method-radio').each(function() {
                        const $r = $(this);
                        const $l = $('label[for="' + $r.attr('id') + '"]');
                        $l.removeClass('border-blue-500');
                        $l.find('.radio-circle').removeClass('border-blue-600');
                        $l.find('.radio-dot').addClass('hidden');
                    });

                    // Check the clicked radio
                    $radio.prop('checked', true);
                    $label.addClass('border-blue-500');
                    $circle.addClass('border-blue-600');
                    $dot.removeClass('hidden');

                    // Trigger change event
                    $radio.trigger('change');
                });
            });
        }

        // Initialize custom radios
        setupCustomRadios();

        // Initialize form with no payment options selected
        // Hiding min payment info initially - will only show when payment type is selected

        // Format tanggal untuk tampilan
        function formatTanggal(tanggal) {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(tanggal).toLocaleDateString('id-ID', options);
        }

        // Format angka ke format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(angka);
        }

        // Ketika tanggal dipilih
        $('#tanggal_booking').on('change', function() {
            const selectedDate = $(this).val();

            // Jika tanggal dipilih, hilangkan alert
            if (selectedDate) {
                $('#booking-alert').fadeOut();
                $(this).removeClass('border-yellow-400 ring-2 ring-yellow-200');
            }

            if (selectedDate) {
                // Format tanggal untuk tampilan
                $('#bookingDateDisplay').html(`<strong>${formatTanggal(selectedDate)}</strong>`);

                // Tampilkan container time slot
                $('#timeSlotContainer').fadeIn(500);

                // Reset semua slot waktu
                $('.time-slot').removeClass('active bg-green-500 text-white booked bg-red-200 text-gray-500 disabled');
                $('.time-slot').removeAttr('title');
                $('.time-slot').removeClass('cursor-not-allowed').addClass('cursor-pointer');

                // Periksa jam saat ini jika tanggal yang dipilih adalah hari ini
                const today = new Date();
                const selectedDateObj = new Date(selectedDate);

                // Format tanggal untuk perbandingan (tanpa waktu)
                const todayStr = today.toISOString().split('T')[0];
                const selectedDateStr = selectedDate;

                // Jika tanggal yang dipilih adalah hari ini, nonaktifkan slot waktu yang sudah lewat
                if (todayStr === selectedDateStr) {
                    const currentHour = today.getHours();

                    // Nonaktifkan semua slot waktu yang sudah lewat
                    $('.time-slot').each(function() {
                        const slotTime = $(this).data('time');
                        const slotHour = parseInt(slotTime.split(':')[0]);

                        if (slotHour <= currentHour) {
                            $(this).addClass('disabled bg-gray-200 text-gray-400 cursor-not-allowed');
                            $(this).attr('title', 'Waktu sudah lewat');
                        }
                    });
                }

                // Periksa ketersediaan slot waktu
                checkAvailability();

                // Hide karyawan dan summary containers ketika tanggal berubah
                $('#karyawanContainer').hide();
                $('#summaryContainer').hide();
                $('#btnSubmit').prop('disabled', true);

                // Reset input
                $('#jamstart').val('');
                $('#jamend').val('');
                $('#idkaryawan').val('');
            } else {
                $('#bookingDateDisplay').html(`
                    <i class="bi bi-info-circle"></i> Silakan pilih tanggal terlebih dahulu
                `);

                // Sembunyikan container time slot, karyawan dan summary
                $('#timeSlotContainer').hide();
                $('#karyawanContainer').hide();
                $('#summaryContainer').hide();
                $('#btnSubmit').prop('disabled', true);

                // Reset input
                $('#jamstart').val('');
                $('#jamend').val('');
                $('#idkaryawan').val('');
            }
        });

        // Fungsi untuk memeriksa ketersediaan
        function checkAvailability() {
            const tanggal = $('#tanggal_booking').val();

            if (!tanggal) {
                showAlert('warning', 'Silakan pilih tanggal terlebih dahulu');
                return;
            }

            // Tampilkan loading
            $('#timeSlotContainer').removeClass('hidden');
            $('#bookingDateDisplay').html(`
                <div class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memeriksa ketersediaan...</span>
                </div>
            `);

            // Reset semua time slot
            $('.time-slot').removeClass('bg-green-500 text-white border-green-500 bg-red-200 text-red-800 border-red-300 cursor-not-allowed')
                .addClass('border-gray-200 hover:border-indigo-300 cursor-pointer')
                .removeAttr('disabled')
                .data('karyawan', []);

            // Ambil durasi total dari paket yang dipilih
            const durasiTotal = $('#durasi_total').val() || 60;

            // Kirim request ke server
            $.ajax({
                url: '<?= base_url('customer/booking/check-availability') ?>',
                type: 'GET',
                data: {
                    tanggal: tanggal,
                    durasi: durasiTotal
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Format tanggal untuk display
                        const formattedDate = new Date(tanggal).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });

                        $('#bookingDateDisplay').html(`
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Ketersediaan untuk <span class="font-semibold">${formattedDate}</span>
                        `);

                        // Update status setiap time slot
                        response.data.forEach(function(slot) {
                            const $slot = $(`.time-slot[data-time="${slot.time}"]`);

                            if (slot.status === 'available') {
                                $slot.data('karyawan', slot.availableKaryawan);
                                $slot.data('endTime', slot.endTime);
                            } else {
                                $slot.addClass('bg-red-200 text-red-800 border-red-300 cursor-not-allowed')
                                    .removeClass('border-gray-200 hover:border-indigo-300 cursor-pointer')
                                    .attr('disabled', true);
                            }
                        });
                    } else {
                        showAlert('error', response.message || 'Terjadi kesalahan saat memeriksa ketersediaan');
                    }
                },
                error: function() {
                    showAlert('error', 'Terjadi kesalahan saat memeriksa ketersediaan');
                }
            });
        }

        // Fungsi untuk mendapatkan karyawan yang tersedia
        function getAvailableKaryawan(jamstart) {
            const tanggal = $('#tanggal_booking').val();
            const durasiTotal = $('#durasi_total').val() || 60;

            if (!tanggal || !jamstart) {
                return;
            }

            // Tampilkan loading
            $('#karyawanContainer').html(`
                <div class="flex items-center justify-center p-4">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memuat data karyawan...</span>
                </div>
            `);

            // Kirim request ke server
            $.ajax({
                url: '<?= base_url('customer/booking/get-available-karyawan') ?>',
                type: 'GET',
                data: {
                    tanggal: tanggal,
                    jamstart: jamstart,
                    durasi: durasiTotal
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data.length > 0) {
                        let html = '';

                        response.data.forEach(function(karyawan, index) {
                            const isChecked = index === 0 ? 'checked' : '';
                            html += `
                                <div class="karyawan-option">
                                    <input type="radio" id="karyawan_${karyawan.id}" name="idkaryawan" value="${karyawan.id}" ${isChecked} class="hidden peer">
                                    <label for="karyawan_${karyawan.id}" class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-700">${karyawan.nama}</p>
                                        </div>
                                    </label>
                                </div>
                            `;
                        });

                        $('#karyawanContainer').html(html);
                        $('#karyawanSection').removeClass('hidden');

                        // Set nilai karyawan pertama sebagai default
                        if (response.data.length > 0) {
                            $('input[name="idkaryawan"]:first').prop('checked', true);
                        }
                    } else {
                        $('#karyawanContainer').html(`
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">Tidak ada karyawan yang tersedia pada waktu ini. Silakan pilih waktu lain.</p>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function() {
                    showAlert('error', 'Terjadi kesalahan saat memuat data karyawan');
                }
            });
        }

        // Delegasi event untuk slot waktu
        $('#timeSlotGrid').on('click', '.time-slot', function() {
            if ($(this).hasClass('booked') || $(this).hasClass('disabled')) {
                return;
            }

            $('.time-slot').removeClass('active bg-green-500 text-white');
            $(this).addClass('active bg-green-500 text-white');

            // Set jam mulai & selesai
            const startTime = $(this).data('time');
            const [startHour, startMinute] = startTime.split(':');
            const endHour = parseInt(startHour) + 1;
            const endTime = `${endHour.toString().padStart(2, '0')}:${startMinute}`;

            $('#jamstart').val(startTime);
            $('#jamend').val(endTime);

            // Load karyawan yang tersedia
            loadAvailableKaryawan();

            // Update summary
            updateSummary();
        });

        // Load daftar karyawan yang tersedia
        function loadAvailableKaryawan() {
            const tanggal = $('#tanggal_booking').val();
            const jamstart = $('#jamstart').val();

            if (!tanggal || !jamstart) return;

            // Reset nilai karyawan yang dipilih sebelumnya
            $('#idkaryawan').val('');

            // Tampilkan container karyawan
            $('#karyawanContainer').removeClass('hidden').fadeIn(500);

            // Tampilkan indikator loading
            $('#karyawanList').html(`
                <div class="col-span-full flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
                </div>
            `);

            $.ajax({
                url: '<?= site_url('customer/booking/getAvailableKaryawan') ?>',
                type: 'GET',
                data: {
                    tanggal: tanggal,
                    jamstart: jamstart
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let karyawanHTML = '';

                        if (response.data.length === 0) {
                            karyawanHTML = `
                                <div class="col-span-full text-center py-6 bg-yellow-50 border border-yellow-100 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-yellow-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-700">Jadwal sudah penuh</p>
                                    <p class="text-gray-500 mt-1">Silakan pilih waktu lain</p>
                                </div>
                            `;

                            // Disable tombol submit karena tidak ada karyawan tersedia
                            $('#btnSubmit').prop('disabled', true);
                        } else {
                            response.data.forEach(function(karyawan, index) {
                                karyawanHTML += `
                                    <div class="karyawan-item bg-white border border-gray-200 rounded-lg overflow-hidden cursor-pointer hover:shadow-md transition-all hover:border-green-300 ${index === 0 ? 'border-green-500 ring-2 ring-green-500' : ''}" data-id="${karyawan.id}">
                                        <div class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 bg-gray-200 rounded-full overflow-hidden mr-3 flex-shrink-0 shadow-sm">
                                                    ${karyawan.foto 
                                                        ? `<img src="<?= base_url('uploads/karyawan/') ?>${karyawan.foto}" class="w-full h-full object-cover" alt="${karyawan.nama}">`
                                                        : `<div class="w-full h-full bg-gradient-to-r from-gray-300 to-gray-200 flex items-center justify-center text-gray-500">
                                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>`
                                                    }
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-800">${karyawan.nama}</div>
                                                    <div class="text-sm text-green-600 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                        </svg>
                                                        Tersedia
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });

                            // Set nilai karyawan pertama sebagai default
                            if (response.data.length > 0) {
                                $('#idkaryawan').val(response.data[0].id);
                                console.log('Default karyawan set:', response.data[0].id);

                                // Enable tombol submit
                                $('#btnSubmit').prop('disabled', false);
                            }
                        }

                        $('#karyawanList').html(karyawanHTML);
                    } else {
                        $('#booking-alert')
                            .removeClass('hidden bg-green-100 text-green-800')
                            .addClass('bg-red-100 text-red-800')
                            .html('Terjadi kesalahan saat memuat data karyawan')
                            .fadeIn();
                    }
                },
                error: function() {
                    $('#booking-alert')
                        .removeClass('hidden bg-green-100 text-green-800')
                        .addClass('bg-red-100 text-red-800')
                        .html('Terjadi kesalahan saat memuat data karyawan')
                        .fadeIn();
                }
            });
        }

        // Delegasi event untuk pilihan karyawan
        $('#karyawanList').on('click', '.karyawan-item', function() {
            $('.karyawan-item').removeClass('border-green-500 ring-2 ring-green-500');
            $(this).addClass('border-green-500 ring-2 ring-green-500');

            // Set nilai karyawan ke input hidden
            const karyawanId = $(this).data('id');
            $('#idkaryawan').val(karyawanId);

            console.log('Karyawan dipilih:', karyawanId); // Log untuk debugging

            // Update summary
            updateSummary();

            // Tampilkan ringkasan booking
            $('#summaryContainer').fadeIn(500);

            // Enable tombol submit
            $('#btnSubmit').prop('disabled', false);
        });

        // Update ringkasan booking
        function updateSummary() {
            const paketId = $('#idpaket').val();
            let paketText = '';
            let harga = 0;

            // Cek apakah paket dipilih dari landing page (hidden input) atau dari dropdown
            if ($('#idpaket').is('input[type="hidden"]')) {
                // Jika paket dari landing page
                paketText = $('.bg-gradient-to-r.from-purple-50 h3').text();
                harga = $('#idpaket').data('harga');
            } else {
                // Jika paket dari dropdown
                paketText = $('#idpaket option:selected').text();
                harga = $('#idpaket option:selected').data('harga') || 0;
            }

            const tanggal = $('#tanggal_booking').val();
            const jamstart = $('#jamstart').val();
            const jamend = $('#jamend').val();
            const karyawanId = $('#idkaryawan').val();

            // Set nilai ringkasan
            if (paketId) $('#summary_paket').text(paketText);
            if (tanggal) $('#summary_tanggal').text(formatTanggal(tanggal));
            if (jamstart && jamend) $('#summary_waktu').text(`${jamstart} - ${jamend}`);

            // Set total harga
            $('#summary_total').text(formatRupiah(harga));
            $('#total').val(harga);

            // Set nama karyawan jika sudah dipilih
            if (karyawanId) {
                const karyawanName = $(`.karyawan-item[data-id="${karyawanId}"]`).find('.font-medium').text();
                $('#summary_karyawan').text(karyawanName);
            }

            // Update minimal pembayaran
            updateMinPayment();
        }

        // Update minimal payment for DP (50%)
        function updateMinPayment() {
            const total = parseFloat($('#total').val() || 0);
            const jenisPembayaran = $('input[name="jenis_pembayaran"]:checked').val();

            if (jenisPembayaran === 'DP') {
                const minPayment = total * 0.5; // 50% dari total
                $('#minPayment').text(formatRupiah(minPayment));
                $('#minPaymentInfo').removeClass('hidden');
                // Tambahkan nilai minimum pembayaran ke input hidden
                $('#min_payment').val(minPayment);
            } else if (jenisPembayaran === 'Lunas') {
                $('#minPayment').text(formatRupiah(total));
                $('#minPaymentInfo').removeClass('hidden');
                // Untuk pembayaran penuh, nilai min_payment adalah total
                $('#min_payment').val(total);
            } else {
                // Tidak ada jenis pembayaran yang dipilih
                $('#minPaymentInfo').addClass('hidden');
                $('#min_payment').val(0);
            }
        }

        // Ketika paket layanan berubah (hanya jika menggunakan select dropdown)
        $('select#idpaket').on('change', function() {
            // Reset form untuk meminta pelanggan memilih ulang tanggal, jam, dan karyawan
            $('#timeSlotContainer').hide();
            $('#karyawanContainer').hide();
            $('#summaryContainer').hide();
            $('#btnSubmit').prop('disabled', true);

            // Jika tanggal sudah dipilih, tampilkan time slot container
            if ($('#tanggal_booking').val()) {
                $('#timeSlotContainer').fadeIn(500);
            }

            // Reset input
            $('#jamstart').val('');
            $('#jamend').val('');
            $('#idkaryawan').val('');

            // Update summary
            updateSummary();
        });

        // Fungsi pembayaran dipindahkan ke halaman payment.php

        // Form submit
        $('#bookingForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submit triggered');

            // Reset semua validasi visual
            $('.form-control-error').removeClass('form-control-error');

            // Validasi khusus untuk tanggal booking
            if (!$('#tanggal_booking').val()) {
                console.log('Tanggal booking tidak diisi');

                // Tampilkan alert khusus untuk tanggal yang belum dipilih
                let dateAlertHTML = `
                    <div class="flex bg-yellow-100 rounded-lg p-4 mb-4 text-sm text-yellow-700 border border-yellow-200">
                        <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <span class="font-medium">Perhatian!</span> Silakan pilih tanggal booking terlebih dahulu.
                        </div>
                    </div>
                `;

                $('#booking-alert')
                    .removeClass('hidden')
                    .removeClass('bg-red-100 text-red-800 border-red-200 bg-green-100 text-green-800 border-green-200')
                    .addClass('bg-yellow-100 text-yellow-800 border-yellow-200')
                    .html(dateAlertHTML)
                    .show();

                // Scroll ke input tanggal dan berikan efek highlight
                $('html, body').animate({
                    scrollTop: $('#tanggal_booking').offset().top - 120
                }, 500);

                $('#tanggal_booking').addClass('border-yellow-400 ring-2 ring-yellow-200');

                // Hapus highlight setelah beberapa detik
                setTimeout(function() {
                    $('#tanggal_booking').removeClass('border-yellow-400 ring-2 ring-yellow-200');
                }, 3000);

                return false;
            }

            // Validasi paket dipilih
            let paketSelected = false;

            // Cek jika ada paket yang dipilih
            if ($('#selectedPakets').length) {
                // Jika menggunakan UI lama
                paketSelected = true;
            } else if ($('.selected-paket-item').length > 0) {
                // Jika menggunakan UI baru
                paketSelected = true;
            }

            if (!paketSelected) {
                isValid = false;
                missingFields.push('paket layanan');
            }

            // Validasi tanggal booking
            if (!$('#tanggal_booking').val()) {
                isValid = false;
                missingFields.push('tanggal booking');
            }

            // Validasi jam mulai
            if (!$('#jamstart').val()) {
                isValid = false;
                missingFields.push('jam booking');
            }

            // Validasi karyawan
            const karyawanId = $('#idkaryawan').val();
            console.log('Validasi karyawan:', karyawanId); // Log untuk debugging

            if (!karyawanId) {
                isValid = false;
                missingFields.push('karyawan');
            }

            if (!isValid) {
                // Scroll ke bagian atas form
                $('html, body').animate({
                    scrollTop: $('#bookingForm').offset().top - 100
                }, 500);

                let messageHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Harap isi semua field yang diperlukan</h3>
                            <p class="mt-1 text-xs text-red-700">Field yang harus diisi: ${missingFields.join(', ')}</p>
                        </div>
                    </div>
                `;

                $('#booking-alert')
                    .removeClass('hidden bg-green-100 text-green-800 border-green-200')
                    .addClass('bg-red-100 text-red-800 border-red-200')
                    .html(messageHTML)
                    .fadeIn();
                return;
            }

            // Kode existing setelahnya tetap sama...

            // Disable tombol submit untuk mencegah double submit
            $('#btnSubmit').prop('disabled', true).html(`
                <svg class="animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `);

            // Scroll ke bagian atas form
            $('html, body').animate({
                scrollTop: $('#bookingForm').offset().top - 100
            }, 500);

            // Buat form data untuk upload file
            var formData = new FormData(this);

            $.ajax({
                url: '<?= site_url('customer/booking/store') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        // Hapus alert lama jika ada
                        $('#booking-alert').hide();

                        // Tampilkan modal sukses yang sudah ada di HTML
                        showSuccessModal(response.kdbooking);
                    } else {
                        let errorHTML = `
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Gagal membuat booking</h3>
                                    <p class="mt-2 text-sm text-red-700">${response.message}</p>
                                </div>
                            </div>
                        `;

                        $('#booking-alert')
                            .removeClass('hidden bg-green-100 text-green-800 border-green-200')
                            .addClass('bg-red-100 text-red-800 border-red-200')
                            .html(errorHTML)
                            .fadeIn();

                        // Re-enable tombol submit
                        $('#btnSubmit').prop('disabled', false).html(`
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Booking Sekarang
                        `);
                    }
                },
                error: function() {
                    let errorHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                                <p class="mt-2 text-sm text-red-700">Sistem tidak dapat memproses booking Anda. Silakan coba lagi.</p>
                            </div>
                        </div>
                    `;

                    $('#booking-alert')
                        .removeClass('hidden bg-green-100 text-green-800 border-green-200')
                        .addClass('bg-red-100 text-red-800 border-red-200')
                        .html(errorHTML)
                        .fadeIn();

                    // Re-enable tombol submit
                    $('#btnSubmit').prop('disabled', false).html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Booking Sekarang
                    `);
                }
            });
        });

        // Preview bukti pembayaran
        $('#bukti_pembayaran').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                $('#buktiPreviewContainer').addClass('hidden');
                return;
            }

            // Validasi tipe file
            const acceptedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!acceptedImageTypes.includes(file.type)) {
                showAlert('File harus berupa gambar (JPG, PNG, atau GIF)', 'error');
                $(this).val('');
                return;
            }

            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showAlert('Ukuran file terlalu besar (maksimal 2MB)', 'error');
                $(this).val('');
                return;
            }

            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#buktiPreview').attr('src', e.target.result);
                $('#buktiPreviewContainer').removeClass('hidden').addClass('animate__animated animate__fadeIn');
            }
            reader.readAsDataURL(file);
        });

        // Hapus bukti pembayaran
        $('#removeBukti').on('click', function() {
            $('#bukti_pembayaran').val('');
            $('#buktiPreviewContainer').addClass('hidden');
        });

        // Fungsi untuk menampilkan alert
        function showAlert(message, type = 'error') {
            const alertClass = type === 'error' ?
                'bg-red-100 text-red-800 border-red-200' :
                'bg-green-100 text-green-800 border-green-200';

            const iconSvg = type === 'error' ?
                `<svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>` :
                `<svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>`;

            let messageHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        ${iconSvg}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                </div>
            `;

            $('#booking-alert')
                .removeClass('hidden bg-green-100 text-green-800 border-green-200 bg-red-100 text-red-800 border-red-200')
                .addClass(alertClass)
                .html(messageHTML)
                .fadeIn();

            // Auto hide setelah 5 detik
            setTimeout(function() {
                $('#booking-alert').fadeOut();
            }, 5000);
        }

        // Fungsi untuk menampilkan modal sukses booking
        function showSuccessModal(bookingCode) {
            const modal = document.getElementById('booking-success-modal');

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('modal-active');

            // Tambahkan efek scale in ke modal content
            setTimeout(() => {
                const modalContent = modal.querySelector('.relative');
                modalContent.classList.add('scale-in');
            }, 100);

            // Tambahkan efek pulse ke icon sukses
            setTimeout(() => {
                const successIcon = modal.querySelector('.w-24');
                successIcon.classList.add('pulse-animation');
            }, 500);

            // Animasi countdown
            const countdownBar = document.getElementById('countdown-bar');
            countdownBar.classList.add('animate-countdown-bar');

            // Hitung mundur timer
            let countdownTime = 3;
            const countdownTimer = document.getElementById('countdown-timer');
            countdownTimer.textContent = countdownTime;

            const countdownInterval = setInterval(function() {
                countdownTime--;
                countdownTimer.textContent = countdownTime;
                if (countdownTime <= 0) {
                    clearInterval(countdownInterval);
                }
            }, 1000);

            // Redirect ke halaman pembayaran
            setTimeout(function() {
                window.location.href = '<?= site_url('customer/booking/payment/') ?>' + bookingCode;
            }, 3000);
        }

        // Fungsi untuk menghitung total harga dan durasi
        function hitungTotal() {
            totalHarga = 0;
            totalDurasi = 0;
            selectedPakets = [];

            // Jika ada paket yang dipilih dari URL parameter dan belum klik "Tambah Paket"
            if ($('#selectedPakets').length) {
                const paketId = $('#selectedPakets').val();
                const harga = parseFloat($('#selectedPakets').data('harga'));
                const durasi = parseInt($('#selectedPakets').data('durasi')) || 60;

                if (paketId && !isNaN(harga)) {
                    totalHarga = harga;
                    totalDurasi = durasi;
                    selectedPakets.push({
                        id: paketId,
                        harga: harga,
                        durasi: durasi
                    });
                }
            }

            // Ambil semua paket yang dipilih di UI cards
            $('.selected-paket-item').each(function() {
                const $item = $(this);
                const paketId = $item.data('id');
                const harga = parseFloat($item.data('harga'));
                const durasi = parseInt($item.data('durasi'));

                if (paketId && !isNaN(harga)) {
                    totalHarga += harga;
                    totalDurasi += durasi;
                    selectedPakets.push({
                        id: paketId,
                        harga: harga,
                        durasi: durasi
                    });
                }
            });

            // Update tampilan total harga dan durasi
            $('#totalHarga').text('Rp. ' + formatNumber(totalHarga));
            $('#totalDurasi').text(totalDurasi + ' menit');

            // Update hidden input untuk total
            $('#total').val(totalHarga);
            $('#durasi_total').val(totalDurasi);

            // Debug total value
            console.log('Total harga updated:', totalHarga, 'Type:', typeof totalHarga, 'Total durasi:', totalDurasi);

            // Update informasi durasi
            updateDurasiInfo();

            // Update selected paket IDs - pastikan format array untuk server
            if (selectedPakets.length > 0) {
                // Gunakan array untuk mengirim ke server, bukan string dengan koma
                const selectedIds = selectedPakets.map(p => p.id);
                $('#selectedPaketIds').val(JSON.stringify(selectedIds));
                console.log('Selected paket IDs:', selectedIds);

                // Enable tombol submit jika paket sudah dipilih dan tanggal sudah dipilih
                if ($('#tanggal_booking').val()) {
                    $('#btnSubmit').prop('disabled', false);
                }
            } else {
                $('#selectedPaketIds').val('');
                $('#btnSubmit').prop('disabled', true);
            }

            // Perbarui jam selesai berdasarkan durasi total
            updateJamEnd();
        }

        // Fungsi untuk memformat angka sebagai mata uang
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }

        // Fungsi untuk menambahkan paket baru
        function tambahPaket() {
            // Jika ini adalah paket pertama yang ditambahkan dan ada paket yang dipilih dari landing page
            if ($('#selectedPakets').length) {
                // Ubah tampilan menjadi container paket biasa
                const selectedPaketId = $('#selectedPakets').val();
                const selectedPaketHarga = $('#selectedPakets').data('harga');
                const selectedPaketDurasi = $('#selectedPakets').data('durasi') || 60;
                const selectedPaketName = $('.bg-gray-50.p-4 h3.font-semibold.text-lg').text().trim();

                // Hapus tampilan paket yang dipilih dari landing page
                const paketContainer = $('.bg-gray-50.p-4.rounded-lg.border.border-gray-200.flex.items-start');
                if (paketContainer.length) {
                    paketContainer.closest('div').remove();
                } else {
                    // Hanya hapus div yang berisi paket yang dipilih
                    $('div:has(> #selectedPakets)').remove();
                }

                // Buat container paket baru dengan UI card
                const paketContainerHtml = `
                    <div>
                        <div id="paketContainer">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <?php foreach ($paketList as $paket): ?>
                                    <div class="paket-card border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer" 
                                        data-id="<?= $paket['idpaket'] ?>" 
                                        data-harga="<?= $paket['harga'] ?>" 
                                        data-durasi="<?= $paket['durasi'] ?? 60 ?>"
                                        data-image="<?= !empty($paket['gambar']) ? $paket['gambar'] : (!empty($paket['image']) ? $paket['image'] : '') ?>">
                                        <div class="relative h-40 bg-gray-100">
                                            <?php if (!empty($paket['gambar'])): ?>
                                                <img src="<?= base_url('uploads/paket/' . $paket['gambar']) ?>" alt="<?= $paket['namapaket'] ?>" 
                                                    class="w-full h-full object-cover">
                                            <?php elseif (!empty($paket['image'])): ?>
                                                <img src="<?= base_url('uploads/paket/' . $paket['image']) ?>" alt="<?= $paket['namapaket'] ?>" 
                                                    class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                            <div class="absolute bottom-0 right-0 bg-gradient-to-l from-indigo-600 to-indigo-500 text-white px-3 py-1 text-sm font-medium">
                                                <?= $paket['durasi'] ?? 60 ?> menit
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-800 mb-1"><?= $paket['namapaket'] ?></h3>
                                            <p class="text-sm text-gray-600 line-clamp-2 mb-2"><?= $paket['deskripsi'] ?? 'Tidak ada deskripsi' ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-indigo-600 font-bold">Rp. <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                                                <button type="button" class="add-paket-btn bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-full p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="selectedPaketsContainer" class="mt-4 space-y-3"></div>
                        <input type="hidden" id="selectedPaketIds" name="selectedPakets" value="">
                    </div>
                `;

                // Tambahkan container paket baru ke dalam DOM, segera setelah label "Informasi Layanan"
                const layananHeading = $('.text-xl.font-semibold:contains("Informasi Layanan")');
                if (layananHeading.length) {
                    layananHeading.closest('.flex').after(paketContainerHtml);
                } else {
                    // Fallback jika heading tidak ditemukan
                    const dataBookingSection = $('#durasiInfo').closest('.bg-white');
                    dataBookingSection.find('.space-y-5').prepend(paketContainerHtml);
                }

                // Hapus input hidden selectedPakets
                $('#selectedPakets').remove();

                // Tambahkan paket yang sudah dipilih sebelumnya ke dalam selected pakets
                tambahPaketKeSelected(selectedPaketId, selectedPaketName, selectedPaketHarga, selectedPaketDurasi);

                return;
            }
        }

        // Fungsi untuk menambahkan paket ke daftar yang dipilih
        function tambahPaketKeSelected(id, nama, harga, durasi) {
            // Periksa apakah paket sudah dipilih
            if ($(`#selected-paket-${id}`).length) {
                return;
            }

            // Bersihkan nama paket dari karakter tidak perlu
            const cleanedNama = nama.replace(/\d+\s*menit\s*Opsi Pembayaran/g, '').trim();

            const formattedHarga = formatNumber(harga);

            // Cari gambar paket dari card
            let imageSrc = '';
            const paketCard = $(`.paket-card[data-id="${id}"]`);

            if (paketCard.length) {
                const cardImage = paketCard.find('img');
                if (cardImage.length) {
                    imageSrc = cardImage.attr('src');
                }
            }

            const paketHTML = `
                <div id="selected-paket-${id}" class="selected-paket-item bg-white border border-gray-200 rounded-lg p-3 flex items-center justify-between" 
                    data-id="${id}" data-harga="${harga}" data-durasi="${durasi}">
                    <div class="flex items-center">
                        ${imageSrc ? 
                        `<div class="w-12 h-12 rounded-md overflow-hidden mr-3 flex-shrink-0">
                            <img src="${imageSrc}" alt="${cleanedNama}" class="w-full h-full object-cover">
                         </div>` : 
                        `<div class="w-12 h-12 bg-gray-100 rounded-md mr-3 flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                         </div>`
                        }
                    <div>
                            <h4 class="font-medium text-gray-800">${cleanedNama}</h4>
                        <div class="flex items-center text-sm">
                            <span class="text-indigo-600 font-semibold mr-3">Rp. ${formattedHarga}</span>
                            <span class="text-gray-500">${durasi} menit</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="remove-selected-paket text-red-500 hover:text-red-700 p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;

            $('#selectedPaketsContainer').append(paketHTML);
            $('#selectedPaketsContainer').removeClass('hidden');

            // Log untuk debugging
            console.log('Paket ditambahkan:', {
                id,
                nama: cleanedNama,
                harga,
                durasi,
                imageSrc
            });

            hitungTotal();
        }

        // Event handlers untuk UI paket baru
        $(document).ready(function() {
            // Event handler untuk klik pada paket card
            $(document).on('click', '.paket-card', function(e) {
                // Jangan trigger jika yang diklik adalah tombol add
                if ($(e.target).closest('.add-paket-btn').length) {
                    return;
                }

                const $card = $(this);
                const id = $card.data('id');
                const nama = $card.find('h3').text();
                const harga = $card.data('harga');
                const durasi = $card.data('durasi');

                tambahPaketKeSelected(id, nama, harga, durasi);
            });

            // Event handler untuk tombol add pada paket card
            $(document).on('click', '.add-paket-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const $card = $(this).closest('.paket-card');
                const id = $card.data('id');
                const nama = $card.find('h3').text();
                const harga = $card.data('harga');
                const durasi = $card.data('durasi');

                tambahPaketKeSelected(id, nama, harga, durasi);
            });

            // Event handler untuk menghapus paket yang dipilih
            $(document).on('click', '.remove-selected-paket', function() {
                $(this).closest('.selected-paket-item').remove();
                hitungTotal();
            });
        });

        // Fungsi untuk memperbarui jam selesai berdasarkan jam mulai dan durasi
        function updateJamEnd() {
            const jamStart = $('#jamstart').val();
            if (jamStart && totalDurasi > 0) {
                // Konversi jam mulai ke menit
                const [hours, minutes] = jamStart.split(':').map(Number);
                const startMinutes = hours * 60 + minutes;

                // Tambahkan durasi total
                const endMinutes = startMinutes + totalDurasi;

                // Konversi kembali ke format jam
                const endHours = Math.floor(endMinutes / 60) % 24;
                const endMins = endMinutes % 60;

                const formattedEndHours = endHours.toString().padStart(2, '0');
                const formattedEndMins = endMins.toString().padStart(2, '0');

                const jamEnd = `${formattedEndHours}:${formattedEndMins}`;
                $('#jamend').val(jamEnd);
            }
        }

        // Event handler untuk perubahan paket
        $(document).on('change', '.paket-select', function() {
            const $select = $(this);
            const $paketInfo = $select.closest('.paket-selection').find('.paket-info');
            const $durasi = $paketInfo.find('.paket-durasi');
            const $harga = $paketInfo.find('.paket-harga');

            if ($select.val()) {
                const $option = $select.find('option:selected');
                const harga = parseFloat($option.data('harga'));
                const durasi = parseInt($option.data('durasi'));

                $durasi.text(durasi);
                $harga.text('Rp. ' + formatNumber(harga));
                $paketInfo.removeClass('hidden');

                hitungTotal();
            } else {
                $paketInfo.addClass('hidden');
            }
        });

        // Event handler untuk menghapus paket
        $(document).on('click', '.remove-paket', function() {
            $(this).closest('.paket-selection').remove();
            hitungTotal();
        });

        // Perbarui jam end saat jam start berubah
        $('#jamstart').on('change', function() {
            updateJamEnd();
        });

        // Inisialisasi perhitungan total saat halaman dimuat
        hitungTotal();

        // Fungsi untuk memperbarui informasi durasi
        function updateDurasiInfo(isInitial) {
            debug('Memperbarui informasi durasi', {
                totalDurasi,
                isInitial
            });

            if (totalDurasi > 0) {
                // Hitung jam selesai berdasarkan jam mulai yang dipilih dan durasi total
                const jamMulai = $('#jamstart').val();

                // Tampilkan informasi durasi yang lebih jelas
                let durasiInfo = '';
                if (totalDurasi >= 60) {
                    const jam = Math.floor(totalDurasi / 60);
                    const menit = totalDurasi % 60;
                    durasiInfo = `${jam} jam`;
                    if (menit > 0) {
                        durasiInfo += ` ${menit} menit`;
                    }
                } else {
                    durasiInfo = `${totalDurasi} menit`;
                }

                // Update jam selesai jika ada jam mulai
                if (jamMulai) {
                    const [jam, menit] = jamMulai.split(':');
                    let jamMulaiMenit = (parseInt(jam) * 60) + parseInt(menit);
                    let jamSelesaiMenit = jamMulaiMenit + totalDurasi;

                    // Format jam selesai
                    let jamSelesai = Math.floor(jamSelesaiMenit / 60);
                    let menitSelesai = jamSelesaiMenit % 60;
                    let jamSelesaiStr = `${jamSelesai.toString().padStart(2, '0')}:${menitSelesai.toString().padStart(2, '0')}`;

                    // Update tampilan jam selesai
                    $('#jamend').val(jamSelesaiStr);
                }

                // Kosongkan container durasi info untuk menghilangkan teks
                $('#durasiInfo').html('').addClass('hidden');
            } else {
                $('#durasiInfo').addClass('hidden');
            }
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('custom_style') ?>
<style>
    /* Style untuk animasi pada preview gambar */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate__animated {
        animation-duration: 0.5s;
    }

    .animate__fadeIn {
        animation-name: fadeIn;
    }

    /* Styling untuk input tanggal saat validasi */
    @keyframes pulseBorder {
        0% {
            box-shadow: 0 0 0 0 rgba(250, 204, 21, 0.6);
        }

        70% {
            box-shadow: 0 0 0 6px rgba(250, 204, 21, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(250, 204, 21, 0);
        }
    }

    .border-yellow-400.ring-2 {
        animation: pulseBorder 1.5s infinite;
        transition: all 0.3s ease;
    }

    /* Style untuk tombol radio */
    .payment-radio:checked+label .radio-circle .radio-dot,
    .method-radio:checked+label .radio-circle .radio-dot {
        display: block;
    }

    .payment-radio:checked+label,
    .method-radio:checked+label {
        border-color: #4f46e5;
        background-color: #f9fafb;
    }

    /* Style untuk tombol dan elemen lainnya */
    .btn-primary {
        background-color: #E74C3C;
        color: white;
    }

    .btn-primary:hover {
        background-color: #d44637;
    }

    .btn-secondary {
        background-color: #f8f9fa;
        color: #343a40;
        border: 1px solid #dee2e6;
    }

    .btn-secondary:hover {
        background-color: #e9ecef;
    }

    .form-section {
        transition: all 0.3s ease-in-out;
    }

    .gradient-text {
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        background-image: linear-gradient(45deg, #E74C3C, #F1C40F);
    }

    .animated-border {
        position: relative;
        background: white;
        z-index: 0;
    }

    .animated-border::before {
        content: '';
        position: absolute;
        z-index: -2;
        left: -8px;
        top: -8px;
        width: calc(100% + 16px);
        height: calc(100% + 16px);
        background: linear-gradient(45deg, #E74C3C, #F1C40F, #E74C3C, #F1C40F);
        background-size: 300% 300%;
        border-radius: 16px;
        animation: border-animate 8s ease-in-out infinite;
    }

    .animated-border::after {
        content: '';
        position: absolute;
        z-index: -1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: white;
        border-radius: 12px;
    }

    @keyframes border-animate {

        0%,
        100% {
            background-position: 0% 0%;
        }

        50% {
            background-position: 100% 100%;
        }
    }

    .time-slot.selected {
        background-color: #4ade80;
        border-color: #22c55e;
        color: white;
        font-weight: 600;
    }

    .time-slot.booked {
        background-color: #fecaca;
        border-color: #ef4444;
        color: #991b1b;
        opacity: 0.7;
        cursor: not-allowed;
    }

    .time-slot.past {
        background-color: #e5e7eb;
        border-color: #d1d5db;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .karyawan-item {
        transition: all 0.2s ease-in-out;
    }

    .karyawan-item.selected {
        border-color: #4f46e5;
        background-color: #eef2ff;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
    }

    /* Styling untuk bukti pembayaran preview */
    #buktiPreviewContainer {
        transition: all 0.3s ease;
    }

    #buktiPreview {
        object-fit: contain;
        border: 1px solid #e5e7eb;
    }

    #removeBukti {
        transition: all 0.2s ease;
        opacity: 0.8;
    }

    #removeBukti:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Modal Sukses Booking */
    #booking-success-modal {
        transition: all 0.3s ease;
    }

    #booking-success-modal.modal-active {
        display: flex;
    }

    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        transition: opacity 0.3s ease;
    }

    @keyframes countdown {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }

    .animate-countdown-bar {
        animation: countdown 3s linear forwards;
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .scale-in {
        animation: scaleIn 0.3s ease forwards;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    /* Styling untuk paket card */
    .paket-card {
        transition: all 0.3s ease;
    }

    .paket-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .paket-card.selected {
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px #4f46e5;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Styling untuk selected paket item */
    .selected-paket-item {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .remove-selected-paket {
        opacity: 0.7;
        transition: all 0.2s ease;
    }

    .remove-selected-paket:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Animasi untuk tombol add paket */
    .add-paket-btn {
        transition: all 0.2s ease;
    }

    .add-paket-btn:hover {
        transform: scale(1.1);
        background-color: #c7d2fe;
    }

    /* Styling untuk durasi badge */
    .paket-card .absolute {
        transition: all 0.3s ease;
    }

    .paket-card:hover .absolute {
        padding-right: 15px;
    }
</style>
<?= $this->endSection() ?>