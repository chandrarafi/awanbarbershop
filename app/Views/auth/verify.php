<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Awan Barbershop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .otp-input {
            width: 3rem;
            height: 3rem;
            text-align: center;
            font-size: 1.5rem;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-[#2C3E50] to-[#3498DB] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-md w-full max-w-md p-8 rounded-2xl shadow-xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Verifikasi OTP</h1>
            <p class="text-gray-200">Masukkan kode OTP yang telah dikirim ke email Anda</p>
        </div>

        <form id="verifyForm" class="space-y-6">
            <div class="flex justify-center space-x-2">
                <input type="text" maxlength="1" class="otp-input bg-white/5 border border-white/10 text-white rounded-lg focus:outline-none focus:border-[#F1C40F] transition duration-300" required>
                <input type="text" maxlength="1" class="otp-input bg-white/5 border border-white/10 text-white rounded-lg focus:outline-none focus:border-[#F1C40F] transition duration-300" required>
                <input type="text" maxlength="1" class="otp-input bg-white/5 border border-white/10 text-white rounded-lg focus:outline-none focus:border-[#F1C40F] transition duration-300" required>
                <input type="text" maxlength="1" class="otp-input bg-white/5 border border-white/10 text-white rounded-lg focus:outline-none focus:border-[#F1C40F] transition duration-300" required>
                <input type="text" maxlength="1" class="otp-input bg-white/5 border border-white/10 text-white rounded-lg focus:outline-none focus:border-[#F1C40F] transition duration-300" required>
                <input type="text" maxlength="1" class="otp-input bg-white/5 border border-white/10 text-white rounded-lg focus:outline-none focus:border-[#F1C40F] transition duration-300" required>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-[#E74C3C] to-[#F1C40F] text-white font-medium py-3 px-4 rounded-lg hover:opacity-90 transition duration-300 transform hover:scale-[1.02]">
                Verifikasi
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-300">
                Tidak menerima kode?
                <button id="resendBtn" class="text-[#F1C40F] hover:underline">Kirim ulang</button>
            </p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle OTP input
            $('.otp-input').on('input', function() {
                if (this.value.length === 1) {
                    $(this).next('.otp-input').focus();
                }
            });

            $('.otp-input').on('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value) {
                    $(this).prev('.otp-input').focus();
                }
            });

            // Handle form submit
            $('#verifyForm').on('submit', function(e) {
                e.preventDefault();

                let otp = '';
                $('.otp-input').each(function() {
                    otp += $(this).val();
                });

                $.ajax({
                    url: '<?= site_url('auth/doVerify') ?>',
                    type: 'POST',
                    data: {
                        otp: otp
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
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

            // Handle resend OTP
            let cooldown = 0;
            const updateCooldownText = () => {
                if (cooldown > 0) {
                    $('#resendBtn').text(`Tunggu ${cooldown} detik`).prop('disabled', true);
                } else {
                    $('#resendBtn').text('Kirim ulang').prop('disabled', false);
                }
            };

            $('#resendBtn').click(function() {
                if (cooldown > 0) return;

                $.ajax({
                    url: '<?= site_url('auth/resendOTP') ?>',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message);
                        if (response.status === 'success') {
                            cooldown = 60;
                            updateCooldownText();

                            const interval = setInterval(() => {
                                cooldown--;
                                updateCooldownText();
                                if (cooldown === 0) {
                                    clearInterval(interval);
                                }
                            }, 1000);
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