<?= $this->extend('templates/main') ?>

<?= $this->section('custom_style') ?>
<style>
    /* Animasi fadeIn untuk preview gambar */
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

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in-out forwards;
    }

    /* Styling untuk countdown */
    .countdown-container {
        filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.07));
    }

    /* Styling untuk preview container */
    #image-preview-container {
        transition: all 0.3s ease;
    }

    #image-preview {
        transition: all 0.3s ease;
    }

    #remove-image {
        transition: all 0.2s ease;
        opacity: 0.8;
    }

    #remove-image:hover {
        transform: scale(1.1);
        opacity: 1;
    }

    /* Pulse animation untuk countdown */
    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }

    #seconds {
        animation: pulse 1s infinite;
    }

    /* Animasi pulse untuk countdown hampir habis */
    .animate-pulse {
        animation: pulse-critical 1s infinite;
    }

    @keyframes pulse-critical {

        0%,
        100% {
            opacity: 1;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }

        50% {
            opacity: 0.9;
            box-shadow: 0 0 0 5px rgba(239, 68, 68, 0);
        }
    }

    /* Progress bar animation */
    #progress-bar {
        transition: width 1s linear, background-color 1s ease;
    }

    /* Animasi untuk alert messages */
    .fixed.top-4.right-4 {
        animation: slideIn 0.3s ease-out forwards;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animasi untuk spinner */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Animasi untuk modal expired */
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        transition: opacity 0.3s ease;
    }

    .modal-content {
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .modal-active .modal-content {
        transform: scale(1);
        opacity: 1;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        10%,
        30%,
        50%,
        70%,
        90% {
            transform: translateX(-5px);
        }

        20%,
        40%,
        60%,
        80% {
            transform: translateX(5px);
        }
    }

    .shake {
        animation: shake 0.8s cubic-bezier(.36, .07, .19, .97) both;
    }

    /* Animasi untuk modal sukses */
    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-20px);
        }

        60% {
            transform: translateY(-10px);
        }
    }

    .bounce {
        animation: bounce 1s ease;
    }

    /* Animasi konfeti untuk modal sukses */
    @keyframes confetti-slow {
        0% {
            transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
        }

        100% {
            transform: translate3d(25px, 105px, 0) rotateX(360deg) rotateY(180deg);
        }
    }

    @keyframes confetti-medium {
        0% {
            transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
        }

        100% {
            transform: translate3d(100px, 140px, 0) rotateX(100deg) rotateY(360deg);
        }
    }

    @keyframes confetti-fast {
        0% {
            transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
        }

        100% {
            transform: translate3d(-50px, 150px, 0) rotateX(10deg) rotateY(250deg);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-16">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-3xl md:text-4xl font-bold gradient-text">Pembayaran Booking</h1>
            <p class="text-gray-300 mt-3">Silakan lakukan pembayaran untuk menyelesaikan proses booking Anda</p>
            <div class="w-20 h-1 bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="form-card rounded-xl shadow-xl p-6 md:p-8 animated-border" data-aos="zoom-in" data-aos-delay="200">
            <!-- Detail Booking Card -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm form-section mb-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center mb-5">
                    <div class="rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 p-2 mr-3 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Detail Booking</h2>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center border-b border-gray-200 pb-3 hover:bg-gray-50 px-2 rounded transition-all">
                        <div class="w-full md:w-1/3 font-medium text-gray-600">Kode Booking</div>
                        <div class="w-full md:w-2/3 text-gray-800 font-semibold"><?= $booking['kdbooking'] ?></div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center border-b border-gray-200 pb-3 hover:bg-gray-50 px-2 rounded transition-all">
                        <div class="w-full md:w-1/3 font-medium text-gray-600">Tanggal Booking</div>
                        <div class="w-full md:w-2/3 text-gray-800"><?= date('d F Y', strtotime($booking['tanggal_booking'])) ?></div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center border-b border-gray-200 pb-3 hover:bg-gray-50 px-2 rounded transition-all">
                        <div class="w-full md:w-1/3 font-medium text-gray-600">Jam</div>
                        <div class="w-full md:w-2/3 text-gray-800"><?= isset($details[0]) ? $details[0]['jamstart'] . ' - ' . $details[0]['jamend'] : '-' ?></div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center border-b border-gray-200 pb-3 hover:bg-gray-50 px-2 rounded transition-all">
                        <div class="w-full md:w-1/3 font-medium text-gray-600">Paket</div>
                        <div class="w-full md:w-2/3 text-gray-800"><?= isset($details[0]) ? $details[0]['nama_paket'] : '-' ?></div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center hover:bg-gray-50 px-2 py-2 rounded transition-all">
                        <div class="w-full md:w-1/3 font-medium text-gray-600">Total</div>
                        <div class="w-full md:w-2/3 text-gray-800 font-bold text-lg">Rp <?= number_format($booking['total'], 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran Card -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm form-section hover:shadow-md transition-all duration-300">
                <div class="flex items-center mb-5">
                    <div class="rounded-full bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] p-2 mr-3 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Form Pembayaran</h2>
                </div>

                <form id="paymentForm" action="<?= site_url('customer/booking/savePayment') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="kdbooking" value="<?= $booking['kdbooking'] ?>">

                    <!-- Jenis Pembayaran -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-sm transition-all">
                        <label for="jenispembayaran" class="block mb-2 text-gray-700 font-medium">Jenis Pembayaran</label>
                        <div class="relative">
                            <select id="jenispembayaran" name="jenis_pembayaran" class="bg-white border border-gray-300 rounded-lg w-full p-3 appearance-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all" required>
                                <option value="DP">DP (50%)</option>
                                <option value="Lunas">Lunas</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">DP minimal 50% dari total pembayaran</p>
                    </div>

                    <!-- Jumlah DP -->
                    <div id="dpAmountContainer" class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-sm transition-all">
                        <label for="min_payment" class="block mb-2 text-gray-700 font-medium">Jumlah DP (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="text" id="min_payment" name="min_payment" value="<?= number_format($booking['total'] * 0.5, 0, '', '') ?>" class="bg-gray-100 border border-gray-300 rounded-lg w-full py-3 pl-10 pr-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all" readonly>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Minimal Rp <?= number_format($booking['total'] * 0.5, 0, ',', '.') ?></p>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-sm transition-all">
                        <label for="metode_pembayaran" class="block mb-2 text-gray-700 font-medium">Metode Pembayaran</label>
                        <div class="relative">
                            <select id="metode_pembayaran" name="metode_pembayaran" class="bg-white border border-gray-300 rounded-lg w-full p-3 appearance-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all" required>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Rekening -->
                    <div id="transferInfo" class="bg-gradient-to-r from-gray-50 to-gray-100 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="text-md font-medium text-gray-800">Informasi Rekening</h3>
                        </div>
                        <div class="pl-7">
                            <p class="text-gray-600 mb-1 flex items-center">
                                <span class="font-medium mr-2">Bank BCA:</span> 1234567890
                            </p>
                            <p class="text-gray-600 mb-1 flex items-center">
                                <span class="font-medium mr-2">A/N:</span> Awan Barbershop
                            </p>
                            <div class="mt-3 bg-blue-50 p-2 rounded-md border border-blue-100">
                                <p class="text-blue-700 text-sm">Harap transfer sesuai nominal yang tertera.</p>
                            </div>
                        </div>
                    </div>

                    <!-- QRIS Info -->
                    <div id="qrisInfo" class="hidden bg-gradient-to-r from-gray-50 to-gray-100 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all text-center">
                        <div class="flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <h3 class="text-md font-medium text-gray-800">Scan QRIS</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Silakan scan kode QRIS di bawah ini untuk melakukan pembayaran:</p>
                        <div class="bg-white p-3 rounded-lg shadow-sm inline-block mb-3">
                            <!-- Placeholder untuk QRIS -->
                            <div class="w-[300px] h-[300px] bg-gray-100 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <img src="<?= base_url('assets/images/payment/qris-placeholder.jpg') ?>" alt="QRIS Code" class="h-80 w-80 mx-auto">
                                    <p class="mt-2 text-sm text-gray-500">QRIS Awan Barbershop</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 bg-blue-50 p-2 rounded-md border border-blue-100">
                            <p class="text-blue-700 text-sm">Harap transfer sesuai nominal yang tertera.</p>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-5 rounded-lg border border-amber-200 shadow-sm hover:shadow-md transition-all mb-5">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-md font-medium text-gray-800">Batas Waktu Pembayaran</h3>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="countdown-container flex justify-center items-center space-x-3 my-2">
                                <div class="flex flex-col items-center">
                                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-2xl font-bold py-2 px-3 rounded-lg shadow-lg min-w-[60px] text-center" id="hours">00</div>
                                    <span class="text-xs text-gray-500 mt-1">JAM</span>
                                </div>
                                <div class="text-xl font-bold text-amber-500">:</div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-2xl font-bold py-2 px-3 rounded-lg shadow-lg min-w-[60px] text-center" id="minutes">00</div>
                                    <span class="text-xs text-gray-500 mt-1">MENIT</span>
                                </div>
                                <div class="text-xl font-bold text-amber-500">:</div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-2xl font-bold py-2 px-3 rounded-lg shadow-lg min-w-[60px] text-center" id="seconds">00</div>
                                    <span class="text-xs text-gray-500 mt-1">DETIK</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3 mb-2">
                                <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-2.5 rounded-full" id="progress-bar" style="width: 100%"></div>
                            </div>
                            <p class="text-sm text-gray-600 text-center mt-2">Segera lakukan pembayaran sebelum batas waktu berakhir atau booking akan otomatis dibatalkan.</p>
                        </div>
                    </div>

                    <!-- Upload Bukti -->
                    <div id="buktiContainer" class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-sm transition-all">
                        <label for="bukti_pembayaran" class="block mb-2 text-gray-700 font-medium">Upload Bukti Pembayaran</label>

                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-2/3">
                                <div class="relative">
                                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" class="hidden" required>
                                    <label for="bukti_pembayaran" class="flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        <span id="file-name" class="text-gray-500">Pilih file bukti pembayaran</span>
                                    </label>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Format: JPG, PNG, JPEG. Maks 2MB</p>
                            </div>
                            <div class="w-full md:w-1/3">
                                <div id="image-preview-container" class="hidden relative">
                                    <div id="image-preview" class="h-32 bg-gray-100 rounded-lg border border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                        <img id="preview-img" src="#" alt="Preview" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <button type="button" id="remove-image" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6">
                        <button type="submit" id="submitBtn" class="bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] hover:from-[#F1C40F] hover:to-[#E74C3C] text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E74C3C] shadow-md">
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Konfirmasi Pembayaran
                            </div>
                        </button>
                        <a href="<?= site_url('customer/booking') ?>" class="text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition-all duration-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            Kembali ke Daftar Booking
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Alert Container -->
<div id="alertContainer" class="fixed top-4 right-4 z-50"></div>

<!-- Modal Sukses Pembayaran -->
<div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="successModal">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 z-10" id="successModalContent">
        <!-- Konfeti -->
        <div class="confetti-container absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="confetti bg-red-500 w-2 h-2 rounded-sm absolute top-0 left-10" style="animation: confetti-slow 3s linear infinite;"></div>
            <div class="confetti bg-yellow-500 w-2 h-2 rounded-sm absolute top-0 left-20" style="animation: confetti-medium 2s linear infinite;"></div>
            <div class="confetti bg-blue-500 w-2 h-2 rounded-sm absolute top-0 left-30" style="animation: confetti-fast 1.5s linear infinite;"></div>
            <div class="confetti bg-green-500 w-2 h-2 rounded-sm absolute top-0 left-40" style="animation: confetti-slow 2.5s linear infinite;"></div>
            <div class="confetti bg-purple-500 w-2 h-2 rounded-sm absolute top-0 left-50" style="animation: confetti-medium 1.8s linear infinite;"></div>
            <div class="confetti bg-pink-500 w-2 h-2 rounded-sm absolute top-0 left-60" style="animation: confetti-fast 2.2s linear infinite;"></div>
            <div class="confetti bg-indigo-500 w-2 h-2 rounded-sm absolute top-0 left-70" style="animation: confetti-slow 2.8s linear infinite;"></div>
            <div class="confetti bg-yellow-300 w-2 h-2 rounded-sm absolute top-0 left-80" style="animation: confetti-medium 2.4s linear infinite;"></div>
            <div class="confetti bg-red-300 w-2 h-2 rounded-sm absolute top-0 left-90" style="animation: confetti-fast 1.9s linear infinite;"></div>
        </div>

        <div class="bg-green-600 px-4 py-3">
            <h5 class="text-white font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pembayaran Berhasil
            </h5>
        </div>
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-green-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h4 class="text-xl font-bold text-gray-800 mb-2">Pembayaran Berhasil Dikonfirmasi!</h4>
            <p class="text-gray-600 mb-4">Terima kasih! Pembayaran Anda telah berhasil diproses. Bukti pembayaran akan segera diverifikasi oleh admin.</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="bg-green-600 h-2 rounded-full animate-pulse" style="width: 100%"></div>
            </div>
            <p class="text-sm text-gray-500 mb-4">Anda akan dialihkan ke halaman detail booking dalam <span id="successRedirectCountdown" class="font-semibold">3</span> detik...</p>
            <a href="#" id="goToBookingDetail" class="inline-block w-full py-2 px-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium rounded-lg transition-all duration-300 shadow-md">
                Lihat Detail Booking
            </a>
        </div>
    </div>
</div>

<!-- Modal Expired -->
<div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="expiredModal">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 z-10" id="expiredModalContent">
        <div class="bg-red-600 px-4 py-3">
            <h5 class="text-white font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Waktu Pembayaran Habis
            </h5>
        </div>
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h4 class="text-xl font-bold text-gray-800 mb-2">Booking Anda Telah Expired</h4>
            <p class="text-gray-600 mb-3">Maaf, waktu untuk melakukan pembayaran telah habis. Booking Anda telah dibatalkan secara otomatis.</p>
            <p class="text-gray-600 mb-4">Silahkan lakukan booking ulang jika Anda masih ingin menggunakan layanan kami.</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="bg-red-600 h-2 rounded-full animate-pulse" style="width: 100%"></div>
            </div>
            <p class="text-sm text-gray-500 mb-4">Anda akan dialihkan dalam <span id="redirectCountdown" class="font-semibold">5</span> detik...</p>
            <a href="<?= base_url('customer/booking') ?>" class="inline-block w-full py-2 px-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-300 shadow-md">
                Kembali ke Daftar Booking
            </a>
        </div>
    </div>
</div>

<!-- Tambahkan WebSocket client -->
<script src="<?= base_url('assets/js/booking-socket.js') ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi WebSocket client
        const bookingSocket = new BookingSocket({
            debug: true
        });

        // Countdown timer untuk waktu expired
        const progressBar = document.getElementById('progress-bar');
        const expiredAt = new Date('<?= $booking['expired_at'] ?>').getTime();
        const now = new Date().getTime();
        const totalDuration = expiredAt - now;
        const kdbooking = '<?= $booking['kdbooking'] ?>';

        // Connect ke WebSocket server
        const wsProtocol = window.location.protocol === 'https:' ? 'wss://' : 'ws://';
        const wsHost = window.location.hostname;
        const wsPort = '8080'; // Port default, sesuaikan dengan konfigurasi server WebSocket
        const wsUrl = `${wsProtocol}${wsHost}:${wsPort}`;

        bookingSocket.connect(wsUrl);

        // Register event listener untuk koneksi berhasil terbuka
        bookingSocket.on('onOpen', function() {
            console.log('WebSocket connected, registering for booking updates...');
            // Register untuk menerima update tentang booking ini
            bookingSocket.send({
                type: 'register',
                booking_code: kdbooking
            });
        });

        // Register event listener untuk booking expired
        bookingSocket.on('onBookingExpired', function(data) {
            if (data.booking_code === kdbooking) {
                console.log('Booking expired notification received via WebSocket');
                showExpiredModal();
            }
        });

        // Periksa status booking secara periodik jika WebSocket tidak tersedia
        function checkExpiredBooking() {
            const currentTime = new Date().getTime();

            if (currentTime > expiredAt) {
                // Coba gunakan WebSocket jika terhubung
                if (bookingSocket.isConnected) {
                    bookingSocket.checkBookingStatus(kdbooking);
                } else {
                    // Fallback ke AJAX jika WebSocket tidak tersedia
                    fetch('<?= site_url('customer/booking/expire/') ?>' + kdbooking, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                console.log('Booking berhasil diperbarui menjadi expired');
                                showExpiredModal();
                            } else {
                                console.error('Gagal memperbarui status booking:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            }
        }

        // Tambahkan pemeriksaan global menggunakan cron endpoint sebagai fallback
        function checkAllExpiredBookings() {
            fetch('<?= site_url('cron/check-expired-bookings') ?>?key=awanbarbershop_secret_key', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.processed > 0) {
                        console.log('Berhasil memperbarui ' + data.processed + ' booking yang expired');
                        // Cek apakah booking yang dilihat termasuk yang expired
                        fetch('<?= site_url('customer/booking/detail/') ?>' + kdbooking, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(bookingData => {
                                if (bookingData.status === 'expired') {
                                    showExpiredModal();
                                }
                            })
                            .catch(error => {
                                console.error('Error checking specific booking:', error);
                            });
                    }
                })
                .catch(error => {
                    console.error('Error checking expired bookings:', error);
                });
        }

        // Tampilkan modal expired
        function showExpiredModal() {
            const modal = document.getElementById('expiredModal');
            const modalContent = document.getElementById('expiredModalContent');

            if (modal) {
                // Tampilkan modal
                modal.classList.remove('hidden');

                // Tambahkan efek animasi shake pada modal content
                if (modalContent) {
                    setTimeout(() => {
                        modalContent.classList.add('shake');
                    }, 100);
                }

                // Nonaktifkan form pembayaran
                const form = document.getElementById('paymentForm');
                if (form) {
                    const inputs = form.querySelectorAll('input, select, button');
                    inputs.forEach(input => {
                        input.disabled = true;
                    });
                }

                // Update tampilan countdown
                const countdownElements = document.querySelectorAll('#hours, #minutes, #seconds');
                countdownElements.forEach(el => {
                    el.textContent = '00';
                    el.classList.add('bg-red-500');
                });

                if (progressBar) {
                    progressBar.style.width = '0%';
                }

                // Countdown untuk redirect
                let redirectSeconds = 5;
                const redirectCountdown = document.getElementById('redirectCountdown');
                if (redirectCountdown) {
                    redirectCountdown.textContent = redirectSeconds;
                    const redirectInterval = setInterval(() => {
                        redirectSeconds--;
                        redirectCountdown.textContent = redirectSeconds;
                        if (redirectSeconds <= 0) {
                            clearInterval(redirectInterval);
                            window.location.href = '<?= base_url('customer/booking') ?>';
                        }
                    }, 1000);
                }
            }
        }

        // Jalankan pemeriksaan saat halaman dimuat
        checkExpiredBooking();

        // Jalankan pemeriksaan setiap 30 detik sebagai fallback
        setInterval(checkExpiredBooking, 30000);
        // Jalankan pemeriksaan global setiap 60 detik sebagai fallback tambahan
        setInterval(checkAllExpiredBookings, 60000);

        // Fungsi untuk memperbarui countdown
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = expiredAt - now;

            if (distance <= 0) {
                // Waktu habis
                clearInterval(countdownInterval);
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                progressBar.style.width = '0%';
                progressBar.classList.remove('bg-green-500', 'bg-yellow-500');
                progressBar.classList.add('bg-red-500');

                // Periksa status booking
                checkExpiredBooking();
                return;
            }

            // Hitung waktu yang tersisa
            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Perbarui tampilan countdown
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');

            // Perbarui progress bar
            const percentLeft = (distance / totalDuration) * 100;
            progressBar.style.width = percentLeft + '%';

            // Ubah warna progress bar berdasarkan waktu yang tersisa
            if (percentLeft < 25) {
                progressBar.classList.remove('bg-gradient-to-r', 'from-amber-500', 'to-orange-500');
                progressBar.classList.add('bg-gradient-to-r', 'from-red-500', 'to-red-600');

                // Tambahkan animasi pulse ke angka countdown
                document.getElementById('hours').classList.add('animate-pulse');
                document.getElementById('minutes').classList.add('animate-pulse');
                document.getElementById('seconds').classList.add('animate-pulse');
            } else if (percentLeft < 50) {
                progressBar.classList.remove('bg-gradient-to-r', 'from-amber-500', 'to-orange-500');
                progressBar.classList.add('bg-gradient-to-r', 'from-orange-500', 'to-red-500');
            } else {
                progressBar.classList.remove('bg-gradient-to-r', 'from-orange-500', 'to-red-500');
                progressBar.classList.add('bg-gradient-to-r', 'from-amber-500', 'to-orange-500');
            }
        }

        // Jalankan countdown
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);

        // Tutup WebSocket ketika halaman ditutup
        window.addEventListener('beforeunload', function() {
            if (bookingSocket.isConnected) {
                bookingSocket.send({
                    type: 'unregister',
                    booking_code: kdbooking
                });
                bookingSocket.close();
            }
        });

        // Form submission dengan AJAX
        const paymentForm = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingSpinner = document.getElementById('loadingSpinner') || document.createElement('div');

        // Metode pembayaran change handler
        const metodePembayaran = document.getElementById('metode_pembayaran');
        const transferInfo = document.getElementById('transferInfo');
        const qrisInfo = document.getElementById('qrisInfo');

        metodePembayaran.addEventListener('change', function() {
            if (this.value === 'transfer') {
                transferInfo.classList.remove('hidden');
                qrisInfo.classList.add('hidden');
            } else if (this.value === 'qris') {
                transferInfo.classList.add('hidden');
                qrisInfo.classList.remove('hidden');
            }
        });

        // Trigger change event to initialize metode pembayaran
        metodePembayaran.dispatchEvent(new Event('change'));

        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Periksa apakah booking sudah expired
            const currentTime = new Date().getTime();
            if (currentTime > expiredAt) {
                showAlert('Waktu pembayaran telah habis. Silakan buat booking baru.', 'error');
                showExpiredModal();
                return;
            }

            // Tampilkan loading spinner
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;

            // Kirim form dengan AJAX
            const formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Tampilkan modal sukses pembayaran
                        showSuccessModal(data.redirect);
                    } else {
                        showAlert('Terjadi kesalahan: ' + data.message, 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = `
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Konfirmasi Pembayaran
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan: ' + error, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Konfirmasi Pembayaran
                        </div>
                    `;
                });
        });

        // Fungsi untuk menampilkan modal sukses pembayaran
        function showSuccessModal(redirectUrl) {
            const modal = document.getElementById('successModal');
            const modalContent = document.getElementById('successModalContent');
            const goToBookingDetailBtn = document.getElementById('goToBookingDetail');

            if (modal && modalContent) {
                // Tampilkan modal
                modal.classList.remove('hidden');

                // Tambahkan efek animasi bounce pada modal content
                setTimeout(() => {
                    modalContent.classList.add('bounce');
                    modalContent.style.opacity = '1';
                    modalContent.style.transform = 'scale(1)';
                }, 100);

                // Perbarui link tombol detail booking
                if (goToBookingDetailBtn) {
                    goToBookingDetailBtn.href = redirectUrl;
                }

                // Membuat konfeti tambahan secara dinamis
                createDynamicConfetti();

                // Countdown untuk redirect otomatis
                let seconds = 3;
                const countdownElement = document.getElementById('successRedirectCountdown');

                if (countdownElement) {
                    const interval = setInterval(() => {
                        seconds--;
                        countdownElement.textContent = seconds;

                        if (seconds <= 0) {
                            clearInterval(interval);
                            window.location.href = redirectUrl;
                        }
                    }, 1000);
                }
            }
        }

        // Fungsi untuk membuat konfeti dinamis
        function createDynamicConfetti() {
            const container = document.querySelector('.confetti-container');
            if (!container) return;

            const colors = ['bg-red-500', 'bg-yellow-500', 'bg-green-500', 'bg-blue-500', 'bg-purple-500', 'bg-pink-500'];
            const animations = ['confetti-slow', 'confetti-medium', 'confetti-fast'];

            // Tambahkan 20 konfeti secara acak
            for (let i = 0; i < 20; i++) {
                const confetti = document.createElement('div');
                const color = colors[Math.floor(Math.random() * colors.length)];
                const animation = animations[Math.floor(Math.random() * animations.length)];
                const size = Math.random() * 5 + 3; // Ukuran antara 3-8px
                const left = Math.random() * 100; // Posisi horizontal 0-100%

                confetti.className = `confetti ${color} absolute top-0 rounded-sm`;
                confetti.style.width = `${size}px`;
                confetti.style.height = `${size}px`;
                confetti.style.left = `${left}%`;
                confetti.style.animation = `${animation} ${Math.random() * 3 + 2}s linear infinite`;
                confetti.style.animationDelay = `${Math.random() * 2}s`;

                container.appendChild(confetti);
            }
        }

        // Fungsi untuk menampilkan alert
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300';
            const alertIcon = type === 'success' ?
                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' :
                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';

            const alert = document.createElement('div');
            alert.className = `flex items-center p-4 mb-4 border rounded-lg ${alertClass}`;
            alert.innerHTML = `
                <div class="mr-2">${alertIcon}</div>
                <div>${message}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 focus:outline-none" onclick="this.parentElement.remove()">
                    <span class="sr-only">Close</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            `;

            alertContainer.appendChild(alert);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.remove();
                }
            }, 5000);
        }

        // Image preview
        const imageInput = document.getElementById('bukti_pembayaran');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('preview-img');
        const fileNameDisplay = document.getElementById('file-name');
        const removeButton = document.getElementById('remove-image');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    previewContainer.classList.add('animate-fadeIn');
                    fileNameDisplay.textContent = file.name;
                }
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function() {
            imageInput.value = '';
            previewContainer.classList.add('hidden');
            fileNameDisplay.textContent = 'Pilih file bukti pembayaran';
        });

        // Jenis pembayaran change handler
        const jenispembayaran = document.getElementById('jenispembayaran');
        const dpAmountContainer = document.getElementById('dpAmountContainer');
        const minPaymentInput = document.getElementById('min_payment');

        jenispembayaran.addEventListener('change', function() {
            if (this.value === 'DP') {
                dpAmountContainer.classList.remove('hidden');
                minPaymentInput.required = true;
                // Set default value to 50% of total
                const total = <?= $booking['total'] ?>;
                minPaymentInput.value = Math.round(total * 0.5);
            } else {
                dpAmountContainer.classList.add('hidden');
                minPaymentInput.required = false;
            }
        });

        // Trigger change event to initialize form
        jenispembayaran.dispatchEvent(new Event('change'));
    });
</script>
<?= $this->endSection() ?>