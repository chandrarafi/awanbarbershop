<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awan Barbershop - Tempat Cukur Rambut Terbaik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #2C3E50;
            --secondary-color: #E74C3C;
            --accent-color: #F1C40F;
            --dark-color: #1a1a1a;
            --light-color: #ECF0F1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-color);
        }

        .swiper {
            width: 100%;
            height: 100vh;
        }

        .gradient-overlay {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(0, 0, 0, 0.4) 100%);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border-color: var(--secondary-color);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .service-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(231, 76, 60, 0.2), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }

        .service-card:hover::before {
            animation: shine 1.5s;
        }

        @keyframes shine {
            0% {
                opacity: 0;
                transform: translateX(-100%) rotate(45deg);
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                transform: translateX(100%) rotate(45deg);
            }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .custom-shape-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .highlight-container {
            position: relative;
            display: inline-block;
        }

        .highlight-container::before {
            content: "";
            position: absolute;
            left: -0.25em;
            right: -0.25em;
            top: 0.1em;
            bottom: 0.1em;
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.2), rgba(241, 196, 15, 0.2));
            z-index: -1;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .highlight-container:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .nav-link {
            position: relative;
            color: white;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent-color);
        }

        .mobile-menu {
            background: rgba(44, 62, 80, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            color: white;
            transition: all 0.4s ease;
            border: none;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            z-index: -1;
            transition: opacity 0.4s ease;
            opacity: 0;
        }

        .btn-primary:hover::before {
            opacity: 1;
        }

        .animated-border {
            position: relative;
        }

        .animated-border::after {
            content: '';
            position: absolute;
            inset: 0;
            border: 2px solid transparent;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color)) border-box;
            -webkit-mask:
                linear-gradient(#fff 0 0) padding-box,
                linear-gradient(#fff 0 0);
            mask:
                linear-gradient(#fff 0 0) padding-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .animated-border:hover::after {
            opacity: 1;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-color);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
        }

        /* Navbar Styles */
        .navbar-fixed {
            background: rgba(44, 62, 80, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-transparent {
            background: transparent;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-[#2C3E50] to-[#ECF0F1]">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 navbar-transparent" id="navbar">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 group">
                <span class="self-center text-2xl font-bold whitespace-nowrap text-white group-hover:text-[#F1C40F] transition-colors duration-300">Awan Barbershop</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200">
                <span class="sr-only">Buka menu utama</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 mobile-menu md:bg-transparent">
                    <li>
                        <a href="#beranda" class="nav-link block py-2 px-3 rounded md:p-0">Beranda</a>
                    </li>
                    <li>
                        <a href="#layanan" class="nav-link block py-2 px-3 rounded md:p-0">Layanan</a>
                    </li>
                    <li>
                        <a href="#tentang" class="nav-link block py-2 px-3 rounded md:p-0">Tentang</a>
                    </li>
                    <li>
                        <a href="#kontak" class="nav-link block py-2 px-3 rounded md:p-0">Kontak</a>
                    </li>
                    <li>
                        <a href="<?= base_url('customer/login') ?>" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full font-medium text-sm text-white bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] hover:from-[#F1C40F] hover:to-[#E74C3C] transform hover:scale-105 transition-all duration-300">
                            <span>Login/Daftar</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                                    <span class="gradient-text">Tampilan Terbaik</span> untuk Kepercayaan Diri Maksimal
                                </h1>
                                <p class="text-xl text-gray-200 mb-8 leading-relaxed">Awan Barbershop hadir untuk memberikan pengalaman grooming terbaik dengan layanan profesional.</p>
                                <div class="flex space-x-4">
                                    <a href="#layanan" class="btn-primary px-8 py-4 rounded-full text-lg font-medium inline-flex items-center transform hover:scale-105 transition-all duration-300">
                                        <span>Lihat Layanan</span>
                                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                    <a href="#kontak" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-full text-lg font-medium inline-flex items-center hover:bg-white/20 transform hover:scale-105 transition-all duration-300">
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
                                <h2 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                                    <span class="gradient-text">Layanan Premium</span> untuk Hasil Maksimal
                                </h2>
                                <p class="text-xl text-gray-200 mb-8 leading-relaxed">Tim profesional kami siap memberikan pelayanan terbaik untuk penampilan anda.</p>
                                <div class="flex space-x-4">
                                    <a href="#layanan" class="btn-primary px-8 py-4 rounded-full text-lg font-medium inline-flex items-center transform hover:scale-105 transition-all duration-300">
                                        <span>Mulai Sekarang</span>
                                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                    <a href="#kontak" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-full text-lg font-medium inline-flex items-center hover:bg-white/20 transform hover:scale-105 transition-all duration-300">
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
    <section id="layanan" class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-[#ECF0F1] to-white"></div>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-sm font-semibold text-gray-600 tracking-wider uppercase mb-2 block">Layanan Kami</span>
                <h2 class="text-4xl font-bold mb-4 gradient-text">Pilihan Layanan Premium</h2>
                <p class="text-lg text-gray-600">Temukan layanan yang sesuai dengan kebutuhan dan gaya Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($pakets as $paket) : ?>
                    <div class="bg-white rounded-2xl p-8 shadow-lg card-hover service-card animated-border" data-aos="fade-up" data-aos-delay="150">
                        <div class="mb-6 overflow-hidden rounded-xl relative group">
                            <img src="<?= $paket['image'] ? base_url('uploads/paket/' . $paket['image']) : base_url('assets/images/imgnotfound.jpg') ?>"
                                alt="<?= $paket['namapaket'] ?>"
                                class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                <div class="p-4 w-full">
                                    <span class="text-white text-sm font-medium">Lihat Detail</span>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 gradient-text"><?= $paket['namapaket'] ?></h3>
                        <p class="text-gray-600 mb-6 line-clamp-2"><?= $paket['deskripsi'] ?></p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold gradient-text">Rp <?= number_format(str_replace(',00', '', $paket['harga']), 0, ',', '.') ?></span>
                            <a href="#" class="btn-primary px-6 py-3 rounded-full inline-flex items-center transform hover:scale-105 transition-all duration-300">
                                <span>Pesan</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-white to-[#ECF0F1]"></div>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-16 items-center">
                <div class="lg:py-24" data-aos="fade-right">
                    <span class="text-sm font-semibold text-gray-600 tracking-wider uppercase mb-2 block">Tentang Kami</span>
                    <h2 class="text-3xl font-bold sm:text-4xl gradient-text mb-6">Awan Barbershop</h2>

                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Awan Barbershop telah melayani pelanggan sejak tahun 2020 dengan komitmen untuk memberikan layanan terbaik dalam perawatan rambut pria. Dengan tim kapster profesional dan berpengalaman, kami siap membantu Anda mendapatkan penampilan terbaik.
                    </p>

                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Kami menggunakan peralatan dan produk berkualitas untuk memastikan hasil yang maksimal dan kepuasan pelanggan.
                    </p>

                    <div class="mt-8 flex gap-4">
                        <div class="bg-white rounded-lg p-4 shadow-lg flex-1 text-center card-hover">
                            <h3 class="text-2xl font-bold gradient-text">3+</h3>
                            <p class="text-gray-600">Tahun Pengalaman</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-lg flex-1 text-center card-hover">
                            <h3 class="text-2xl font-bold gradient-text">1000+</h3>
                            <p class="text-gray-600">Pelanggan Puas</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-lg flex-1 text-center card-hover">
                            <h3 class="text-2xl font-bold gradient-text">5+</h3>
                            <p class="text-gray-600">Kapster Ahli</p>
                        </div>
                    </div>
                </div>

                <div class="relative h-64 overflow-hidden rounded-2xl sm:h-80 lg:h-full shadow-2xl" data-aos="fade-left">
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
    <section id="kontak" class="py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-[#ECF0F1] to-white"></div>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center" data-aos="fade-up">
                <span class="text-sm font-semibold text-gray-600 tracking-wider uppercase mb-2 block">Kontak</span>
                <h2 class="text-3xl font-bold sm:text-4xl gradient-text mb-4">Hubungi Kami</h2>
                <p class="mt-4 text-gray-600">Kami siap melayani Anda</p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2">
                <div data-aos="fade-right">
                    <h3 class="text-lg font-bold gradient-text mb-6">Informasi Kontak</h3>
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl p-6 card-hover animated-border">
                            <div class="flex items-start">
                                <div class="p-3 bg-gradient-to-br from-[#E74C3C] to-[#F1C40F] rounded-lg text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Alamat</h4>
                                    <p class="mt-1 text-gray-600">Jl. Contoh No. 123, Kota, Provinsi</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 card-hover animated-border">
                            <div class="flex items-start">
                                <div class="p-3 bg-gradient-to-br from-[#E74C3C] to-[#F1C40F] rounded-lg text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Telepon</h4>
                                    <p class="mt-1 text-gray-600">+62 123 4567 890</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 card-hover animated-border">
                            <div class="flex items-start">
                                <div class="p-3 bg-gradient-to-br from-[#E74C3C] to-[#F1C40F] rounded-lg text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Jam Operasional</h4>
                                    <p class="mt-1 text-gray-600">Senin - Minggu: 09:00 - 21:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-left" class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] rounded-2xl transform rotate-1"></div>
                    <iframe
                        class="w-full h-96 rounded-2xl relative transform -rotate-1 transition-transform hover:rotate-0 duration-300"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.6664463317476!2d106.82496851476882!3d-6.175392395527934!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1647831234567!5m2!1sid!2sid"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-[#2C3E50] to-[#1a1a1a] relative overflow-hidden">
        <div class="custom-shape-divider-top">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ECF0F1"></path>
            </svg>
        </div>
        <div class="max-w-screen-xl mx-auto px-4 py-16 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div data-aos="fade-up">
                    <h2 class="text-2xl font-bold gradient-text mb-4">Awan Barbershop</h2>
                    <p class="text-gray-400 leading-relaxed">
                        Tempat cukur rambut profesional dengan pelayanan terbaik untuk kepuasan pelanggan.
                    </p>
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 lg:col-span-2 lg:grid-cols-3">
                    <div data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-white font-bold mb-4">Layanan</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Haircut
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Shaving
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Hair Coloring
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Hair Treatment
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-white font-bold mb-4">Informasi</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Tentang Kami
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Kontak
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Booking
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    FAQ
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="300">
                        <h3 class="text-white font-bold mb-4">Sosial Media</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Instagram
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Facebook
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Twitter
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    WhatsApp
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-12 border-t border-gray-800 pt-8">
                <p class="text-center text-gray-400">&copy; 2024 Awan Barbershop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Swiper initialization
        var swiper = new Swiper(".heroSwiper", {
            spaceBetween: 0,
            effect: "fade",
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });

        // Enhanced Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.remove('navbar-transparent');
                navbar.classList.add('navbar-fixed');
            } else {
                navbar.classList.remove('navbar-fixed');
                navbar.classList.add('navbar-transparent');
            }
        });

        // Enhanced Active link highlighting
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - sectionHeight / 3)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('active');
                }
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>