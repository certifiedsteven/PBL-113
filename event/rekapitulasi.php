<?php
include '../connect.php'; // Pastikan koneksi benar
session_start();

// Folder tempat menyimpan foto kandidat
$folder_kandidat = "../img/kandidat/";

// Ambil id_voting dari URL
$id_voting = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_voting) {
    // Query untuk mengambil data kandidat dan jumlah suara
    $query = "
    SELECT k.nama, k.foto_kandidat, h.jumlah_suara
    FROM hasil h
    INNER JOIN kandidat k ON h.id_kandidat = k.id_kandidat
    WHERE h.id_pemilihan = ?
    ORDER BY h.jumlah_suara DESC
    LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_voting);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama_kandidat = $row['nama'];
        $foto_kandidat = $row['foto_kandidat'];
        $jumlah_suara = $row['jumlah_suara'];

        // Gabungkan path folder dengan nama file dari database
        $fotopath = $folder_kandidat . $foto_kandidat;
    } else {
        $error_message = "Tidak ada hasil untuk voting ini.";
    }
} else {
    $error_message = "ID Voting tidak valid.";
}

if ($id_voting) {
    $query_chart = "
    SELECT k.nama, h.jumlah_suara
    FROM hasil h
    INNER JOIN kandidat k ON h.id_kandidat = k.id_kandidat
    WHERE h.id_pemilihan = ?
    ";
    $stmt_chart = $conn->prepare($query_chart);
    $stmt_chart->bind_param('i', $id_voting);
    $stmt_chart->execute();
    $result_chart = $stmt_chart->get_result();

    $chart_data = [];
    while ($row = $result_chart->fetch_assoc()) {
        $chart_data[] = [
            'nama' => $row['nama'],
            'jumlah_suara' => $row['jumlah_suara']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Voting</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="hasil.css">
    <link rel="stylesheet" href="../dashboard.css">
    <style>
        .margin-profile{
            margin-top: 2.5px;
        }
    </style>
</head>
<body>
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
            <li class="nav-item"><a class="nav-link" href="event/hasil.php">Hasil</a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link nav-profile margin-profile" href="../profile/profile.php">
                    Profil
                    <img src="https://img.icons8.com/material-outlined/24/000000/user--v1.png" alt="User Icon">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php?keluar=true">
                    Keluar
                    <img src="../img/logout.png" alt="Keluar Icon">
                </a>
            </li>
        </ul>
    </div>
    </nav>

    <div class="container mt-5 hasil-voting">
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger text-center">
            <?= $error_message; ?>
        </div>
    <?php else: ?>
        <div class="row justify-content-between align-items-start">
            <!-- Card Pemenang -->
            <div class="col-md-5">
                <div class="card text-center p-4">
                    <h1 class="font-weight-bold" style="font-family: 'Lexend', sans-serif;">Pemenang Voting</h1>
                    <img src="<?= $fotopath; ?>" alt="<?= $nama_kandidat; ?>" class="rounded-circle mx-auto my-3 img-fluid pemenang-img">
                    <h2 class="mt-3"><?= $nama_kandidat; ?></h2>
                    <p class="mt-1">Jumlah Suara: <span class="font-weight-bold"><?= $jumlah_suara; ?></span></p>
                </div>
            </div>

            <!-- Card Chart -->
            <div class="col-md-6">
                <div class="card text-center p-4">
                    <h2 class="font-weight-bold mb-4" style="font-family: 'Lexend', sans-serif;">Statistik Voting</h2>
                    <!-- Placeholder Chart -->
                    <div id="chart-container" style="height: 300px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


    <!-- Footer -->
    <footer class="footer p-4" style="background-color: #000; color: white;">
        <div class="container d-flex justify-content-between">
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
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("pieChart").getContext("2d");
        
        // Data dari PHP
        const chartData = <?= json_encode($chart_data); ?>;
        
        const labels = chartData.map(data => data.nama);
        const dataValues = chartData.map(data => data.jumlah_suara);
        const backgroundColors = ['#bfbfbf', '#778899', '#DCDCDC', '#C0C0C0', '#808080', '#696969', '#00']; // Warna untuk kandidat
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: backgroundColors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>
</body>
</html>
