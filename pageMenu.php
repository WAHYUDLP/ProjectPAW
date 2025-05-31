<?php
/* -------------------------------------------------
   PAGE MENU – BloodWellness
-------------------------------------------------- */
session_start();

/* (1) Wajib login */
if (!isset($_SESSION['user_id'])) {
  header('Location: pageLogin.php');
  exit;
}

/* (2) Data planner harus ada */
if (!isset($_SESSION['blood_group'], $_SESSION['calories'])) {
  header('Location: pagePlanner.php');
  exit;
}

/* (3) Ambil data dari session */
$group    = $_SESSION['blood_group'];          // A | B | AB | O
$calories = (int) $_SESSION['calories'];

/* (4) Hitung gram makro untuk teks info */
$macrosMap = [
  'O'  => ['carb' => 25,  'fat' => 25,  'prot' => 50],
  'A'  => ['carb' => 50,  'fat' => 25,  'prot' => 25],
  'B'  => ['carb' => 37.5, 'fat' => 25,  'prot' => 37.5],
  'AB' => ['carb' => 40,  'fat' => 25,  'prot' => 35],
];
function grams($cal, $pct, $calPerGram)
{
  return round(($cal * $pct / 100) / $calPerGram);
}
$grams = [
  'carb'  => grams($calories, $macrosMap[$group]['carb'], 4),
  'fat'   => grams($calories, $macrosMap[$group]['fat'], 9),
  'prot'  => grams($calories, $macrosMap[$group]['prot'], 4),
];

/* (5) Fase makan */
$phases = ['Sarapan', 'Makan Siang', 'Camilan', 'Makan Malam'];

require_once 'db.php';      // $pdo = new PDO(...);

/* (6) Cek refresh phase dari query string */
$refreshPhase = isset($_GET['refresh']) ? $_GET['refresh'] : null;

/* (7) Fungsi ambil 3 menu acak per fase */
function fetchMenusForPhase(PDO $pdo, $group, $phase) {
  $stm = $pdo->prepare(
    "SELECT id,name,image,calories,ingredients
     FROM menu_items
     WHERE blood_group = :bg AND phase = :ph
     ORDER BY RAND()
     LIMIT 3"
  );
  $stm->execute([':bg' => $group, ':ph' => $phase]);
  return $stm->fetchAll(PDO::FETCH_ASSOC);
}

/* (8) Inisialisasi session storage untuk menu per fase */
if (!isset($_SESSION['menus'])) {
  $_SESSION['menus'] = [];
}

/* (9) Jika ada refresh phase, update hanya fase itu */
if ($refreshPhase !== null && in_array($refreshPhase, $phases)) {
  $_SESSION['menus'][$refreshPhase] = fetchMenusForPhase($pdo, $group, $refreshPhase);
}

/* (10) Jika session menus untuk fase tertentu belum ada, ambil dari DB */
foreach ($phases as $phase) {
  if (!isset($_SESSION['menus'][$phase])) {
    $_SESSION['menus'][$phase] = fetchMenusForPhase($pdo, $group, $phase);
  }
}

/* (11) Sekarang $_SESSION['menus'] sudah berisi data 3 menu per fase */
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Rencana Makan Hari Ini</title>
  <link rel="stylesheet" href="css/styleGeneral.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
    rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <script src="script.js" defer></script>
</head>

<body class="halaman-menu">

  <header>
    <div class="nav-container">
      <div class="logo-container">
        <img src="css/aset/logo.png" alt="Logo" class="logo" />
        <span class="brand-text">BloodWellness</span>
      </div>
      <nav>
        <ul>
          <li><a href="beranda.php">Beranda</a></li>
          <li><a href="kalkulator.php">Kalkulator Kalori</a></li>
          <li><a href="pagePlanner.php" class="active">Perencana Makanan</a></li>
          <li><a href="pageProfil.php">Profil</a></li>
        </ul>
      </nav>
    </div>
    <div class="auth-buttonsKal">
      <a href="prosesLogout.php" onclick="confirmLogout()">Keluar</a>
    </div>
  </header>

  <main>
    <!-- Banner -->
    <section class="planner-intro">
      <h1>Perencana Makanan Harian</h1>
      <p class="subjudul">
        Ingin menjaga pola makan yang sehat dan seimbang? BloodWellness membantu Anda
        menyusun menu makanan sesuai kebutuhan kalori dan golongan darah Anda.
      </p>
    </section>

    <!-- Kotak info -->
    <div class="planner-container">
      <h2>Pilih golongan darah Anda</h2>

      <div class="blood-group-wrapper">
        <?php foreach (['A', 'B', 'AB', 'O'] as $g): ?>
          <div class="blood-group <?= $g === $group ? 'active' : '' ?>"><?= htmlspecialchars($g) ?></div>
        <?php endforeach; ?>
      </div>

      <div class="form-row">
        <div class="calories-wrapper">
          <label>Jumlah kalori harian:</label>
          <input type="number" value="<?= htmlspecialchars($calories) ?>" disabled />
        </div>
        <ul class="macros">
          <li>Minimal <?= htmlspecialchars($grams['carb']) ?> gr Karbohidrat</li>
          <li>Minimal <?= htmlspecialchars($grams['fat']) ?> gr Lemak</li>
          <li>Minimal <?= htmlspecialchars($grams['prot']) ?> gr Protein</li>
        </ul>
      </div>

      <a href="pagePlanner.php" class="btn-reset">ATUR ULANG</a>
    </div>

    <!-- Rencana makan -->
    <div class="meal-plan" id="menuMam">
      <div class="planner-intro">
        <h1>Rencana Makan Hari Ini</h1>
        <p style="color: white; font-weight: bold;">🕒 <?= htmlspecialchars($calories) ?> kalori / hari</p>
      </div>

      <?php foreach ($phases as $phase): ?>
        <section class="meal-phase" id="<?= htmlspecialchars($phase) ?>">
          <div class="meal-phase-header">
            <h3><?= htmlspecialchars($phase) ?></h3>
            <a class="generate-icon" href="?refresh=<?= urlencode($phase) ?>#<?= urlencode($phase) ?>" title="Regenerate">&#x21bb;</a>
          </div>

          <div class="meal-cards">
            <?php foreach ($_SESSION['menus'][$phase] as $m): ?>
              <!-- ===== kartu jadi link ke recipe.php ===== -->
              <a href="recipe.php?id=<?= (int)$m['id'] ?>" class="meal-card-link">
                <div class="meal-card">
                  <img src="images/<?= htmlspecialchars($m['image']) ?>"
                    alt="<?= htmlspecialchars($m['name']) ?>" />
                  <h4><?= htmlspecialchars($m['name']) ?></h4>
                  <p class="cal"><?= (int)$m['calories'] ?> kalori</p>
                  <ul>
                    <?php foreach (explode("\n", $m['ingredients']) as $ing): ?>
                      <?php $ing = trim($ing);
                      if ($ing === '') continue; ?>
                      <li><?= htmlspecialchars($ing) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endforeach; ?>
    </div>

  </main>

  <footer>&copy; 2025 BloodWellness</footer>
  <div class="mobile-navbar">
    <ul>
      <li>
        <a href="beranda.php" id="home" class="<?= (basename($_SERVER['PHP_SELF']) === 'beranda.php') ? 'active' : '' ?>">
          <i class="fas fa-home"></i>
        </a>
      </li>
      <li>
        <a href="kalkulator.php#kalkulator" id="kalkulator" class="<?= (basename($_SERVER['PHP_SELF']) === 'kalkulator.php') ? 'active' : '' ?>">
          <i class="fas fa-calculator"></i>
        </a>
      </li>
      <li>
        <a href="pagePlanner.php#makanan" id="makanan" class="<?= (basename($_SERVER['PHP_SELF']) === 'pagePlanner.php') ? 'active' : '' ?>">
          <i class="fas fa-utensils"></i>
        </a>
      </li>
      <li>
        <a href="pageProfil.php#profil" id="profil" class="<?= (basename($_SERVER['PHP_SELF']) === 'pageProfil.php') ? 'active' : '' ?>">
          <i class="fas fa-user"></i>
        </a>
      </li>
    </ul>
  </div>

</body>

</html>
