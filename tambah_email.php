<?php
session_start();
header('Content-Type: application/json');

include_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$emailBaru = trim($data['emailBaru'] ?? '');

if (!filter_var($emailBaru, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO users_email (id_user, email) VALUES (:id_user, :email)");
    $stmt->execute([
        ':id_user' => $_SESSION['user_id'],
        ':email' => $emailBaru
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>