<?php
include 'connect.php';
session_start();

$prodi_mahasiswa = $_SESSION['prodi'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/icon type">
    <style>
    body {
        font-family: 'Lexend', sans-serif;
        background-color: #000;
        overflow-x: hidden;
        margin: 0;
    }
    .margin-profile{
        margin-top: 2.5px;
    }
    .navbar {
        height: 80px;
    }

    .custom-navbar {
        background-color: #808080;
    }

    .navbar-brand img {
        width: 40px;
        height: 40px;
    }

    .navbar-toggler-icon {
        background-image: url('https://img.icons8.com/material-outlined/24/000000/menu.png');
    }

    .navbar-nav .nav-link {
        color: #000;
        text-align: center;
        font-weight: bold;
        font-size: 16px;
    }

    .navbar-nav .nav-link:hover {
        color: #555;
    }

    .jumbotron{
        background-image: url('img/BACKGROUND.png');
        background-size: cover;
        background-position: center;
        color: #000;
        text-align: left;
        padding-top: 20vh;
        padding-bottom: 79vh;
        padding-left: 5%;
        margin-bottom: 0;
    }
    .teks-instructor{
        margin-bottom: 10px;
    }
    .lebar-acara {
        display: flex;
        flex-wrap: wrap; /* Allows wrapping for smaller screens */
        /* justify-content: center; */ /* Center-align buttons */
        gap: 20px; /* Add consistent spacing between buttons */
        margin-top: 20px; /* Add spacing from text above */
        width: 100%; /* Full width for responsiveness */
    }

    .acara-button {
        font-family: 'Lexend', sans-serif;
        border: none;
        border-radius: 5px;
        background-color: #d9d9d9;
        padding: 50px 10px; /* Large padding for desktop view */
        font-weight: 600;
        transition: background-color 0.15s, color 0.15s, box-shadow 0.15s;
        width: 300px;
        text-align: center;
    }

    .acara-button:hover {
        background-color: #000000;
        color: white;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
    }

    .acara-button:active {
        opacity: 0.9;
        outline-color: white;
        outline-style: solid;
    }
    /* Footer */
    .footer {
        color: white;
        font-size: 14px;
        margin-top: 50px;
    }

    .footer-left ul li {
        margin-bottom: 5px;
    }

    .footer-left a {
        color: white;
        text-decoration: none;
    }

    .footer-left a:hover {
        color: #ddd;
    }

    .button-footer {
        border-radius: 10px;
        background-color: black;
        padding: 10px;
        border-style: solid;
        border-color: white;
        border-width: 1px;
        margin-right: 9px;
    }

    .footer-brand img {
        width: 20px;
        height: 20px;
    }
    .pengaturan-teks{
        margin-bottom: 20px;
    }
    .custom-bg{
        background-color: #808080;
        border-color: #808080;
        transition: opacity 0.15s;
    }
    .custom-bg:hover{
        background-color: #808080;
        border-color: #808080;
        opacity: 0.7;
    }
    .custom-bg:active {
        background-color: #808080 !important;
        border-color: #808080 !important;
        color: #ffffff !important;
        box-shadow: none !important; /* Override shadow */
        opacity: 1 !important; /* Override opacity */
    }
    .custom-bg:focus {
        background-color: #808080 !important;
        border-color: #808080 !important;
        color: #ffffff !important;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5) !important; /* Override shadow */
        opacity: 1 !important; /* Override opacity */
    }

    /* Responsive Styles */
    @media (max-width: 800px) {
        .navbar {
            height: auto;
        }
        .navbar-brand img {
            width: 30px;
            height: 30px;
        }
        .jumbotron {
            padding: 10px;
        }
        .acara-button {
            padding: 20px; /* Maintain consistent padding */
            font-size: 16px; /* Adjust font size for better readability */
            flex: 1 1 100%; /* Buttons take up the full width in mobile */
            max-width: 100%; /* Prevent shrinking below container width */
            text-align: center; /* Ensure text is centered */
            word-wrap: break-word; /* Handle longer text gracefully */
        }
        .lebar-acara {
            gap: 15px; /* Slightly increase the gap for better spacing */
        }
    }
</style>
</head>
<body>
    <nav class="navbar navbar-expand-sm custom-navbar sticky-top flexbox">
        <a class="navbar-brand" href="/dashboard.php">
            <img src="img/logo.png" alt="Logo">
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse w-100" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#carousel">Pemilihan</a></li>
                <li class="nav-item"><a class="nav-link" href="event/hasil.php">Hasil</a></li>
            </ul>
            
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
            <a class="nav-link nav-profile margin-profile" href="profile/profile.php">
                Profil
                <img src="https://img.icons8.com/material-outlined/24/000000/user--v1.png" alt="User Icon">
            </a>
        </li>
                <li class="nav-item">
                <a class="nav-link" action="dashboard.php" name="logout" href="dashboard.php?logout=true">
                    Logout
                <img src="img/logout.png" alt="Logout Icon">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Jumbotron/Bagian Event -->
    <div class="jumbotron">
    <div class="display-4 fw-bold no-line-spacing">
        <span class="teks-instructor d-block">PEMILIHAN</span>
    </div>
    <div class="lebar-acara">
        <?php
        $query = "SELECT * FROM pemilihan 
              WHERE prodi = '$prodi_mahasiswa' 
              ORDER BY tanggal_mulai DESC, waktu_mulai DESC";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            while ($event = mysqli_fetch_assoc($result)) {
                echo "<div class='acara-button'>
                        <div class ='pengaturan-teks'>
                            <span>{$event['judul']}</span><br>
                        </div>
                        <a href='event/vote.php?id_pemilihan={$event['id_pemilihan']}'>
                            <button class='btn btn-primary mt-2 custom-bg'>Pilih Sekarang</button>
                        </a>
                      </div>";
            }
        } else {
            echo "<p>Belum ada event yang tersedia untuk prodi Anda.</p>";
        }
        ?>
    </div>
</div>

    <!-- Footer -->
    <footer class="footer p-4" style="background-color: #000; color: white;">
        <div class="container d-flex justify-content-between">
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
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
