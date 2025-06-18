<!-- Navbar -->
<nav class="fixed w-full z-50 transition-all duration-300 navbar-transparent" id="navbar">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="<?= base_url() ?>" class="flex items-center space-x-3 group">
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
                    <a href="<?= base_url() ?>#beranda" class="nav-link block py-2 px-3 rounded md:p-0">Beranda</a>
                </li>
                <li>
                    <a href="<?= base_url() ?>#layanan" class="nav-link block py-2 px-3 rounded md:p-0">Layanan</a>
                </li>
                <li>
                    <a href="<?= base_url() ?>#tentang" class="nav-link block py-2 px-3 rounded md:p-0">Tentang</a>
                </li>
                <li>
                    <a href="<?= base_url() ?>#kontak" class="nav-link block py-2 px-3 rounded md:p-0">Kontak</a>
                </li>
                <li>
                    <a href="<?= session()->get('logged_in') && session()->get('role') == 'pelanggan' ? base_url('customer/booking/create') : base_url('customer/login?redirect=booking') ?>" class="nav-link block py-2 px-3 rounded md:p-0">Booking</a>
                </li>
                <li>
                    <?php if (isset($_SESSION['pelanggan']) || session()->get('logged_in')) : ?>
                        <div class="flex items-center space-x-4">
                            <a href="<?= base_url('customer/profil') ?>" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full font-medium text-sm text-white bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] hover:from-[#F1C40F] hover:to-[#E74C3C] transform hover:scale-105 transition-all duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profil</span>
                            </a>
                            <a href="<?= base_url('customer/logout') ?>" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full font-medium text-sm text-white bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] hover:from-[#F1C40F] hover:to-[#E74C3C] transform hover:scale-105 transition-all duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Logout</span>
                            </a>
                        </div>
                    <?php else : ?>
                        <a href="<?= base_url('customer/login') ?>" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full font-medium text-sm text-white bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] hover:from-[#F1C40F] hover:to-[#E74C3C] transform hover:scale-105 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Login/Daftar</span>
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>