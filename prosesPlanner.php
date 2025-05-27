<?php
session_start();

// Pastikan form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pagePlanner.php');
    exit;
}

// Cek input yang wajib ada
if (!isset($_POST['blood_group'], $_POST['calories'])) {
    $_SESSION['error'] = 'Silakan pilih golongan darah dan masukkan kalori.';
    header('Location: pagePlanner.php');
    exit;
}

// Simpan pilihan user ke session
$_SESSION['blood_group'] = $_POST['blood_group'];
$_SESSION['calories']    = (int) $_POST['calories'];

// Redirect ke halaman menu
header('Location: pageMenu.php#menuMam');
exit;
