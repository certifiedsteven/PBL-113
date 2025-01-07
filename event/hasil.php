<?php
include '../connect.php';
session_start();

$prodi_mahasiswa = $_SESSION['prodi'];

// Query untuk mendapatkan total suara hanya untuk event yang sudah direkapitulasi
$query = "
    SELECT pemilihan.id_pemilihan, pemilihan.judul, SUM(hasil.jumlah_suara) AS total_suara
    FROM pemilihan
    INNER JOIN hasil ON pemilihan.id_pemilihan = hasil.id_pemilihan
    GROUP BY pemilihan.id_pemilihan, pemilihan.judul
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="../navbar.css">
</head>
<body>
    <nav class="navbar navbar-expand-sm custom-navbar sticky-top">
    <a class="navbar-brand" href="#">
        <img src="../img/logo.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse w-100" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="../dashboard.php">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="../dashboard.php#carousel">Pemilihan</a></li>
            <li class="nav-item"><a class="nav-link" href="hasil.php">Hasil</a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link nav-profile" href="profile/profile.php">
                    Profil
                    <img src="https://img.icons8.com/material-outlined/24/000000/user--v1.png" alt="User Icon">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../dashboard.php?logout=true">
                    Keluar
                    <img src="../img/logout.png" alt="Keluar Icon">
                </a>
            </li>
        </ul>
    </div>
    </nav>

    <!-- Carousel -->
    <section class="carousel" id="carousel"> 
        <h2 class="carousel-category" style="color: #000;">HASIL</h2>
        <button class="pre-btn"><img src="../img/arrow.png" alt=""></button>
        <button class="nxt-btn"><img src="../img/arrow.png" alt=""></button>
        <div class="carousel-container">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Menampilkan data carousel dengan total suara yang benar
                    echo '<div class="carousel-card">';
                    echo '    <a href="rekapitulasi.php?id=' . $row['id_pemilihan'] . '">';
                    echo '        <div class="carousel-image">';
                    echo '            <img class="event-image-sizing" src="../img/event-placeholder.jpg" alt="Event Image">';
                    echo '        </div>';
                    echo '        <div class="carousel-caption">';
                    echo '            <h3 class="carousel-caption-title">' . htmlspecialchars($row['judul']) . '</h3>';
                    echo '            <p>Suara Masuk: ' . $row['total_suara'] . '</p>';
                    echo '        </div>';
                    echo '    </a>';
                    echo '</div>';
                }
            } else {
                echo '<p>Tidak ada event yang sudah direkapitulasi.</p>';
            }
            ?>
        </div>
    </section>

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
    
    <!-- Scripts -->
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
