<?php
session_start();


include_once __DIR__ . '/db.php';


if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Mohon maaf, Anda harus masuk atau daftar terlebih dahulu untuk mengakses fitur ini.";
    header("Location: pageLogin.php");
    exit;
}

try {

    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM usersaccount WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        session_destroy();
        header("Location: pageLogin.php");
        exit;
    }


    $stmtEmail = $pdo->prepare("SELECT * FROM users_email WHERE id_user = :user_id");
    $stmtEmail->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtEmail->execute();
    $emailsTambahan = $stmtEmail->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="css/styleGeneral.css">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="css/main.css">

    <style>
        body {
            background-color: #6E8E59;
        }


        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            border-radius: 8px;
            position: relative;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }



        .btn-submit {
            margin-top: 15px;
            background-color: #6E8E59;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }


        .edit-photo-button {
            position: absolute;
            bottom: 0px;
            right: 0px;
            z-index: 999;
            background-color: rgba(0, 0, 0, 0.0);
            border: none;
            border-radius: 50%;
            color: white;
            padding: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .avatar {
            position: relative;
        }
    </style>




</head>

<body class="">
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="css/aset/logo.png" alt="Logo" class="logo">
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="pageLogin.php">Beranda</a></li>
                    <li><a href="kalkulator.php">Kalkulator Kalori</a></li>
                    <li><a href="pagePlanner.php">Perencana Makanan</a></li>
                    <li><a href="pageProfil.php" class="active">Profil</a></li>
                </ul>
            </nav>
        </div>

        <div class="auth-buttonsKal">
            <a href="prosesLogout.php" onclick="confirmLogout()">Keluar</a>
        </div>
        <!-- Navbar Mobile -->
        <div class="mobile-navbar">
            <ul>
                <li><a href="pageLogin.php" id="home"><i class="fas fa-home"></i></a></li>
                <li><a href="kalkulator.php#kalkulator" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
                <li><a href="pagePlanner.php#makanan" id="makanan"><i class="fas fa-utensils"></i></a></li>
                <li><a href="pageProfil.php#profil" id="profil" class="active"><i class="fas fa-user"></i></a></li>
            </ul>
        </div>
    </header>

    <main>

        <div class="profile-container" style="background-color: #CAE0BC;">
            <form action="update_profile.php" method="post" id="profileForm">

                <div class="banner">
                    <img src="css/aset/background.webp" alt="Fresh vegetables and fruits">
                </div>
                <div class="profile-header">
                    <div class="avatar">
                        <img id="profileImage"
                            src="<?= htmlspecialchars($user['photo_url'] ?? 'https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg'); ?>"
                            alt="Profile picture">
                        <button class="edit-photo-button" id="openPhotoModal" type="button" title="Ubah Foto" style="z-index: 9999;">
                            <img src="/aset/Icon.png" alt="" srcset="">
                        </button>
                    </div>


                    <div class="profile-info">
                        <h1><?= $user['name']; ?></h1>
                        <p class="email"><?= $user['email']; ?></p>
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn-edit">Simpan</button>
                    </div>
                </div>

                <!-- Ubah ini -->
                <div class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullName">Nama Lengkap</label>
                            <input type="text" id="fullName" name="name"
                                value="<?= htmlspecialchars($user['name']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="nickname">Nama Panggilan</label>
                            <input type="text" id="nama_panggilan" name="nickname"
                                value="<?= htmlspecialchars(explode(' ', $user['name'])[0]); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender">Jenis kelamin</label>
                            <div class="select-wrapper">
                                <select id="gender" name="jenis_kelamin">
                                    <!-- <option value="">none</option> -->
                                    <option value="Laki-laki" <?= $user['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= $user['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                    <option value="Lainnya" <?= $user['jenis_kelamin'] === 'Lainnya' ? 'selected' : '' ?>>
                                        Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country">Negara</label>
                            <div class="select-wrapper">
                                <select id="country" name="negara">
                                    <!-- <option value="">none</option> -->
                                    <option value="Indonesia" <?= $user['negara'] === 'Indonesia' ? 'selected' : '' ?>>
                                        Indonesia</option>
                                    <option value="Malaysia" <?= $user['negara'] === 'Malaysia' ? 'selected' : '' ?>>
                                        Malaysia</option>
                                    <option value="Singapura" <?= $user['negara'] === 'Singapura' ? 'selected' : '' ?>>
                                        Singapura</option>
                                    <option value="Lainnya" <?= $user['negara'] === 'Lainnya' ? 'selected' : '' ?>>Lainnya
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="language">Bahasa</label>
                            <input type="text" id="bahasa" name="bahasa"
                                value="<?= htmlspecialchars($user['bahasa'] ?? '-'); ?>">
                        </div>
                    </div>

                    <!-- Tombol simpan dan batal, hidden default -->
                    <div class="form-actions" style="margin-top: 15px;">
                        <button type="submit" class="btn-submit" style="display: none;">Simpan</button>
                        <button type="button" class="btn-cancel"
                            style="display: none; margin-left: 10px;">Batal</button>
                    </div>
                    <div class="email-section">
                        <h2>Akun email</h2>
                        <div class="email-list">
                            <?php foreach ($emailsTambahan as $emailItem): ?>
                                <div class="email-item">
                                    <div class="email-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                            <path d="M22 7l-10 7L2 7"></path>
                                        </svg>
                                    </div>
                                    <span class="email-address"><?= htmlspecialchars($emailItem['email']) ?></span>
                                </div>
                            <?php endforeach; ?>

                            <button class="btn-add-email" type="button">+ tambah akun email</button>
                        </div>

                    </div>
            </form>

        </div>

        </div>

        <div id="modalTambahEmail" class="modal hidden">
            <div class="modal-content">
                <span class="close-button" id="closeModal">&times;</span>
                <h2>Tambah Email</h2>
                <form id="formTambahEmail">
                    <label for="emailBaru">Email Baru</label>
                    <input type="email" id="emailBaru" name="emailBaru" required>
                    <button type="submit" class="btn-submit">Simpan</button>
                </form>
            </div>
        </div>



        <div id="modalUploadFoto" class="modal hidden">
            <div class="modal-content">
                <span class="close-button" id="closeUploadModal">&times;</span>
                <h2>Ubah Foto Profil</h2>
                <form id="formUploadFoto" enctype="multipart/form-data">
                    <input type="file" name="photo" id="photoInput" accept="image/*" required>
                    <button type="submit" class="btn-submit">Unggah</button>
                </form>
                <p id="uploadMessage" style="color:red; margin-top: 10px;"></p>
            </div>
        </div>



    </main>


    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalTambahEmail');
            const openBtn = document.querySelector('.btn-add-email');
            const closeBtn = document.getElementById('closeModal');
            const message = document.getElementById('message');

            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                message.textContent = '';
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            document.getElementById('formTambahEmail').addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('emailBaru').value;

                fetch('tambah_email.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            emailBaru: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Email berhasil ditambahkan!");
                            modal.classList.add('hidden');
                            window.location.reload();
                            this.reset();
                        } else {
                            message.textContent = data.message || "Gagal menyimpan email.";
                        }
                    })
                    .catch(error => {
                        message.textContent = "Terjadi kesalahan server.";
                        console.error(error);
                    });
            });
        });


        const photoModal = document.getElementById('modalUploadFoto');
        const openPhotoBtn = document.getElementById('openPhotoModal');
        const closeUploadModal = document.getElementById('closeUploadModal');
        const uploadForm = document.getElementById('formUploadFoto');
        const uploadMessage = document.getElementById('uploadMessage');

        openPhotoBtn.addEventListener('click', () => {
            photoModal.classList.remove('hidden');
            uploadMessage.textContent = '';
        });

        closeUploadModal.addEventListener('click', () => {
            photoModal.classList.add('hidden');
        });

        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(uploadForm);

            fetch('upload_foto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Foto berhasil diperbarui!");
                        photoModal.classList.add('hidden');
                        document.getElementById('profileImage').src = data.new_url + '?t=' + new Date().getTime(); // cache bust
                    } else {
                        uploadMessage.textContent = data.message || "Upload gagal.";
                    }
                })
                .catch(error => {
                    uploadMessage.textContent = "Kesalahan server.";
                    console.error(error);
                });
        });
    </script>



</body>

</html>