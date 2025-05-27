<?php
session_start();

if (!isset($_SESSION['otp_email'])) {
    header("Location: lupaPassword.php");
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - BloodWellness</title>
    <link rel="stylesheet" href="css/styleGeneral.css">
    <style>
        .otp-input {
            width: 40px;
            font-size: 20px;
            text-align: center;
            margin: 0 5px;
        }
    </style>
</head>

<body class="bodyLogin">
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="css/aset/logo.png" alt="Logo" class="logo">
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="pageLogin.php" class="active">Beranda</a></li>
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
        <!-- Navbar Mobile -->
        <div class="mobile-navbar">
            <ul>
                <li><a href="pageLogin.php" id="home" class="active"><i class="fas fa-home"></i></a></li>
                <li><a href="kalkulator.php#kalkulator" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
                <li><a href="pagePlanner.php#makanan" id="makanan"><i class="fas fa-utensils"></i></a></li>
                <li><a href="pageProfil.php#profil" id="profil"><i class="fas fa-user"></i></a></li>
            </ul>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="register-form">
                <h3>Verifikasi Kode OTP</h3>
                <p>Masukkan 6 digit kode yang dikirim ke email Anda</p>

                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="processVerifikasiOTP.php" method="POST" id="otpForm">
                    <div style="display: flex; justify-content: center;">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <input type="text" name="otp[]" maxlength="1" class="otp-input" required pattern="[0-9]">
                        <?php endfor; ?>
                    </div>
                    <br>
                    <button type="submit">VERIFIKASI</button>
                </form>
            </section>
        </div>
    </main>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        inputs.forEach((input, i) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && i < inputs.length - 1) {
                    inputs[i + 1].focus();
                }
            });
        });
    </script>
</body>

</html>