<?php
session_start();

// Menghapus session
session_unset();
session_destroy();

// Menghapus cookie remember me jika ada
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/'); // Menghapus cookie
}

// Redirect ke halaman login
header("Location: pageLogin.php");
exit;
?>
