<?php
session_start();

// — pastikan user sudah login —
if (!isset($_SESSION['user_id'])) {
  $_SESSION['error'] = "Mohon maaf, Anda harus masuk terlebih dahulu.";
  header("Location: pageLogin.php");
  exit;
}

// siapkan variabel calories (dari session kalkulator Anda)
if (isset($_SESSION['kalori'], $_SESSION['diet'])) {
  $kaloriArray = $_SESSION['kalori'];
  $diet        = $_SESSION['diet'];
  $calories    = $kaloriArray[$diet];
} else {
  $calories = "";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perencana Makanan Harian</title>
  <link rel="stylesheet" href="css/styleGeneral.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
    rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="script.js" defer></script>
</head>

<body class="halaman-beranda halaman-planner">

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
    <div class="planner-intro">
      <h1>Perencana Makanan Harian</h1>
      <p class="subjudul">
        Ingin menjaga pola makan yang sehat dan seimbang? BloodWellness membantu Anda
        menyusun menu makanan sesuai dengan kebutuhan kalori dan golongan darah Anda.
      </p>
    </div>

    <div class="planner-container">
      <h2>Pilih golongan darah Anda</h2>

      <form action="prosesPlanner.php" method="POST" id="plannerForm">
        <div class="blood-group-wrapper">
          <?php
          $groups        = ['A', 'B', 'AB', 'O'];
          $selectedGroup = $_SESSION['blood_group'] ?? '';
          foreach ($groups as $g) {
            $active = $selectedGroup === $g ? 'active' : '';
            echo "
                <label class='blood-group {$active}'>
                  {$g}
                  <input type='radio' name='blood_group' value='{$g}' " . ($active ? 'checked' : '') . ">
                </label>";
          }
          ?>
        </div>

        <div class="form-row">
          <div class="calories-wrapper">
            <label for="calories">Jumlah kalori harian:</label>
            <input
              type="number"
              id="calories"
              name="calories"
              value="<?= htmlspecialchars($calories) ?>"
              min="0"
              required>
          </div>

          <ul class="macros" data-default-group="<?= $_SESSION['blood_group'] ?? 'O' ?>">
            <li>Minimal <span id="carb">…</span> gr Karbohidrat</li>
            <li>Minimal <span id="fat">…</span> gr Lemak</li>
            <li>Minimal <span id="protein">…</span> gr Protein</li>
          </ul>
        </div>

        <button type="submit" class="btn-buat">BUAT</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 BloodWellness</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // mapping persentase untuk tiap golongan darah
      const macrosMap = {
        'O': {
          carb: 25,
          fat: 25,
          prot: 50
        },
        'A': {
          carb: 50,
          fat: 25,
          prot: 25
        },
        'B': {
          carb: 37.5,
          fat: 25,
          prot: 37.5
        },
        'AB': {
          carb: 40,
          fat: 25,
          prot: 35
        }
      };

      // helper: hitung gram = (kalori * pct / 100) / calPerGram
      function calcGrams(cal, pct, calPerGram) {
        return Math.round((cal * pct / 100) / calPerGram);
      }

      const caloriesInput = document.getElementById('calories');
      const carbSpan = document.getElementById('carb');
      const fatSpan = document.getElementById('fat');
      const proteinSpan = document.getElementById('protein');
      const macrosList = document.querySelector('.macros');

      function getSelectedGroup() {
        const sel = document.querySelector('input[name="blood_group"]:checked');
        return sel ? sel.value : macrosList.dataset.defaultGroup;
      }

      function updateMacros() {
        const c = parseFloat(caloriesInput.value) || 0;
        const grp = getSelectedGroup();
        const {
          carb,
          fat,
          prot
        } = macrosMap[grp] || macrosMap['O'];

        carbSpan.textContent = calcGrams(c, carb, 4);
        fatSpan.textContent = calcGrams(c, fat, 9);
        proteinSpan.textContent = calcGrams(c, prot, 4);
      }

      // attach event listeners
      document.querySelectorAll('.blood-group').forEach(el => {
        el.addEventListener('click', () => {
          // toggling active radio
          document.querySelectorAll('.blood-group').forEach(g => g.classList.remove('active'));
          el.classList.add('active');
          el.querySelector('input').checked = true;
          updateMacros(); // hitung ulang saat ganti golongan
        });
      });

      caloriesInput.addEventListener('input', updateMacros);

      // initial calculation
      updateMacros();
    });

    function confirmLogout() {
      if (confirm('Apakah Anda yakin ingin keluar?')) {
        window.location.href = 'prosesLogout.php';
      }
    }
  </script>
  <!-- MOBILE NAVBAR -->
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