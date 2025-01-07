<?php
ini_set('session.gc_maxlifetime', 5); 
session_set_cookie_params(5); 
date_default_timezone_set('Asia/Jakarta'); // Ganti sesuai dengan zona waktu yang diinginkan

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
        FROM suara
        JOIN kandidat ON suara.id_kandidat = kandidat.id_kandidat
        WHERE suara.id_pemilihan = ?
        GROUP BY suara.id_kandidat, kandidat.nama";
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
    header("Location: adminpanel.php?id_pemilihan=$id_pemilihan");
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
    <link rel="stylesheet" href="adminpanels.css?v=<?php echo time(); ?>">
    <style>

    </style>
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
                    <a class="nav-link" action="logout.php" name="logout" href="logout.php">
                        Keluar
                    <img src="../img/logout.png" alt="Logout Icon">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Event -->
    <div class="container container-form" id="eventContent">
    <h2>Daftar Pemilihan</h2>
    <button class="create-event-btn" id="createEvent">Buat Acara</button>
    <table class="event-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Tanggal Mulai</th>
                <th>Waktu Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Waktu Selesai</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Waktu Tersisa</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
        <?php 
                include '../connect.php';

                $sqlEvent = "
                    SELECT pemilihan.*, jurusan.nama_jurusan, prodi.prodi 
                    FROM pemilihan
                    JOIN jurusan ON pemilihan.jurusan = jurusan.id_jurusan
                    JOIN prodi ON pemilihan.prodi = prodi.id_prodi
                ";

                $resultEvent = $conn->query($sqlEvent);

                if ($resultEvent === false) {
                    echo "Error: " . $conn->error;
                } elseif ($resultEvent->num_rows > 0) {
                    $noEvent = 1;
                    while ($rowEvent = $resultEvent->fetch_assoc()) {
                        $current_time = new DateTime(); // Current time

                        // Combine start and end datetime
                        $start_time = new DateTime($rowEvent['tanggal_mulai'] . ' ' . $rowEvent['waktu_mulai']);
                        $end_time = new DateTime($rowEvent['tanggal_selesai'] . ' ' . $rowEvent['waktu_selesai']);
                        
                        // Check if event has not started
                        if ($current_time < $start_time) {
                            $interval = $current_time->diff($start_time);
                            $time_remaining = "Dimulai dalam " . $interval->format('%a hari, %h jam, %i menit');
                        } 
                        // Check if event is ongoing
                        elseif ($current_time >= $start_time && $current_time <= $end_time) {
                            $interval = $current_time->diff($end_time);
                            $time_remaining = "Berlangsung, sisa " . $interval->format('%a hari, %h jam, %i menit');
                        } 
                        // If event is already finished
                        else {
                            $time_remaining = "Event telah selesai";
                        }
                        echo "<tr>
                            <td>{$rowEvent['id_pemilihan']}</td>
                            <td>{$rowEvent['judul']}</td>
                            <td>{$rowEvent['deskripsi']}</td>
                            <td>{$rowEvent['tanggal_mulai']}</td>
                            <td>{$rowEvent['waktu_mulai']}</td>
                            <td>{$rowEvent['tanggal_selesai']}</td>
                            <td>{$rowEvent['waktu_selesai']}</td>
                            <td>{$rowEvent['nama_jurusan']}</td>
                            <td>{$rowEvent['prodi']}</td>
                            <td>{$time_remaining}</td>
                            <td>
                                <form action='hapus_event.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='id_pemilihan' value='{$rowEvent['id_pemilihan']}'>
                                    <button type='submit' class='delete-btn' onclick=\"return confirm('Apakah Anda yakin ingin menghapus event ini?');\">Hapus</button>
                                </form>
                                <form action='edit_event.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='id_pemilihan' value='{$rowEvent['id_pemilihan']}'>
                                    <button type='submit' class='edit-btn'>Edit</button>
                                </form>
                            </td>
                        </tr>";
                        $noEvent++;
                    }
                } else {
                    echo "<tr><td colspan='12'>Tidak ada data event</td></tr>";
                }
            ?>
        </tbody>
    </table>
</div>

<!-- Form konten event -->
<div class="container container-forms" id="eventForm">
    <span class="close-button" onclick="closeForm()">×</span>
    <form id="formTambahEvent" method="POST" action="tambah_event.php">
    <button type="button" class="close-button" onclick="window.location.href='event.php';" 
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
    <!-- Judul Event -->
        <label for="judul">JUDUL</label>
        <input type="text" id="title" name="judul" placeholder="Masukkan judul acara">
        
        <!-- Deskripsi Event -->
        <label for="deskripsi">DESKRIPSI</label>
        <textarea id="description" name="deskripsi" placeholder="Masukkan deskripsi acara"></textarea>
        
        <!-- Waktu Mulai dan Selesai -->
        <div class="time-container">
            <div>
                <label>WAKTU MULAI</label>
                <input type="time" placeholder="Jam" name="waktu_mulai">
                <input type="date" placeholder="Tgl" name="tanggal_mulai">
            </div>
            <div>
                <label>Selesai</label>
                <input type="time" placeholder="Jam" name="waktu_selesai">
                <input type="date" placeholder="Tgl" name="tanggal_selesai">
            </div>
        </div>

        <!-- Tipe Event -->
        <p>Tipe</p>
        <div class="details-container">
            <!-- Jurusan dan Prodi -->
            <label>Jurusan:
                <select id="jurusan" name="jurusan" onchange="updateProdi()">
                    <option value="" disabled selected>Pilih jurusan</option>
                    <option value="1" <?php echo isset($_POST['jurusan']) && $_POST['jurusan'] == '1' ? 'selected' : ''; ?>>Informatika</option>
                </select>
            </label>

            <label>Prodi:
                <select id="prodi" name="prodi">
                    <option value="" disabled selected>Pilih prodi</option>
                </select>
            </label>

            <!-- Tahun Angkatan -->
            <label>Tahun:
                <select id="tahun" name="angkatan">
                    <option value="" disabled selected>Pilih tahun</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
            </label>
        </div>

        <!-- Pilih Kandidat menggunakan checkbox -->
        <label for="kandidat">Pilih Kandidat:</label>
            <div class="checkbox-container">
                <?php
                $query = "SELECT id_kandidat, nama FROM kandidat";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // Loop untuk menampilkan kandidat sebagai checkbox
                    while($row = $result->fetch_assoc()) {
                        echo "<label><input type='checkbox' name='kandidat[]' value='" . $row['id_kandidat'] . "'> " . $row['nama'] . "</label><br>";
                    }
                } else {
                    echo "<label><input type='checkbox' disabled>Tidak ada kandidat</label>";
                }
                ?>
            </div>


        
        <!-- Tombol Simpan memicu modal -->
        <button type="button" class="submit-btn" data-toggle="modal" data-target="#confirmModal">Simpan</button>
    </form>
</div>


<script>
    document.getElementById("createEvent").addEventListener("click", function() {
    const eventForm = document.getElementById("eventForm");
    const eventContent = document.getElementById("eventContent");

    if (eventContent) {
        eventContent.style.display = "none";
    }

    if (eventForm.style.display === "none" || eventForm.style.display === "") {
        eventForm.style.display = "block";
    } else {
        eventForm.style.display = "none";
    }
});
</script>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Simpan Data</h5>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menyimpan data event ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmSave">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alert (Delete Event) -->
        <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                    <button type="button" class="close-alert" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="alertModalBody">
                    Tidak dapat menghapus acara ini karena terkait dengan suara. Harap hapus suara yang terkait terlebih dahulu.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<!-- Tambahkan Library JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Dinamisasi Prodi berdasarkan Jurusan
    function updateProdi() {
        const jurusan = document.getElementById('jurusan').value;
        const prodiSelect = document.getElementById('prodi');

        // Kosongkan opsi sebelumnya
        prodiSelect.innerHTML = '<option value="" disabled selected>Pilih prodi</option>';

        // Tambahkan opsi berdasarkan jurusan
        if (jurusan === '1') {
            prodiSelect.innerHTML += `
                <option value="11">Teknik Informatika</option>
                <option value="12">Sistem Informasi</option>
            `;
    
        }
    }

    // Handle tombol "Ya, Simpan" pada modal
    document.getElementById('confirmSave').addEventListener('click', function () {
        document.getElementById('formTambahEvent').submit(); // Submit form
    });
</script>

<script>
        // Check if the URL contains a message parameter
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');

        // Display an alert based on the message
        if (message === 'success') {
            document.getElementById('alertModalBody').innerText = 'Event deleted successfully.';
            $('#alertModal').modal('show'); // Show the modal
        } else if (message === 'error') {
            document.getElementById('alertModalBody').innerText = 'Failed to delete the event. Please try again.';
            $('#alertModal').modal('show'); // Show the modal
        } else if (message === 'foreign_key_error') {
            document.getElementById('alertModalBody').innerText = 'Tidak dapat menghapus acara ini karena terkait dengan suara. Harap hapus suara yang terkait terlebih dahulu.';
            $('#alertModal').modal('show'); // Show the modal
        }
</script>

<script src="scripts.js"></script>
</body>
</html>