<?php
ob_start();
session_start();
header('Content-Type: application/json');

require 'db.php';

// Ambil input secara aman
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']) && $_POST['remember'] == '1';

// Validasi input dasar
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email dan password wajib diisi.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM usersaccount WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];

        // Handle "remember me"
        if ($remember) {
            $token = bin2hex(random_bytes(16));
            setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true); // HTTPOnly
            $pdo->prepare("UPDATE usersaccount SET remember_token = ? WHERE id = ?")->execute([$token, $user['id']]);
        }
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Email atau password salah.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server.',
        'error' => $e->getMessage() // tampilkan error asli
    ]);
}