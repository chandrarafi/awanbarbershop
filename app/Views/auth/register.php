<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Awan Barbershop</title>
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
            <h1 class="text-3xl font-bold text-white mb-2">Registrasi</h1>
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

            <button type="submit"
                class="w-full bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] text-white font-medium py-3 px-4 rounded-lg hover:opacity-90 transition duration-300 transform hover:scale-[1.02]">
                Daftar
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-300">
                Sudah punya akun?
                <a href="<?= site_url('auth') ?>" class="text-[#F1C40F] hover:underline">Login di sini</a>
            </p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '<?= site_url('auth/doRegister') ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            window.location.href = response.redirect;
                        } else {
                            let errorMessage = '';
                            if (typeof response.message === 'object') {
                                Object.values(response.message).forEach(function(msg) {
                                    errorMessage += msg + '\n';
                                });
                            } else {
                                errorMessage = response.message;
                            }
                            alert(errorMessage);
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