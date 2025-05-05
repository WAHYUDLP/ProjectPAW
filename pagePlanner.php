<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Mohon maaf, Anda harus masuk atau daftar terlebih dahulu untuk mengakses fitur ini.";
    header("Location: pageLogin.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>