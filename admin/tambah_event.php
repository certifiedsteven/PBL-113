<?php
include '../connect.php';

var_dump($_POST); // Untuk melihat data yang diterima

// Jika request untuk AJAX (mengambil Prodi berdasarkan Jurusan)
if (isset($_GET['id_jurusan'])) {
    $id_jurusan = $_GET['id_jurusan'];

    // Query untuk mengambil prodi berdasarkan jurusan yang dipilih
    $query = "
    SELECT p.id_prodi, p.prodi
    FROM prodi p
    INNER JOIN jurusan j ON p.id_jurusan = j.id_jurusan
    WHERE j.id_jurusan = ?";


    // Menyiapkan statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_jurusan); // Binding parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika ada hasil, tampilkan prodi
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id_prodi'] . "'>" . $row['prodi'] . "</option>";
        }
    } else {
        echo "<option value='' disabled>Tidak ada prodi untuk jurusan ini</option>";
    }

    $stmt->close();
    $conn->close();
    exit(); // Menghentikan script setelah proses untuk AJAX
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $prodi_id = $_POST['prodi']; // Mengambil 'prodi' yang benar
    $jurusan_id = $_POST['jurusan']; // Mengambil 'jurusan' yang benar
    $angkatan = $_POST['angkatan'];
    
    // Mengecek apakah semua field sudah terisi
    if (!empty($judul) && !empty($deskripsi) && !empty($waktu_mulai) && !empty($tanggal_mulai) && !empty($waktu_selesai) && !empty($tanggal_selesai) && !empty($prodi_id) && !empty($jurusan_id)) {
        // Query untuk insert event
        $sql = "INSERT INTO pemilihan (judul, deskripsi, waktu_mulai, tanggal_mulai, waktu_selesai, tanggal_selesai, prodi, jurusan, angkatan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssis", $judul, $deskripsi, $waktu_mulai, $tanggal_mulai, $waktu_selesai, $tanggal_selesai, $prodi_id, $jurusan_id, $angkatan);

        if ($stmt->execute()) {
            // Mendapatkan ID voting yang baru disisipkan
            $pemilihan_id = $stmt->insert_id;

            // Memasukkan kandidat
            if (isset($_POST['kandidat']) && !empty($_POST['kandidat'])) {
                $kandidat_ids = $_POST['kandidat'];

                foreach ($kandidat_ids as $kandidat_id) {
                    $sql_kandidat = "INSERT INTO mengambil_data (id_pemilihan, kandidat_id) VALUES (?, ?)";
                    $stmt_kandidat = $conn->prepare($sql_kandidat);
                    $stmt_kandidat->bind_param("ii", $pemilihan_id, $kandidat_id);

                    if (!$stmt_kandidat->execute()) {
                        echo "Terjadi kesalahan saat menambahkan kandidat: " . $conn->error;
                    }

                    $stmt_kandidat->close();
                }
            }

            echo "Acara berhasil ditambahkan.";
            header("Location: event.php");
        } else {
            echo "Terjadi kesalahan: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Semua field wajib diisi!";
    }

    $conn->close();
}
else {
    echo "Akses tidak valid!";
}
?>
