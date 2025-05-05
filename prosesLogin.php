<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    $stmt = $pdo->prepare("SELECT * FROM usersaccount WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = "Akun dengan email tersebut tidak ditemukan. Silakan daftar terlebih dahulu.";
        header("Location: pageLogin.php");
        exit;
    }

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
    
        if (isset($_POST['remember'])) {
            $token = bin2hex(random_bytes(16));
            setcookie("remember_token", $token, time() + (86400 * 7), "/");
    
            // Simpan token di DB
            $stmt = $pdo->prepare("UPDATE usersaccount SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $user['id']]);
        }
    
        header("Location: beranda.php");
        exit;
    
    
        } else {
            // Redirect ke pageLogin.php dengan parameter error
            header("Location: pageLogin.php?error=1");
            exit;
        }
    }
