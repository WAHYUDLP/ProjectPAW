<?php
session_start();
include_once __DIR__ . '/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_FILES['photo'])) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diunggah.']);
    exit;
}

$photo = $_FILES['photo'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($photo['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Hanya file JPG dan PNG yang diperbolehkan.']);
    exit;
}

$targetDir = 'uploads/';
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
$filename = 'profile_' . $user_id . '_' . time() . '.' . $ext;
$targetPath = $targetDir . $filename;

if (move_uploaded_file($photo['tmp_name'], $targetPath)) {
    $stmt = $pdo->prepare("UPDATE usersaccount SET photo_url = :photo WHERE id = :id");
    $stmt->execute(['photo' => $targetPath, 'id' => $user_id]);
    echo json_encode(['success' => true, 'new_url' => $targetPath]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file.']);
}
?>