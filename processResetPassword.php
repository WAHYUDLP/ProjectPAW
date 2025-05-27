<?php
session_start();
require 'db.php';

if (!isset($_SESSION['reset_email'])) {
    $_SESSION['error'] = "Sesi reset tidak valid.";
    header("Location: lupaPassword.php");
    exit;
}

$email = $_SESSION['reset_email'];
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (strlen($password) < 6) {
    $_SESSION['error'] = "Password minimal 6 karakter.";
    header("Location: resetPassword.php");
    exit;
}

if ($password !== $confirm) {
    $_SESSION['error'] = "Konfirmasi password tidak cocok.";
    header("Location: resetPassword.php");
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE usersaccount SET password = ? WHERE email = ?");
$stmt->execute([$hashed, $email]);

$stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
$stmt->execute([$email]);

unset($_SESSION['reset_email']);
$_SESSION['success'] = "Password berhasil diubah. Silakan login.";

header("Location: pageLogin.php");
exit;
