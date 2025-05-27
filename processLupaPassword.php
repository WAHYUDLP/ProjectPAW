<?php
session_start();
require 'db.php';
require 'vendor/autoload.php'; // PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'] ?? '';

if (!$email) {
    $_SESSION['error'] = "Email tidak boleh kosong.";
    header("Location: lupaPassword.php");
    exit;
}

// Cek apakah email terdaftar
$stmt = $pdo->prepare("SELECT * FROM usersaccount WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error'] = "Email tidak ditemukan.";
    $_SESSION['old_email'] = $email;
    header("Location: lupaPassword.php");
    exit;
}

// Generate OTP & simpan ke database
$otp = rand(100000, 999999);
$expiresAt = date('Y-m-d H:i:s', time() + 300); // 5 menit ke depan

// Hapus OTP lama (jika ada) untuk email ini
$pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

// Simpan OTP baru ke DB
$stmt = $pdo->prepare("INSERT INTO password_resets (email, otp, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$email, $otp, $expiresAt]);

// Kirim Email OTP
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pawproject71@gmail.com'; // Ganti dengan email kamu
    $mail->Password = 'idsp fqdf ddsk oull';    // Ganti dengan app password (bukan password Gmail biasa)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('pawproject71@gmail.com', 'BloodWellness');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Kode OTP Reset Password - BloodWellness';
    $mail->Body = "
        <h2>Reset Password</h2>
        <p>Kode OTP Anda adalah:</p>
        <h1 style='letter-spacing: 4px;'>$otp</h1>
        <p>Kode berlaku selama <strong>5 menit</strong>. Jangan berikan kode ini kepada siapa pun.</p>
    ";

    $mail->send();

    $_SESSION['success'] = "Kode OTP telah dikirim ke email Anda.";
    $_SESSION['otp_email'] = $email; // Simpan email ke session untuk tahap verifikasi
    header("Location: verifikasiOTP.php");
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Gagal mengirim email: {$mail->ErrorInfo}";
    header("Location: lupaPassword.php");
    exit;
}
