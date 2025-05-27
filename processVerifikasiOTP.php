<?php
session_start();
require 'db.php';

$inputOtp = implode('', $_POST['otp'] ?? []);
$email = $_SESSION['otp_email'] ?? '';

if (!$email || !$inputOtp) {
    $_SESSION['error'] = "OTP tidak valid.";
    header("Location: verifikasiOTP.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error'] = "User tidak ditemukan.";
    header("Location: verifikasiOTP.php");
    exit;
}

if ($user['otp'] != $inputOtp || time() > $user['expires_at']) {
    $_SESSION['error'] = "OTP salah atau sudah kedaluwarsa.";
    header("Location: verifikasiOTP.php");
    exit;
}

$_SESSION['reset_email'] = $email;
header("Location: resetPassword.php");
exit;
