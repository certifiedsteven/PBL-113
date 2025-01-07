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
          <a href="event.php" class="list-group-item list-group-item-action py-2 ripple" id="eventLink">
            <span>PEMILIHAN</span>
          </a>
          <a href="pemilih.php" class="list-group-item list-group-item-action py-2 ripple" id="pemilihLink">
            <span>DATA PEMILIH</span>
          </a>
          <a href="hasil.php" class="list-group-item list-group-item-action py-2 ripple" id="hasilLink">
            <span>HASIL</span>
          </a>
          <a href="kandidat.php" class="list-group-item list-group-item-action py-2 ripple active" id="kandidatLink">
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
                    <a class="nav-link" href="logout.php?logout=true">
                        Keluar
                        <img src="../img/logout.png" alt="Logout Icon">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content">
        <!-- Kandidat Table -->
        <div class="container container-form" id="kandidatContent">
            <h2>Daftar Kandidat</h2>
            <button class="create-event-btn" id="createKandidat">Tambah Kandidat</button>
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nama</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ambilKandidat = "SELECT * FROM kandidat";
                    $resultKandidat = $conn->query($ambilKandidat);
                    if ($resultKandidat->num_rows > 0) {
                        while ($row = $resultKandidat->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id_kandidat']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>
                                    <form method='POST' action='edit_kandidat.php' style='display:inline;'>
                                        <input type='hidden' name='id_kandidat' value='" . htmlspecialchars($row['id_kandidat']) . "'>
                                        <button type='submit' class='btn-data'>Edit</button>
                                    </form>
                                    <form method='POST' action='hapus_kandidat.php' style='display:inline;' onsubmit='return confirm(\"Yakin ingin menghapus?\");'>
                                        <input type='hidden' name='id_kandidat' value='" . htmlspecialchars($row['id_kandidat']) . "'>
                                        <button type='submit' class='btn-data'>Hapus</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Tidak ada data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Popup Tambah Kandidat -->
        <div class="container container-form" id="popupTambahKandidat" style="display: none;">
            <button type="button" class="close-button" onclick="window.location.href='kandidat.php';">&times;</button>

            <form id="formTambahKandidat" method="POST" action="tambah_kandidat.php" enctype="multipart/form-data">
                <label for="nama">NAMA</label>
                <input type="text" id="nama" name="nama" placeholder="Nama Kandidat" required>

                <label for="foto_kandidat">UPLOAD FOTO</label>
                <input type="file" id="foto_kandidat" name="foto_kandidat" accept="image/*" required>

                <button type="button" class="submit-btn" data-toggle="modal" data-target="#confirmModalKandidat">Simpan</button>
            </form>
        </div>

        <!-- Modal Konfirmasi -->
        <div class="modal fade" id="confirmModalKandidat" tabindex="-1" role="dialog" aria-labelledby="confirmModalKandidatLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalKandidatLabel">Konfirmasi Simpan Data</h5>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menyimpan data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirmSaveKandidat">Ya, Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("createKandidat").addEventListener("click", function() {
            const popupTambahKandidat = document.getElementById("popupTambahKandidat");
            const kandidatContent = document.getElementById("kandidatContent");

            kandidatContent.style.display = "none";
            popupTambahKandidat.style.display = "block";
        });

        document.getElementById('confirmSaveKandidat').addEventListener('click', function() {
            document.getElementById('formTambahKandidat').submit();
        });
    </script>
</body>
</html>