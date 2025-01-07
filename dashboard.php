<!DOCTYPE html>
<html lang="en">
<?php
include 'connect.php';
session_start();
include 'navbar.php';
$prodi_mahasiswa = $_SESSION['prodi'];

if (isset($_SESSION["prodi"]) == false) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION["is_login"]) == false) {
    header("Location: index.php");
    exit();
}

if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header('location: index.php');
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Instructor of the Month</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/icon type">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .carousel-imagee {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: bottom; /* Center vertically (if needed) */
            height: 100%; /* Ensure the container has a height */
        }
        .img-sizing{
            width: 520px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<!-- <nav class="navbar navbar-expand-sm custom-navbar sticky-top">
        <a class="navbar-brand" href="#">
            <img src="img/logo.png" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse w-100" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#carousel">Acara</a></li>
                <li class="nav-item"><a class="nav-link" href="event/hasil.php">Hasil</a></li>
                <li class="nav-item"><a class="nav-link" href="#about-heading">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="#">SSD</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link nav-profile" href="profile/profile.php">
                        Profil
                        <img src="https://img.icons8.com/material-outlined/24/000000/user--v1.png" alt="User Icon">
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" action="dashboard.php" name="logout" href="dashboard.php?logout=true">
                        Logout
                    <img src="img/logout.png" alt="Logout Icon">
                    </a>
                </li>
            </ul>
        </div>
</nav> -->

<!-- JUMBOTRON -->
<div class="jumbotron">
    <p style="font-family: 'Playfair Display'; color: #000;" class="teks-pendukung">SEDANG BERLANGSUNG</p>
    <div class="display-4 fw-bold no-line-spacing">
        <span class="teks-instructor d-block">INSTRUCTOR</span>
        <span class="teks-of d-block">OF THE</span>
        <span class="teks-month d-block">MONTH</span>
    </div>
    </p>
    <a href="daftar_event.php">
        <button class="tombol mt-3">PILIH SEKARANG</button>
    </a>
</div>



<!-- Carousel -->
<?php
$query = "SELECT * FROM pemilihan WHERE prodi = '$prodi_mahasiswa'";
$result = mysqli_query($conn, $query);
?>

<section class="carousel" id="carousel"> 
    <h2 class="carousel-category">EVENT</h2>
    <button class="pre-btn"><img src="img/arrow.png" alt=""></button>
    <button class="nxt-btn"><img src="img/arrow.png" alt=""></button>
    <div class="carousel-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($event = mysqli_fetch_assoc($result)) {
                echo "<div class='carousel-card'>
                        <a href='event/vote.php?id_pemilihan={$event['id_pemilihan']}' class='slide{$event['id_pemilihan']}'>
                            <div class='carousel-imagee'>
                                <img class='img-sizing' src='img/event-placeholder.jpg'>
                            </div>
                            <div class='carousel-caption'>
                                <h3 class='carousel-caption-title'>{$event['judul']}</h3>
                            </div>
                        </a>
                      </div>";
            }
        } else {
            echo "<p>Tidak ada event tersedia</p>";
        }
        ?>
    </div>
</section>

<!-- About section -->
<div class="about-section">
    <h2 id="about-heading" class="carousel-category">TENTANG</h2>
    <div class="about-text">E-voting berbasis web adalah solusi modern untuk menyelenggarakan pemilihan secara efisien, transparan, dan aman. Dengan memanfaatkan teknologi website, proses pemungutan suara dapat dilakukan kapan saja dan dimana saja, memberikan kemudahan bagi pemilih untuk berpartisipasi tanpa batasan lokasi. Sistem ini dirancang untuk menjaga kerahasiaan suara dan meminimalkan potensi kecurangan. <br>
    <br>
    Website ini dirancang untuk memudahkan pengguna untuk pemilihan instruktur secara modern dan praktis. Website ini dirancang oleh 6 mahasiswa yang sedang menempuh pendidikan di Politeknik Negeri Batam. Mahasiswa-mahasiswa yang berkontribusi ialah: <br>
    - Bagus Harimukti <br>
    - Hafiz Atama Romadhoni <br>
    - Lathifah Nasywa Kesumaputri <br>
    - Muhammad Addin <br>
    - Putri <br>
    - Steven Kumala <br>
    </div>
</div>

<!-- Footer -->
<footer class="footer p-4" style="background-color: #000; color: white;">
    <div class="container d-flex justify-content-between">
        <!-- Bagian Kiri Footer -->
        <div class="footer-left">
            <div class="footer-brand mb-3">
                <img src="img/logo.png" alt="Logo" style="width: 30px; height: 30px;">
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
                    <img src="img/Facebook.png" alt="facebook">
                </button>
                <button class="button-footer footer-brand">
                    <img src="img/Instagram.png" alt="Instagram">
                </button>
                <button class="button-footer footer-brand">
                    <img src="img/Twitter.png" alt="Twitter">
                </button>
                <button class="button-footer"></button>
            </div>
        </div>
    </div>
</footer>

<script src="script.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>