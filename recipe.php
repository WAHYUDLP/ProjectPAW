<?php
/* ==========================================
   recipe.php  –  halaman detail menu
   ========================================== */
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: pageLogin.php'); exit; }

/* ------ validasi id ------- */
$id = $_GET['id'] ?? '';
if (!ctype_digit($id)) { header('Location: pageMenu.php'); exit; }

/* ------ ambil data menu ----- */
require 'db.php';                                  // $pdo
$stmt = $pdo->prepare('SELECT * FROM menu_items WHERE id = ?');
$stmt->execute([$id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$menu) { header('Location: pageMenu.php'); exit; }

/* ------ nilai default makro bila kolom kosong ----- */
$prot = (int)($menu['prot_pct'] ?? 0);
$carb = (int)($menu['carb_pct']  ?? 0);
$fat  = (int)($menu['fat_pct']   ?? 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($menu['name']) ?> | BloodWellness</title>
  <link rel="stylesheet" href="css/styleGeneral.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
    <style>
        /* ===== hanya styling resep ===== */
        .recipe-box {
            background: #CAE0BC;
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 1rem;
        }

        /* Bungkus gambar dan macro-bar jadi flex container */
        .img-macro-wrap {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            width: 100%;
            margin-bottom: 1rem;
            position: relative;
            /* boleh */
            /* hilangkan float dan width inline */
        }

        .img-macro-wrap img {
            width: 45%;
            border-radius: 0.5rem;
            object-fit: cover;
            aspect-ratio: 4 / 3;
            /* hapus float dan margin jika ada */
            float: none;
            margin: 0;
            transition: width 0.3s ease;
        }

        .macro-bar {
            position: relative;
            width: 45%;
            font-size: 0.75rem;
            color: #fff;
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
            transition: width 0.3s ease;
        }

        .macro-row {
            padding: 2px 6px;
            margin-bottom: 2px;
            border-radius: 4px;
        }

        .prot {
            background: #0062ff;
        }

        .carb {
            background: #ffb000;
        }

        .fat {
            background: #c000ff;
        }

        /* dua kolom bahan & langkah */
        .two-col {
            clear: both;
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }

        .two-col ul,
        .two-col ol {
            margin: 0 0 0 1.2rem;
            font-size: 0.9rem;
        }

        /* --- PENTING: paksa judul turun di bawah gambar --- */
        .recipe-box h2 {
            clear: left;
            font-size: 1.75rem;
            margin: 1.5rem 0;
        }

        /* Responsive: di layar kecil, gambar & macro-bar jadi vertikal */
        @media (max-width: 600px),
        (max-width: 768px) {
            .img-macro-wrap {
                flex-direction: column;
            }

            .img-macro-wrap img,
            .macro-bar {
                width: 100%;
            }

            .macro-bar {
                margin-top: 1rem;
            }
        }
    </style>
</head>

<body class="halaman-menu">
  <header>
    <div class="nav-container">
      <div class="logo-container">
        <img src="css/aset/logo.png" alt="Logo" class="logo">
        <span class="brand-text">BloodWellness</span>
      </div>
      <nav>
        <ul>
          <li><a href="beranda.php">Beranda</a></li>
          <li><a href="kalkulator.php">Kalkulator Kalori</a></li>
          <li><a href="pagePlanner.php">Perencana Makanan</a></li>
          <li><a href="pageProfil.php">Profil</a></li>
        </ul>
      </nav>
    </div>
    <div class="auth-buttonsKal">
      <a href="prosesLogout.php" onclick="return confirm('Keluar dari aplikasi?')">Keluar</a>
    </div>
  </header>

  <main>
    <section class="planner-intro">
      <h1>Perencana Makanan Harian</h1>
      <p class="subjudul">
        Ingin menjaga pola makan yang sehat dan seimbang? BloodWellness membantu Anda.
      </p>
    </section>

    <div class="recipe-box">
      <!-- bungkus gambar + grafik -->
      <div class="img-macro-wrap" >
        <img
          src="images/<?= htmlspecialchars($menu['image']) ?>"
          alt="<?= htmlspecialchars($menu['name']) ?>"
        >
        <div class="macro-bar">
          <div class="macro-row prot" style="width:<?= $prot ?>%">Protein <?= $prot ?>%</div>
          <div class="macro-row carb" style="width:<?= $carb ?>%">Karbohidrat <?= $carb ?>%</div>
          <div class="macro-row fat"  style="width:<?= $fat  ?>%">Lemak <?= $fat  ?>%</div>
        </div>
      </div>

      <!-- Judul resep akan clear float dan turun di bawah gambar -->
      <h2><?= htmlspecialchars($menu['name']) ?></h2>

      <!-- bahan & langkah dalam dua kolom -->
      <div class="two-col">
        <div>
          <h3>Bahan:</h3>
          <ul>
            <?php foreach (explode("\n", $menu['ingredients']) as $b): ?>
              <?php if (trim($b) !== ''): ?>
                <li><?= htmlspecialchars($b) ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
        <div>
          <h3>Cara Membuat:</h3>
          <ul>
            <?php foreach (explode("\n", $menu['steps']) as $s): ?>
              <?php if (trim($s) !== ''): ?>
                <li><?= htmlspecialchars($s) ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </main>
  <div class="mobile-navbar">
    <ul>
        <li>
            <a href="beranda.php" id="home" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'beranda.php') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
            </a>
        </li>
        <li>
            <a href="kalkulator.php#kalkulator" id="kalkulator" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'kalkulator.php') ? 'active' : ''; ?>">
                <i class="fas fa-calculator"></i>
            </a>
        </li>
        <li>
            <a href="pagePlanner.php#makanan" id="makanan" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'pagePlanner.php') ? 'active' : ''; ?>">
                <i class="fas fa-utensils"></i>
            </a>
        </li>
        <li>
            <a href="pageProfil.php#profil" id="profil" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'pageProfil.php') ? 'active' : ''; ?>">
                <i class="fas fa-user"></i>
            </a>
        </li>
    </ul>
</div>

</body>
</html>
