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

// Ambil daftar event pemilihan
$sqlevent = "SELECT id_pemilihan, judul FROM pemilihan";
$resultevent = $conn->query($sqlevent);
$events = [];
if ($resultevent->num_rows > 0) {
    while ($row = $resultevent->fetch_assoc()) {
        $events[] = $row;
    }
}

// Default id_pemilihan untuk ditampilkan
$id_pemilihan = isset($_GET['id_pemilihan']) ? $_GET['id_pemilihan'] : (isset($events[0]['id_pemilihan']) ? $events[0]['id_pemilihan'] : 0);

// Query untuk mendapatkan data suara beserta nama kandidat berdasarkan event
$sqlhasil = "SELECT kandidat.nama, COUNT(suara.id_kandidat) AS total_suara
            FROM kandidat
            LEFT JOIN suara ON kandidat.id_kandidat = suara.id_kandidat 
            AND suara.id_pemilihan = ?
            GROUP BY kandidat.id_kandidat, kandidat.nama";

$stmt = $conn->prepare($sqlhasil);
$stmt->bind_param('i', $id_pemilihan);
$stmt->execute();
$resulthasil = $stmt->get_result();


// Siapkan data untuk chart
$labels = [];
$data = [];
if ($resulthasil->num_rows > 0) {
    while ($row = $resulthasil->fetch_assoc()) {
        $labels[] = $row['nama']; // Ambil nama kandidat
        $data[] = $row['total_suara']; // Total suara per kandidat
    }
} else {
    $labels[] = "Tidak ada data";
    $data[] = 0;
}

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
    header("Location: pemilih.php?id_pemilihan=$id_pemilihan");
    exit();
}

$stmt->close();
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
                    <a class="nav-link" action="logout.php" name="logout" href="logout.php?logout=true">
                        Keluar
                    <img src="../img/logout.png" alt="Logout Icon">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Data Pemilih-->
    <div class="container container-form container-data" id="pemilihForm">
        <h1>Daftar Mahasiswa</h1>
        <h1>Upload File Excel</h1>
    <form action="aksi.php" method="POST" enctype="multipart/form-data">
        <label for="excel_file">Pilih file Excel:</label>
        <input type="file" name="excel_file" id="excel_file" required>
        <button type="submit">Upload</button>
    </form>
        <div class="btn-container">
            <button class="btn-data" id="tambahMahasiswaBtn">Tambah Mahasiswa</button>
        </div>
        <!-- Filter dan Sorting -->
        <div style="margin-bottom: 20px;">
            <label for="filterField">Urutkan Berdasarkan:</label>
            <select id="filterField">
                <option value="nim">NIM</option>
                <option value="nama">Nama</option>
                <option value="angkatan">Angkatan</option>
            </select>
            <label for="filterOrder">Urutan:</label>
            <select id="filterOrder">
                <option value="ASC">Ascending</option>
                <option value="DESC">Descending</option>
            </select>
            <button id="applyFilter">Terapkan</button>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody id="mahasiswaTableBody">
                <?php
                    include '../connect.php';
                    $no = 1;

                    // Query untuk menampilkan data mahasiswa dengan nama jurusan dan prodi
                   // Ubah query untuk memastikan angkatan terambil dengan benar
                    $ambildataMhs = "
                    SELECT pemilih.*, prodi.prodi, jurusan.nama_jurusan,
                    CASE 
                        WHEN pemilih.angkatan = 0 OR pemilih.angkatan IS NULL THEN DATE_FORMAT(CURRENT_DATE, '%Y')
                        ELSE pemilih.angkatan 
                    END as angkatan
                    FROM pemilih
                    LEFT JOIN prodi ON pemilih.prodi = prodi.id_prodi
                    LEFT JOIN jurusan ON prodi.id_jurusan = jurusan.id_jurusan";
                    
                    $resultMhs = $conn->query($ambildataMhs);

                    if ($resultMhs->num_rows > 0) {
                        while ($row = $resultMhs->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nim'] ?? 'Tidak ada data') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama'] ?? 'Tidak ada data') . "</td>";
                            echo "<td>" . htmlspecialchars($row['email'] ?? 'Tidak ada data') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_jurusan'] ?? 'Tidak ada data') . "</td>";
                            echo "<td>" . htmlspecialchars($row['prodi'] ?? 'Tidak ada data') . "</td>"; 
                            echo "<td>" . htmlspecialchars($row['angkatan'] ?? 'Tidak ada data') . "</td>";
                            echo "<td>
                                    <form method='POST' action='edit_mahasiswa.php' style='display:inline;'>
                                        <input type='hidden' name='nim' value='" . htmlspecialchars($row['nim'] ?? '') . "'>
                                        <button type='submit' class='btn-data'>Edit</button>
                                    </form>
                                    <form method='POST' action='hapus_mahasiswa.php' style='display:inline;' onsubmit='return confirm(\"Yakin ingin menghapus?\");'>
                                        <input type='hidden' name='nim' value='" . htmlspecialchars($row['nim'] ?? '') . "'>
                                        <button type='submit' class='btn-data'>Hapus</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Tidak ada data.</td></tr>";
                    }
                    $conn->close();
                ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Form Tambah Mahasiswa -->
    <div class="container container-form" id="popupTambahMahasiswa" style="display: none;">
    <button type="button" class="close-button" onclick="window.location.href='pemilih.php';" 
        style="
            position: absolute;
            top: 5px;
            right: 8px;
            background: none;
            border: none;
            font-size: 30px;
            color: #333;
            cursor: pointer;
            transition: color 0.3s ease;
            outline: none;
        " onmouseover="this.style.color='#dc3545'" onmouseout="this.style.color='#333'">
        &times;
    </button>


            <h3 class="title-popup-mahasiswa">Tambah Mahasiswa</h3>
            <form id="formTambahMahasiswa" method="POST" action="tambah_mahasiswa.php">
                <div class="form-left-mahasiswa">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama Mahasiswa" required>
    
                    <label for="nim">NM</label>
                    <input type="text" id="nim" name="nim" placeholder="NIM Mahasiswa" required>
    
                    <label for="katasandi">Kata Sandi</label>
                    <input type="text" id="katasandi" name="katasandi" placeholder="Kata Sandi" required>
    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email Mahasiswa" required>
                </div>
                <div class="form-right-mahasiswa">
                    <label for="jurusan">Jurusan</label>
                    <select class="selectMahasiswa" id="jurusanMhs" name="jurusanMhs" onchange="updateProdiMhs()" required>
                        <option value="" disabled selected>-- Pilih Jurusan --</option>
                        <option value="1" <?php echo isset($_POST['jurusanMhs']) && $_POST['jurusanMhs'] == '1' ? 'selected' : ''; ?>>Informatika</option>
                        <option value="2" <?php echo isset($_POST['jurusanMhs']) && $_POST['jurusanMhs'] == '2' ? 'selected' : ''; ?>>Elektro</option>
                    </select>
    
                    <label for="prodi">Prodi</label>
                    <select class="selectMahasiswa" id="prodiMhs" name="prodiMhs" required>
                        <option value="" disabled selected>-- Pilih Prodi --</option>
                    </select>

                    <label for="angkatan">Angkatan</label>
                    <select class="selectMahasiswa" id="angkatan" name="angkatan" required>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>

                    <button type="submit" class="submit-mahasiswa-btn" data-toggle="modal" data-target="#confirmModal">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    
<script src="scripts.js"></script>
</body>
</html>