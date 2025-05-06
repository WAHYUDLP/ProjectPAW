<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil dan bersihkan input dari form
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm-password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $_SESSION['error'] = "Semua field wajib diisi.";
        $_SESSION['old_name'] = $name;
        $_SESSION['old_email'] = $email;
        header("Location: pageRegister.php#form-register");
        exit;
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['old_name'] = $name;
        $_SESSION['old_email'] = $email;
        $_SESSION['error'] = "Format email tidak valid.";
        header("Location: pageRegister.php#form-register");
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['old_name'] = $name;
        $_SESSION['old_email'] = $email;
        $_SESSION['error'] = "Password minimal 6 karakter.";
        header("Location: pageRegister.php#form-register");
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['old_name'] = $name;
        $_SESSION['old_email'] = $email;
        $_SESSION['error'] = "Password dan konfirmasi tidak sama.";
        header("Location: pageRegister.php#form-register");
        exit;
    }


    try {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT id FROM usersaccount WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email sudah terdaftar.";
            header("Location: pageRegister.php#form-register");
            exit;
        }

        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Simpan data pengguna baru
        $stmt = $pdo->prepare("INSERT INTO usersaccount (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);

        // Simpan data pengguna ke session
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['success'] = "Registrasi berhasil!";

        // Arahkan ke halaman login
        header("Location: pageLogin.php");
        exit;
    } catch (PDOException $e) {
        // Penanganan error database
        $_SESSION['old_name'] = $name;
        $_SESSION['old_email'] = $email;

        $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data. Coba lagi.";
        header("Location: pageRegister.php#form-register");
        exit;
    }
}
