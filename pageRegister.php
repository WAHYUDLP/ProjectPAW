<?php session_start();
require 'db.php';

// Ambil email jika sebelumnya dikirim
$name = $_SESSION['old_name'] ?? '';

$email = $_SESSION['old_email'] ?? '';

?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="script.js" defer></script>

    <title>BloodWellness</title>
</head>

<body class="bodyLogin">
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="aset/logo.png" alt="Logo" class="logo">
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="pageRegister.php"
                            class="active">Beranda</a></li>
                    <li><a href="kalkulator.php">Kalkulator Kalori</a></li>
                    <li><a href="pagePlanner.php">Perencana Makanan</a></li>
                    <li><a href="pageProfil.php">Profil</a></li>
                </ul>
            </nav>
        </div>

        <div class="auth-buttons">
            <a href="pageLogin.php">Masuk</a>
            <a href="pageRegister.php">Daftar</a>
        </div>

    </header>

    <!-- Navbar Mobile -->
    <div class="mobile-navbar">
        <ul>
            <li><a href="pageRegister.php#home" id="home" class="active"><i class="fas fa-home"></i></a></li>
            <li><a href="kalkulator.php#kalkulator" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
            <li><a href="pagePlanner.php#makanan" id="makanan"><i class="fas fa-utensils"></i></a></li>
            <li><a href="pageProfil.php#profil" id="profil"><i class="fas fa-user"></i></a></li>
        </ul>
    </div>

    <main>
        <div class="container">
            <!-- Bagian Logo dan Nama BloodWellness -->


            <div class="slider-container">
                <div class="brand-header">
                    <img src="aset/logo.png" alt="Logo" class="logo2">
                    <span class="brand-name">BloodWellness</span>
                </div>

                <div class="slides">
                    <div class="slide">
                        <h2>Temukan pola makan sehat sesuai
                            tipe darah Anda!</h2>
                        <p>Dengan fitur hitung kalori dan meal planner
                            otomatis, BloodWellness membantu Anda
                            merencanakan menu harian lengkap dengan resep
                            yang lezat dan bergizi.</p>
                    </div>
                    <div class="slide">
                        <h2>Sesuaikan makanan dengan kebutuhan
                            tubuh Anda</h2>
                        <p>BloodWellness memberikan rekomendasi makanan yang
                            cocok berdasarkan tipe darah Anda untuk
                            kesehatan yang lebih optimal.</p>
                    </div>
                    <div class="slide">
                        <h2>Raih hidup sehat dengan pilihan
                            makanan terbaik</h2>
                        <p>Gunakan panduan dari BloodWellness untuk
                            mendapatkan energi yang cukup dan tubuh yang
                            lebih sehat setiap hari.</p>
                    </div>
                </div>
                <div class="nav-lines">
                    <div class="line active" onclick="moveSlide(0)"></div>
                    <div class="line" onclick="moveSlide(1)"></div>
                    <div class="line" onclick="moveSlide(2)"></div>
                </div>
            </div>

            <section id="form-register" class="register-form">

                <p class="heading">AYO MULAI SEKARANG!</p>
                <h3>Buat Akun</h3>


                <form method="POST" action="prosesRegister.php">
                    <label for="name">Nama Anda</label>
                    <input type="text" id="name" name="name" required value="<?= isset($_SESSION['old_name']) ? htmlspecialchars($_SESSION['old_name']) : '' ?>">

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= isset($_SESSION['old_email']) ? htmlspecialchars($_SESSION['old_email']) : '' ?>">

                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password"
                        required>

                    <label for="confirm-password">Konfirmasi Kata
                        Sandi</label>
                    <input type="password" id="confirm-password"
                        name="confirm-password" required>
                    <div class="pass">
                        <p>* Password minimal 6 karakter.</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="error-message">
                            <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" href="pageLogin.php">DAFTAR</button>

                    <div class="auth-options">
                        <div class="google-auth">
                            <p>Atau</p>
                            <button type="button">
                                <svg class="google-icon"
                                    xmlns="http://www.w3.org/2000/svg"
                                    x="0px" y="0px" width="48" height="48"
                                    viewBox="0 0 48 48">
                                    <path fill="#fbc02d"
                                        d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                    <path
                                        fill="#e53935"
                                        d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                                    <path
                                        fill="#4caf50"
                                        d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                                    <path
                                        fill="#1565c0"
                                        d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                </svg>
                                Daftar dengan akun Google
                            </button>
                        </div>

                        <p>Sudah punya akun? <a href="pageLogin.php">MASUK DI
                                SINI</a></p>
                    </div>
            </section>

        </div>
    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>
    <?php
    // Hapus session old_name dan old_email setelah form selesai diproses
    unset($_SESSION['old_name'], $_SESSION['old_email']);
    ?>
</body>

</html>