<?php
ini_set('session.gc_maxlifetime', 5);
session_set_cookie_params(5);

session_start();

if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header('location: ../index.php');
}

include '../connect.php';

// Ambil daftar event voting
$sqlevent = "SELECT id_pemilihan, judul FROM pemilihan";
$resultevent = $conn->query($sqlevent);
$events = [];
if ($resultevent->num_rows > 0) {
    while ($row = $resultevent->fetch_assoc()) {
        $events[] = $row;
    }
}

// Default id_voting untuk ditampilkan
$id_pemilihan = isset($_GET['id_pemilihan']) ? $_GET['id_pemilihan'] : (isset($events[0]['id_pemilihan']) ? $events[0]['id_pemilihan'] : 0);

if (isset($_POST['publish'])) {
    // Mengambil data suara berdasarkan id_pemilihan yang dipilih
    $sql = "SELECT id_kandidat, COUNT(id_kandidat) AS jumlah_suara 
            FROM suara 
            WHERE id_pemilihan = ? 
            GROUP BY id_kandidat";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_pemilihan);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memasukkan hasil ke dalam tabel hasil
    while ($row = $result->fetch_assoc()) {
        $id_kandidat = $row['id_kandidat'];
        $jumlah_suara = $row['jumlah_suara'];

        // Periksa apakah hasil sudah ada di tabel hasil
        $checkSql = "SELECT id_hasil FROM hasil WHERE id_pemilihan = ? AND id_kandidat = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param('ii', $id_pemilihan, $id_kandidat);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Jika hasil sudah ada, update jumlah suara
            $updateSql = "UPDATE hasil SET jumlah_suara = ? WHERE id_pemilihan = ? AND id_kandidat = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('iii', $jumlah_suara, $id_pemilihan, $id_kandidat);
            $updateStmt->execute();
        } else {
            // Jika belum ada, insert data ke tabel hasil
            $insertSql = "INSERT INTO hasil (id_pemilihan, id_kandidat, jumlah_suara) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param('iii', $id_pemilihan, $id_kandidat, $jumlah_suara);
            $insertStmt->execute();
        }
    }

    // Menutup statement dan koneksi setelah selesai
    $stmt->close();
    $conn->close();

    // Refresh halaman setelah publikasi
    header("Location: hasil.php?id_pemilihan=$id_pemilihan");
    exit();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="adminpanels.css">
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse">
      <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
        <div class="judul-panel">PANEL ADMIN</div>
          <a href="event.php" class="list-group-item list-group-item-action py-2 ripple active" id="eventLink">
            <span>PEMILIHAN</span>
          </a>
          <a href="pemilih.php" class="list-group-item list-group-item-action py-2 ripple" id="pemilihLink">
            <span>DATA PEMILIH</span>
          </a>
          <a href="hasil.php" class="list-group-item list-group-item-action py-2 ripple" id="hasilLink">
            <span>HASIL</span>
          </a>
          <a href="kandidat.php" class="list-group-item list-group-item-action py-2 ripple" id="kandidatLink">
            <span>DATA KANDIDAT</span>
          </a>
        </div>
      </div>
    </nav>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm custom-navbar sticky-top">
        <a class="navbar-brand">
            <img src="../img/logo.png" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse w-100" id="navbarNav">
            <ul class="navbar-nav">
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" action="dashboard.php" name="logout" href="logout.php">
                        Keluar
                    <img src="../img/logout.png" alt="Logout Icon">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hasil -->
    <div class="container container-form container-data" id="hasilContent">
        <h2>Hasil Voting</h2>

        <!-- Dropdown untuk memilih event -->
        <form method="GET" action="">
            <label for="id_pemilihan">Pilih Event Voting:</label>
            <select name="id_pemilihan" id="id_pemilihan" onchange="updateChart()">
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['id_pemilihan']; ?>" <?php echo ($id_pemilihan == $event['id_pemilihan']) ? 'selected' : ''; ?>>
                        <?php echo $event['judul']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <canvas id="pieChart"></canvas>
        <!-- Tombol Publish Hasil -->
        <form method="POST" action="">
            <button type="submit" name="publish" class="btn btn-primary">Publish Hasil</button>
        </form>

    <script>
        let pieChart; // Simpan instance chart untuk memperbarui datanya

function updateChart() {
    const idPemilihan = document.getElementById('id_pemilihan').value;

    fetch(`get_chart_data.php?id_pemilihan=${idPemilihan}`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.label);
            const values = data.map(item => item.value);

            if (pieChart) {
                pieChart.data.labels = labels;
                pieChart.data.datasets[0].data = values;
                pieChart.update(); // Perbarui chart
            } else {
                const ctx = document.getElementById('pieChart').getContext('2d');
                pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Suara',
                            data: values,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        },
                    },
                });
            }
        })
        .catch(error => console.error('Error fetching chart data:', error));
}

// Panggil updateChart setiap beberapa detik
setInterval(updateChart, 2000);

// Panggil updateChart saat halaman pertama kali dimuat
updateChart();
</script>
</div>
