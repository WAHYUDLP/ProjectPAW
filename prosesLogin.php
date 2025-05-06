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


    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];

        if (isset($_POST['remember'])) {
            $token = bin2hex(random_bytes(16));
            setcookie("remember_token", $token, time() + (86400 * 7), "/");

            $stmt = $pdo->prepare("UPDATE usersaccount SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $user['id']]);
        }

        header("Location: beranda.php");
        exit;
    } else {
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['old_email'] = $email; // Simpan email
            header("Location: pageLogin.php?error=1#form-login");
            exit;
        }
        exit;
    }
}
