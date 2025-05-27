<?php
session_start();

// Hapus semua session kalkulator
unset(
    $_SESSION['gender'], $_SESSION['age'], $_SESSION['weight'],
    $_SESSION['height'], $_SESSION['activity'], $_SESSION['body_fat'],
    $_SESSION['diet'], $_SESSION['kalori'], $_SESSION['hasil_kalori'],
    $_SESSION['tombol_label']
);

// Kembali ke kalkulator kosong
header("Location: kalkulator.php");
exit;
?>
