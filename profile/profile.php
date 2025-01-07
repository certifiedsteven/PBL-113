<?php
include "../connect.php";
session_start();

// Pastikan session ID ada
if (!isset($_SESSION['nim'])) {
    die("Error: Session ID tidak ditemukan. Silakan login ulang.");
}

$nim = $_SESSION['nim'];

// Query untuk mengambil data user beserta jurusan dan prodi
$sql = "
    SELECT p.nama, p.nim, j.nama_jurusan, pr.prodi, p.angkatan
    FROM pemilih p
    INNER JOIN prodi pr ON p.prodi = pr.id_prodi
    INNER JOIN jurusan j ON pr.id_jurusan = j.id_jurusan
    WHERE p.nim = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $nim);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Profil tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// URL gambar acak
$randomImageUrl = "https://picsum.photos/200";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="profile.css">
    <style>
        .margin-profile{
            margin-top: 2.5px ;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm custom-navbar sticky-top">
    <a class="navbar-brand" href="../dashboard.php">
        <img src="../img/logo.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse w-100" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="../dashboard.php">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="../daftar_event.php">Pemilihan</a></li>
            <li class="nav-item"><a class="nav-link" href="../event/hasil.php">Hasil</a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link nav-profile margin-profile" href="profile/profile.php">
                    Profil
                    <img src="https://img.icons8.com/material-outlined/24/000000/user--v1.png" alt="User Icon">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?logout=true">
                    Keluar
                    <img src="../img/logout.png" alt="Keluar Icon">
                </a>
            </li>
        </ul>
    </div>
    </nav>

    <!-- Navbar dan lainnya tetap -->
    <div class="jumbotron profile-page">
        <div class="container d-flex flex-wrap justify-content-between">
            <div class="profile-left">
                <h1 class="profile-title">PROFIL</h1>
                <!-- Gambar profil random -->
                <div class="profile-photo">
                    <img src="<?php echo $randomImageUrl; ?>" alt="Profile Photo" style="border-radius: 50%; width: 150px; height: 150px;">
                </div>
                <form method="POST" action="profile.php">
                    <button type="submit" name="logout" class="logout-button">KELUAR AKUN</button>
                </form>
            </div>

            <div class="profile-middle">
                <?php if ($user): ?>
                    <h2 class="profile-name"><?php echo htmlspecialchars($user['nama']); ?></h2>
                    <p class="profile-info">
                        <?php 
                        echo "Jurusan: " . htmlspecialchars($user['nama_jurusan']) . "<br>" .
                             "Prodi: " . htmlspecialchars($user['prodi']) . "<br>" . 
                             "Angkatan: " . htmlspecialchars($user['angkatan']); 
                        ?>
                    </p>
                <?php else: ?>
                    <p class="text-danger">Data profil tidak tersedia. Silakan periksa kembali.</p>
                <?php endif; ?>
            </div>

            <div class="profile-right">
                <h1 class="profile-settings">PENGATURAN</h1>
                <ul class="settings-list">
                    <li class="settings-option" onclick="window.location.href='ganti.php';">Ganti kata sandi</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer p-4" style="background-color: #000; color: white;">
    <div class="container d-flex justify-content-between">
        <!-- Bagian Kiri Footer -->
        <div class="footer-left">
            <div class="footer-brand mb-3">
                <img src="../img/logo.png" alt="Logo" style="width: 30px; height: 30px;">
            </div>
            <ul class="list-unstyled">
                <li><a href="#" class="text-white">Tentang</a></li>
                <li><a href="#" class="text-white">Kontak</a></li>
                <li><a href="#" class="text-white">FAQ</a></li>
                <li><a href="#" class="text-white">Alamat</a></li>
            </ul>
            <p class="mt-3">© 2024 Olympvote</p>
        </div>

        <!-- Bagian Kanan Footer -->
        <div class="footer-right text-right">
            <p>IKUTIN KAMI</p>
            <div class="d-flex justify-content-end">
                <button class="button-footer footer-brand">
                    <img src="../img/Facebook.png" alt="facebook">
                </button>
                <button class="button-footer footer-brand">
                    <img src="../img/Instagram.png" alt="Instagram">
                </button>
                <button class="button-footer footer-brand">
                    <img src="../img/Twitter.png" alt="Twitter">
                </button>
                <button class="button-footer"></button>
            </div>
        </div>
    </div>
</footer>
</body>
</html>