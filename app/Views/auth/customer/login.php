<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - Awan Barbershop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-[#2C3E50] to-[#3498DB] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-md w-full max-w-md p-8 rounded-2xl shadow-xl">
        <div class="text-center mb-8">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" class="w-24 h-24 mx-auto mb-4 rounded-full bg-white p-2" onerror="this.src='https://ui-avatars.com/api/?name=Awan+Barbershop&background=A27B5C&color=fff'">
            <h1 class="text-3xl font-bold text-white mb-2">Login Pelanggan</h1>
            <p class="text-gray-200">Masuk untuk menikmati layanan terbaik kami</p>
        </div>

        <?php if (session()->getFlashdata('message')) : ?>
            <div class="bg-white/10 backdrop-blur-md text-white p-4 rounded-lg mb-6">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-white mb-2" for="username">
                    Username atau Email
                </label>
                <input type="text" id="username" name="username" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:border-[#F1C40F] transition duration-300"
                    placeholder="Masukkan username atau email">
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2" for="password">
                    Password
                </label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:border-[#F1C40F] transition duration-300"
                    placeholder="Masukkan password">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember"
                    class="w-4 h-4 text-[#F1C40F] bg-white/5 border-white/10 rounded focus:ring-[#F1C40F]">
                <label for="remember" class="ml-2 text-sm text-gray-200">
                    Ingat saya
                </label>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] text-white font-medium py-3 px-4 rounded-lg hover:opacity-90 transition duration-300 transform hover:scale-[1.02]">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-300">
                Belum punya akun?
                <a href="<?= site_url('customer/register') ?>" class="text-[#F1C40F] hover:underline">Daftar di sini</a>
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
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '<?= site_url('customer/login') ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            window.location.href = response.redirect;
                        } else if (response.status === 'pending_verification') {
                            alert(response.message);
                            window.location.href = response.redirect;
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
</body>

</html>