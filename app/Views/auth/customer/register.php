<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pelanggan - Awan Barbershop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .spinner {
            display: none;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .btn-disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-[#2C3E50] to-[#3498DB] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-md w-full max-w-md p-8 rounded-2xl shadow-xl">
        <div class="text-center mb-8">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" class="w-24 h-24 mx-auto mb-4 rounded-full bg-white p-2" onerror="this.src='https://ui-avatars.com/api/?name=Awan+Barbershop&background=A27B5C&color=fff'">
            <h1 class="text-3xl font-bold text-white mb-2">Registrasi Pelanggan</h1>
            <p class="text-gray-200">Daftar untuk mendapatkan layanan terbaik</p>
        </div>

        <form id="registerForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-white mb-2" for="name">
                    Nama Lengkap
                </label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:border-[#F1C40F] transition duration-300"
                    placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2" for="username">
                    Username
                </label>
                <input type="text" id="username" name="username" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:border-[#F1C40F] transition duration-300"
                    placeholder="Masukkan username">
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2" for="email">
                    Email
                </label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:border-[#F1C40F] transition duration-300"
                    placeholder="Masukkan email">
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2" for="password">
                    Password
                </label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:border-[#F1C40F] transition duration-300"
                    placeholder="Masukkan password">
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] text-white font-medium py-3 px-4 rounded-lg hover:opacity-90 transition duration-300 transform hover:scale-[1.02] flex items-center justify-center">
                <span>Daftar</span>
                <svg class="spinner ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-300">
                Sudah punya akun?
                <a href="<?= site_url('customer/login') ?>" class="text-[#F1C40F] hover:underline">Login di sini</a>
            </p>
        </div>

        <div class="mt-4 text-center">
            <a href="<?= site_url() ?>" class="text-gray-300 hover:text-white text-sm">
                <span class="inline-block transform rotate-180">➜</span> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#submitBtn');
                const spinner = $('.spinner');

                // Disable button and show spinner
                submitBtn.addClass('btn-disabled');
                spinner.show();

                $.ajax({
                    url: '<?= site_url('customer/doRegister') ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success' || response.status === 'pending_verification') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonColor: '#E74C3C'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect;
                                }
                            });
                        } else {
                            let errorMessage = '';
                            if (typeof response.message === 'object') {
                                Object.values(response.message).forEach(function(msg) {
                                    errorMessage += msg + '<br>';
                                });
                            } else {
                                errorMessage = response.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: errorMessage,
                                confirmButtonColor: '#E74C3C'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan. Silakan coba lagi.',
                            confirmButtonColor: '#E74C3C'
                        });
                    },
                    complete: function() {
                        // Enable button and hide spinner
                        submitBtn.removeClass('btn-disabled');
                        spinner.hide();
                    }
                });
            });
        });
    </script>
</body>

</html>