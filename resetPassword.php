<!-- // Ini ntar disambungin dari edit profil -->
<?php if (isset($_GET['account_deleted']) && $_GET['account_deleted'] == 1): ?>
    <div class="success-message">Akun Anda telah berhasil dihapus.</div>
<?php endif; ?>

<?php
session_start();
require 'db.php';

// Ambil email jika sebelumnya dikirim
$email = $_SESSION['old_email'] ?? '';
unset($_SESSION['old_email']);


// Jika ada remember_token di cookie
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $pdo->prepare("SELECT * FROM usersaccount WHERE remember_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: beranda.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styleGeneral.css">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
    <title>Atur Ulang Kata Sandi BloodWellness</title>

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

        <div class="auth-buttonsKal">
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

            <section id="form-login" class="register-form">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message"><?= htmlspecialchars($_SESSION['success']); ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>


                <?php if (isset($_SESSION['error'])): ?>
                    <!-- TRUE -->
                    <!-- Tempat pesan error akan muncul -->
                    <div id="login-error" class="error-message">
                        <?= $_SESSION['error']; ?>
                    </div>
                <?php endif ?>

                <p class="heading">AYO MULAI SEKARANG!</p>
                <h3 class = "heading3" >Lupa Kata Sandi</h3>
                <p style="text-align: start; width: 100%;">
                    Verifikasi Kata Sandi Anda berhasil, konfirmasi untuk mengatur ulang sandi anda
                </p>

                <form id="lupaPasswordForm" method="POST" action="processResetPassword.php">
                    <label for="password">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirm" name="confirm_password" required>

                    <button type="submit">KONFIRMASI</button>
                </form>
            </section>

        </div>
    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>
</body>

</html>