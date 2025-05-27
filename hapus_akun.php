<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $email_id = $_POST['user_id'];

    $stmt = $pdo->prepare("DELETE FROM usersaccount WHERE id = :id");
    $stmt->bindParam(':id', $email_id, PDO::PARAM_INT);
    $stmt->execute();

    require_once __DIR__ . '/prosesLogout.php';

    exit;
} else {
    echo "Permintaan tidak valid.";
}
