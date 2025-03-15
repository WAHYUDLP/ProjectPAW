<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validasi input
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Semua field harus diisi.";
        return;
    }

    if ($password !== $confirmPassword) {
        echo "Kata sandi tidak cocok.";
        return;
    }

    // Simpan ke database (kode untuk koneksi ke DB dan penyimpanan harus ditambahkan)
    // Misalnya:
    // $conn = new mysqli("hostname", "username", "password", "database");
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')");

    echo "Pendaftaran berhasil!";
}
?>