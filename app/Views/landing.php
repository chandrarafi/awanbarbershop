<?= $this->extend('templates/main') ?>
<?= $this->section('content') ?>
<!-- Hero Section with Swiper -->
<section id="beranda" class="h-screen relative">
    <div class="swiper heroSwiper h-full">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide relative">
                <img src="https://images.unsplash.com/photo-1596728325488-58c87691e9af?q=80&w=2073&auto=format&fit=crop&ixlib=rb-4.1.0" alt="Barbershop 1" class="w-full h-full object-cover">
                <div class="absolute inset-0 gradient-overlay flex items-center">
                    <div class="container mx-auto px-4">
                        <div class="max-w-3xl" data-aos="fade-right">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 sm:mb-6 leading-tight">
                                <span class="gradient-text">Tampilan Terbaik</span> untuk Kepercayaan Diri Maksimal
                            </h1>
                            <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-6 sm:mb-8 leading-relaxed px-2 sm:px-0">Awan Barbershop hadir untuk memberikan pengalaman grooming terbaik dengan layanan profesional.</p>
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 px-2 sm:px-0">
                                <a href="#layanan" class="btn-primary px-6 sm:px-8 py-3 sm:py-4 rounded-full text-base sm:text-lg font-medium inline-flex items-center justify-center transform hover:scale-105 transition-all duration-300">
                                    <span>Lihat Layanan</span>
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                <a href="#kontak" class="bg-white/10 backdrop-blur-md text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full text-base sm:text-lg font-medium inline-flex items-center justify-center hover:bg-white/20 transform hover:scale-105 transition-all duration-300">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide relative">
                <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=2070" alt="Barbershop 2" class="w-full h-full object-cover">
                <div class="absolute inset-0 gradient-overlay flex items-center">
                    <div class="container mx-auto px-4">
                        <div class="max-w-3xl" data-aos="fade-right">
                            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 sm:mb-6 leading-tight">
                                <span class="gradient-text">Layanan Premium</span> untuk Hasil Maksimal
                            </h2>
                            <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-6 sm:mb-8 leading-relaxed px-2 sm:px-0">Tim profesional kami siap memberikan pelayanan terbaik untuk penampilan anda.</p>
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 px-2 sm:px-0">
                                <a href="#layanan" class="btn-primary px-6 sm:px-8 py-3 sm:py-4 rounded-full text-base sm:text-lg font-medium inline-flex items-center justify-center transform hover:scale-105 transition-all duration-300">
                                    <span>Mulai Sekarang</span>
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                <a href="#kontak" class="bg-white/10 backdrop-blur-md text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full text-base sm:text-lg font-medium inline-flex items-center justify-center hover:bg-white/20 transform hover:scale-105 transition-all duration-300">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <div class="custom-shape-divider">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ECF0F1"></path>
        </svg>
    </div>
</section>

<!-- Layanan Section -->
<section id="layanan" class="py-12 sm:py-16 lg:py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-[#ECF0F1] to-white"></div>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16" data-aos="fade-up">
            <span class="text-xs sm:text-sm font-semibold text-gray-600 tracking-wider uppercase mb-2 block">Layanan Kami</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4 gradient-text">Pilihan Layanan Premium</h2>
            <p class="text-base sm:text-lg text-gray-600 px-4 sm:px-0">Temukan layanan yang sesuai dengan kebutuhan dan gaya Anda</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            <?php foreach ($pakets as $paket) : ?>
                <div class="bg-white rounded-2xl p-4 sm:p-6 lg:p-8 shadow-lg card-hover service-card animated-border" data-aos="fade-up" data-aos-delay="150">
                    <div class="mb-4 sm:mb-6 overflow-hidden rounded-xl relative group">
                        <img src="<?= $paket['image'] ? base_url('uploads/paket/' . $paket['image']) : base_url('assets/images/imgnotfound.jpg') ?>"
                            alt="<?= $paket['namapaket'] ?>"
                            class="w-full h-40 sm:h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <div class="p-3 sm:p-4 w-full">
                                <span class="text-white text-xs sm:text-sm font-medium">Lihat Detail</span>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold mb-3 sm:mb-4 gradient-text"><?= $paket['namapaket'] ?></h3>
                    <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6 line-clamp-2"><?= $paket['deskripsi'] ?></p>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
                        <span class="text-lg sm:text-xl lg:text-2xl font-bold gradient-text">Rp <?= number_format(str_replace(',00', '', $paket['harga']), 0, ',', '.') ?></span>
                        <?php if (session()->get('logged_in') && session()->get('role') == 'pelanggan'): ?>
                            <a href="<?= site_url('customer/booking/create?paket=' . $paket['idpaket']) ?>" class="btn-primary px-4 sm:px-6 py-2 sm:py-3 rounded-full inline-flex items-center justify-center transform hover:scale-105 transition-all duration-300 w-full sm:w-auto text-sm sm:text-base">
                                <span>Booking</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url('customer/login?redirect=booking') ?>" class="btn-primary px-4 sm:px-6 py-2 sm:py-3 rounded-full inline-flex items-center justify-center transform hover:scale-105 transition-all duration-300 w-full sm:w-auto text-sm sm:text-base">
                                <span class="hidden sm:inline">Login untuk Booking</span>
                                <span class="sm:hidden">Login</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Tentang Section -->
<section id="tentang" class="py-12 sm:py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-white to-[#ECF0F1]"></div>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-16 items-center">
            <div class="lg:py-24" data-aos="fade-right">
                <span class="text-xs sm:text-sm font-semibold text-gray-600 tracking-wider uppercase mb-2 block">Tentang Kami</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold gradient-text mb-4 sm:mb-6">Awan Barbershop</h2>

                <p class="mt-3 sm:mt-4 text-sm sm:text-base text-gray-600 leading-relaxed">
                    Awan Barbershop telah melayani pelanggan sejak tahun 2020 dengan komitmen untuk memberikan layanan terbaik dalam perawatan rambut pria. Dengan tim kapster profesional dan berpengalaman, kami siap membantu Anda mendapatkan penampilan terbaik.
                </p>

                <p class="mt-3 sm:mt-4 text-sm sm:text-base text-gray-600 leading-relaxed">
                    Kami menggunakan peralatan dan produk berkualitas untuk memastikan hasil yang maksimal dan kepuasan pelanggan.
                </p>

                <div class="mt-6 sm:mt-8 grid grid-cols-3 gap-2 sm:gap-4">
                    <div class="bg-white rounded-lg p-3 sm:p-4 shadow-lg text-center card-hover">
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold gradient-text">3+</h3>
                        <p class="text-xs sm:text-sm text-gray-600">Tahun Pengalaman</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 sm:p-4 shadow-lg text-center card-hover">
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold gradient-text">1000+</h3>
                        <p class="text-xs sm:text-sm text-gray-600">Pelanggan Puas</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 sm:p-4 shadow-lg text-center card-hover">
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold gradient-text">5+</h3>
                        <p class="text-xs sm:text-sm text-gray-600">Kapster Ahli</p>
                    </div>
                </div>
            </div>

            <div class="relative h-48 sm:h-64 lg:h-80 xl:h-full overflow-hidden rounded-2xl shadow-2xl" data-aos="fade-left">
                <img
                    alt="Barbershop Interior"
                    src="<?= base_url('assets/images/hero1.jpg') ?>"
                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 hover:scale-105" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            </div>
        </div>
    </div>
</section>

<!-- Kontak Section -->
<section id="kontak" class="py-12 sm:py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-[#ECF0F1] to-white"></div>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center" data-aos="fade-up">
            <span class="text-xs sm:text-sm font-semibold text-gray-600 tracking-wider uppercase mb-2 block">Kontak</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold gradient-text mb-3 sm:mb-4">Hubungi Kami</h2>
            <p class="mt-3 sm:mt-4 text-sm sm:text-base text-gray-600">Kami siap melayani Anda</p>
        </div>

        <div class="mt-8 sm:mt-12 grid grid-cols-1 gap-6 sm:gap-8 lg:grid-cols-2">
            <div data-aos="fade-right">
                <h3 class="text-base sm:text-lg font-bold gradient-text mb-4 sm:mb-6">Informasi Kontak</h3>
                <div class="space-y-4 sm:space-y-6">
                    <div class="bg-white rounded-xl p-4 sm:p-6 card-hover animated-border">
                        <div class="flex items-start">
                            <div class="p-2 sm:p-3 bg-gradient-to-br from-[#E74C3C] to-[#F1C40F] rounded-lg text-white flex-shrink-0">
                                <svg class="h-4 w-4 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h4 class="text-sm sm:text-lg font-semibold text-gray-900">Alamat</h4>
                                <p class="mt-1 text-xs sm:text-sm text-gray-600">Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 sm:p-6 card-hover animated-border">
                        <div class="flex items-start">
                            <div class="p-2 sm:p-3 bg-gradient-to-br from-[#E74C3C] to-[#F1C40F] rounded-lg text-white flex-shrink-0">
                                <svg class="h-4 w-4 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h4 class="text-sm sm:text-lg font-semibold text-gray-900">Telepon</h4>
                                <p class="mt-1 text-xs sm:text-sm text-gray-600">+62 123 4567 890</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 sm:p-6 card-hover animated-border">
                        <div class="flex items-start">
                            <div class="p-2 sm:p-3 bg-gradient-to-br from-[#E74C3C] to-[#F1C40F] rounded-lg text-white flex-shrink-0">
                                <svg class="h-4 w-4 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h4 class="text-sm sm:text-lg font-semibold text-gray-900">Jam Operasional</h4>
                                <p class="mt-1 text-xs sm:text-sm text-gray-600">Senin - Minggu: 09:00 - 21:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-left" class="relative mt-8 lg:mt-0">
                <div class="absolute inset-0 bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] rounded-2xl transform rotate-1"></div>
                <iframe
                    class="w-full h-64 sm:h-80 lg:h-96 rounded-2xl relative transform -rotate-1 transition-transform hover:rotate-0 duration-300"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.292503681792!2d100.42455489999999!3d-0.9302143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b9ba771810a1%3A0x5360eeef57155078!2sAWAN%20barbershop!5e0!3m2!1sid!2sid!4v1756983295959!5m2!1sid!2sid"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>