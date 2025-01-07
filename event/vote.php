<!DOCTYPE html>
<html lang="en">
<?php
include '../connect.php';
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

$prodi_mahasiswa = $_SESSION['prodi'];
$id_voting = $_GET['id_pemilihan'];

// Query untuk mengambil data event berdasarkan ID
$sqlEvent = "
    SELECT pemilihan.judul, pemilihan.deskripsi, pemilihan.tanggal_mulai, pemilihan.tanggal_selesai, 
           pemilihan.waktu_mulai, pemilihan.waktu_selesai, pemilihan.jurusan, pemilihan.prodi, 
           jurusan.nama_jurusan, prodi.prodi 
    FROM pemilihan
    JOIN jurusan ON pemilihan.jurusan = jurusan.id_jurusan
    JOIN prodi ON pemilihan.prodi = prodi.id_prodi
    WHERE pemilihan.id_pemilihan = ?
";
$stmtEvent = $conn->prepare($sqlEvent);
$stmtEvent->bind_param("i", $id_voting);
$stmtEvent->execute();
$resultEvent = $stmtEvent->get_result();

if ($resultEvent->num_rows > 0) {
    $rowEvent = $resultEvent->fetch_assoc();
    $judul = $rowEvent['judul'];
    $deskripsi = $rowEvent['deskripsi'];
    $tanggal_mulai = $rowEvent['tanggal_mulai'];
    $tanggal_selesai = $rowEvent['tanggal_selesai'];
    $waktu_mulai = $rowEvent['waktu_mulai'];
    $waktu_selesai = $rowEvent['waktu_selesai'];
    $jurusan = $rowEvent['nama_jurusan'];
    $prodi = $rowEvent['prodi'];
}

// Cek apakah user sudah memilih
$sqlCheck = "SELECT id_kandidat FROM suara WHERE id_pemilih = ? AND id_pemilihan = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $_SESSION['id'], $id_voting);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$userHasVoted = $resultCheck->num_rows > 0;
$votedCandidateId = $userHasVoted ? $resultCheck->fetch_assoc()['id_kandidat'] : null;

// Hitung jumlah suara masuk berdasarkan id_pemilihan
$sqlJumlahSuara = "SELECT COUNT(suara) AS total_suara FROM suara WHERE id_pemilihan = ?";
$stmtJumlahSuara = $conn->prepare($sqlJumlahSuara);
$stmtJumlahSuara->bind_param("i", $id_voting);
$stmtJumlahSuara->execute();
$resultJumlahSuara = $stmtJumlahSuara->get_result();
$totalSuara = ($resultJumlahSuara->num_rows > 0) ? $resultJumlahSuara->fetch_assoc()['total_suara'] : 0;

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Instructor of the Month</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .disabled-button {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }
        .alert-info{
            text-align: center !important;
        }
        .margin-profile{
            margin-top: 2.5px;
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
        <li class="nav-item"><a class="nav-link" href="hasil.php">Hasil</a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link nav-profile margin-profile" href="../profile/profile.php">
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

<!-- Timer Area -->
<nav class="navbar navbar-expand-sm custom-navbar navbares sticky-top" style="background-color: #bfbfbf; display: flex; justify-content: space-between; align-items: center; padding: 0 10px;">
    <!-- Tombol Back -->
    <a href="../daftar_event.php" style="text-decoration: none; display: flex; align-items: center; font-family: 'Lexend', sans-serif; color: black;">
        <i class="fas fa-arrow-left" style="font-size: 24px; margin-right: 10px;"></i> Kembali
    </a>

    <!-- Timer -->
    <div id="timer" style="font-size: 1.5rem; color: black;">Waktu Tersisa 00:00:00</div>
    
    <!-- Jumlah Suara -->
    <div id="jumlahSuara" style="font-size: 1.2rem; color: black;">Suara Masuk: 0</div>
</nav>


<?php
if (isset($_SESSION['notification'])) {
    echo "<div class='alert alert-info' role='alert'>" . $_SESSION['notification'] . "</div>";
    unset($_SESSION['notification']); // Hapus notifikasi setelah ditampilkan
}
?>

<!-- Konten -->
<div class="container-voting">
    <div class="title" style="color: #000000;">
        <?php echo $judul; ?>
    </div>
    <div class="subtitle" style="color: #000000;">
        <?php echo $deskripsi; ?>
    </div>
        <?php
        // Query untuk mengambil data kandidat dan foto
        $sqlKandidat = "SELECT kandidat.id_kandidat, kandidat.nama, kandidat.foto_kandidat 
        FROM kandidat 
        JOIN mengambil_data 
        ON kandidat.id_kandidat = mengambil_data.kandidat_id
        WHERE mengambil_data.id_pemilihan = ?";
        $stmtKandidat = $conn->prepare($sqlKandidat);
        $stmtKandidat->bind_param("i", $id_voting);
        $stmtKandidat->execute();
        $resultKandidat = $stmtKandidat->get_result();

        if ($resultKandidat->num_rows > 0) {
            $count = 0;
            echo "<div class='row'>";
            while ($rowKandidat = $resultKandidat->fetch_assoc()) {
                $idKandidat = $rowKandidat['id_kandidat'];
                $namaKandidat = $rowKandidat['nama'];
                $fotoKandidat = $rowKandidat['foto_kandidat'];

                // Cek apakah file foto ada
                $fotoPath = "../img/kandidat/$fotoKandidat";
                $isSelected = ($userHasVoted && $votedCandidateId == $idKandidat) ? "true" : "false";

                echo "<div class='col-6 col-md-3 text-center mb-4'>";
                echo "<div class='lecturer'>";
                echo "<div class='lecturer-name'>$namaKandidat</div>";
                echo "<img src='$fotoPath' alt='Foto Kandidat' height='200' width='150' />";
                
                $now = time(); // Waktu server saat ini
                $startTimestamp = strtotime($tanggal_mulai . ' ' . $waktu_mulai);
                $disabled = ($now < $startTimestamp) ? 'disabled' : '';

                echo "<button class='lecturer-button' data-id='$idKandidat' id='btnPilih' data-nama='$namaKandidat' data-selected='$isSelected' $disabled>PILIH</button>";
                echo "</div>";
                echo "</div>";

                $count++;
                if ($count % 4 == 0 && $count < $resultKandidat->num_rows) {
                    echo "</div><div class='row'>"; // Tutup row lama dan buka row baru
                }
            }
            echo "</div>"; // Penutup row terakhir
        } else {
            echo "<p>Tidak ada kandidat untuk event ini</p>";
        }
        ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const jumlahSuaraElement = document.getElementById("jumlahSuara");

    // Set jumlah suara dari PHP
    jumlahSuaraElement.textContent = `Suara Masuk: <?php echo $totalSuara; ?>`;
});

document.addEventListener("DOMContentLoaded", () => {
    const timerElement = document.getElementById("timer");

    // Tanggal dan waktu mulai dan selesai
    const startDateTime = new Date("<?php echo $tanggal_mulai . 'T' . $waktu_mulai; ?>").getTime();
    const endDateTime = new Date("<?php echo $tanggal_selesai . 'T' . $waktu_selesai; ?>").getTime();

    // Fungsi update timer
    function updateTimer() {
        const now = new Date().getTime();

        if (now < startDateTime) {
            timerElement.textContent = "Voting belum dimulai";
            document.querySelectorAll('.lecturer-button').forEach(button => {
                button.disabled = true; // Disable tombol
                button.classList.add("disabled-button");
                button.textContent = "Belum Dimulai"; // Teks untuk tombol
            });
            return;
        }

        let timeLeft = endDateTime - now;

        if (timeLeft <= 0) {
            timerElement.textContent = "Waktu telah habis";
            clearInterval(timerInterval); // Menghentikan interval saat waktu habis
            document.querySelectorAll('.lecturer-button').forEach(button => {
                button.disabled = true; // Disable tombol
                button.classList.add("disabled-button");
                button.textContent = "Waktu Habis"; // Teks untuk tombol
            });
            return;
        }

        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        timerElement.textContent = `${days}d ${String(hours).padStart(2, "0")}h ${String(minutes).padStart(2, "0")}m ${String(seconds).padStart(2, "0")}s`;
    }

    // Update setiap detik
    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Memperbarui langsung saat halaman dimuat

    // Disable tombol jika kandidat sudah dipilih
    document.querySelectorAll('.lecturer-button').forEach(button => {
        const isSelected = button.getAttribute('data-selected') === "true";
        if (isSelected) {
            button.disabled = true;
            button.textContent = "Sudah Dipilih"; // Ubah teks tombol
            button.classList.add("disabled-button"); // Tambahkan gaya visual jika diperlukan
        }
    });
});

    document.addEventListener("DOMContentLoaded", () => {
        let selectedCandidateId = null; // Menyimpan ID kandidat yang dipilih
        let selectedCandidateName = null; // Menyimpan nama kandidat yang dipilih

        // Event listener untuk tombol "PILIH" di daftar kandidat
        document.querySelectorAll('.lecturer-button').forEach(button => {
            button.addEventListener('click', () => {
                selectedCandidateId = button.getAttribute('data-id'); // Ambil ID kandidat
                selectedCandidateName = button.getAttribute('data-nama'); // Ambil nama kandidat
                
                // Tampilkan nama kandidat di modal
                document.getElementById('kandidat-name').textContent = selectedCandidateName;

                // Tampilkan modal konfirmasi
                $('#modalPilih').modal('show');
            });
        });

        // Event listener untuk tombol konfirmasi di modal
        document.getElementById('confirmPilih').addEventListener('click', () => {
            if (selectedCandidateId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'pilih_kandidat.php'; // Ganti dengan file yang akan menangani pemilihan

                const idVotingInput = document.createElement('input');
                idVotingInput.type = 'hidden';
                idVotingInput.name = 'id_voting';
                idVotingInput.value = "<?php echo $id_voting; ?>";

                const idKandidatInput = document.createElement('input');
                idKandidatInput.type = 'hidden';
                idKandidatInput.name = 'id_kandidat';
                idKandidatInput.value = selectedCandidateId;

                form.appendChild(idVotingInput);
                form.appendChild(idKandidatInput);
                document.body.appendChild(form);

                form.submit(); // Kirim form untuk menyimpan pemilihan kandidat
            } else {
                alert('Pilih kandidat terlebih dahulu.');
            }
        });

        // Cek jika ada pesan sukses setelah pemilihan
        <?php if (isset($_SESSION['voting_success']) && $_SESSION['voting_success'] === true): ?>
            alert('Terimakasih sudah memilih');
            <?php unset($_SESSION['voting_success']); ?>
        <?php endif; ?>
    });
</script>

</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalPilih" tabindex="-1" aria-labelledby="modalPilihLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihLabel">Konfirmasi Pilihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin memilih <span id="kandidat-name"></span> sebagai Instructor of the Month?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmPilih">Pilih</button>
            </div>
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


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
