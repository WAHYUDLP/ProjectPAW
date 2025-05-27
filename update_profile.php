<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu.";
    header("Location: pageLogin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Ambil data dari form
    $name = $_POST['name'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $negara = $_POST['negara'] ?? '';
    $bahasa = $_POST['bahasa'] ?? '';
    if (empty(trim($name))) {
        $_SESSION['error'] = "Nama tidak boleh kosong.";
        header("Location: pageProfil.php");
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE usersaccount 
        SET name = :name, jenis_kelamin = :jenis_kelamin, 
            negara = :negara, bahasa = :bahasa 
        WHERE id = :id
    ");

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
    $stmt->bindParam(':negara', $negara);
    $stmt->bindParam(':bahasa', $bahasa);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    $stmt->execute();

    $_SESSION['success'] = "Profil berhasil diperbarui.";
    header("Location: pageProfil.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    header("Location: pageProfil.php");
    exit;
}
