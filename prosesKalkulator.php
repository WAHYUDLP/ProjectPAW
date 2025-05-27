<?php
session_start();

$gender = $_POST['gender'];
$age = $_POST['age'];
$weight = $_POST['weight'];
$height = $_POST['height'];
$activity = $_POST['activity'];
$diet = $_POST['diet'] ?? 'pertahankan';
$body_fat_input = $_POST['body_fat'] ?? 'Tidak tahu';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kalau tombol diet dipilih ulang
    if (isset($_POST['diet']) && isset($_SESSION['gender'])) {
        $gender = $_SESSION['gender'];
        $age = $_SESSION['age'];
        $weight = $_SESSION['weight'];
        $height = $_SESSION['height'];
        $activity = $_SESSION['activity'];
        $body_fat_input = $_SESSION['body_fat'];
        $diet = $_POST['diet'];
    } else {
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        $weight = $_POST['weight'];
        $height = $_POST['height'];
        $activity = $_POST['activity'];
        $diet = $_POST['diet'] ?? 'pertahankan';
        $body_fat_input = $_POST['body_fat'] ?? 'Tidak tahu';

        $_SESSION['gender'] = $gender;
        $_SESSION['age'] = $age;
        $_SESSION['weight'] = $weight;
        $_SESSION['height'] = $height;
        $_SESSION['activity'] = $activity;
        $_SESSION['body_fat'] = $body_fat_input;
    }
}


// Mapping input lemak tubuh ke persentase numerik
$body_fat_percent = match ($body_fat_input) {
    'Kurang dari 18%' => 15,
    '18% - 25%'       => 21.5,
    'Lebih dari 25%'  => 28,
    default           => null // 'Tidak tahu'
};

// Hitung BMR
if ($body_fat_percent !== null) {
    // Menggunakan rumus Katch-McArdle
    $lbm = $weight * (1 - $body_fat_percent / 100);
    $bmr = 370 + (21.6 * $lbm);
} else {
    // Menggunakan rumus Mifflin-St Jeor
    if ($gender === 'Pria') {
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
    } else {
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
    }
}

// Faktor aktivitas
$activity_factors = [
    'Sangat Rendah' => 1.2,
    'Ringan' => 1.375,
    'Sedang' => 1.55,
    'Tinggi' => 1.725,
    'Sangat Tinggi' => 1.9
];

$tdee = $bmr * $activity_factors[$activity];

$minimum_calories = ($gender === 'Pria') ? 1500 : 1200;

$kalori = [
    'hilangkan_lemak' => max(round($tdee - 500), $minimum_calories),
    'pertahankan'     => round($tdee),
    'bangun_massa_otot' => round($tdee + 500)
];

$_SESSION['kalori'] = $kalori;
$_SESSION['diet'] = $diet;
$_SESSION['tombol_label'] = 'HITUNG';
$_SESSION['hasil_kalori'] = $kalori[$diet];


header("Location: kalkulator.php#hasil-kalori");
exit;
