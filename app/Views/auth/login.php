<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Awan Barbershop Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2C3639;
            --secondary-color: #3F4E4F;
            --accent-color: #A27B5C;
            --light-color: #DCD7C9;
        }

        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: var(--primary-color);
            padding: 30px 20px;
            text-align: center;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: hidden;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent-color);
        }

        .login-header h4 {
            color: white;
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .login-header p {
            color: var(--light-color);
            margin: 10px 0 0;
            font-size: 1rem;
        }

        .login-body {
            padding: 40px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--accent-color);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 2px solid #e1e1e1;
            border-right: none;
            background-color: white;
        }

        .btn-login {
            background: var(--accent-color);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-login:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .brand-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
            border-radius: 50%;
            padding: 10px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            z-index: 1000;
        }

        .form-label {
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .remember-me {
            color: var(--secondary-color);
        }

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        .btn-outline-secondary {
            border-color: #e1e1e1;
            color: var(--secondary-color);
            border-radius: 0 12px 12px 0;
            border-left: none;
        }

        .btn-outline-secondary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }

            .login-body {
                padding: 20px;
            }

            .login-header h4 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card position-relative">
                <div class="loading-overlay" id="loadingOverlay">
                    <div class="spinner-border text-accent" style="color: var(--accent-color)" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="login-header">
                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" class="brand-logo" onerror="this.src='https://ui-avatars.com/api/?name=Awan+Barbershop&background=A27B5C&color=fff'">
                    <h4>Awan Barbershop</h4>
                    <p>Admin Panel</p>
                </div>

                <div class="login-body">
                    <?php if (session()->getFlashdata('message')) : ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div id="loginError" class="alert alert-danger alert-dismissible fade show" style="display: none;" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="errorMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form id="loginForm" method="post">
                        <div class="mb-4">
                            <label for="username" class="form-label">Username atau Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="username" required
                                    placeholder="Masukkan username atau email">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required
                                    placeholder="Masukkan password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label remember-me" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100" id="btnLogin">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle Password Visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Handle form submission
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                // Show loading overlay
                $('#loadingOverlay').css('display', 'flex');

                // Disable form
                $('#loginForm :input').prop('disabled', true);

                // Get form data
                const username = $('#username').val();
                const password = $('#password').val();
                const remember = $('#remember').prop('checked') ? 'on' : 'off';

                $.ajax({
                    url: '<?= site_url('auth/login') ?>',
                    type: 'POST',
                    data: {
                        username: username,
                        password: password,
                        remember: remember
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {

                            $('#loginError').hide();
                            toastr.success(response.message, 'Berhasil!');
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1000);
                        } else {
                            $('#errorMessage').text(response.message);
                            $('#loginError').show();
                        }
                    },
                    error: function() {
                        $('#errorMessage').text('Terjadi kesalahan. Silakan coba lagi.');
                        $('#loginError').show();
                    },
                    complete: function() {
                        // Hide loading overlay
                        $('#loadingOverlay').hide();
                        // Enable form
                        $('#loginForm :input').prop('disabled', false);
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').not('#loginError').alert('close');
            }, 5000);
        });
    </script>
</body>

</html>