<?php
include '../connect.php'; // Pastikan file connect.php terhubung dengan benar ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $id = $_POST['id_kandidat']; 
    $nama = $_POST['nama'];


    // Cek apakah ada file foto yang diunggah
    if (isset($_FILES['foto_kandidat']) && $_FILES['foto_kandidat']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp_name = $_FILES['foto_kandidat']['tmp_name'];
        $foto_name = basename($_FILES['foto_kandidat']['name']);
        $upload_dir = '../uploads/'; // Direktori untuk menyimpan file
        $foto_path = $upload_dir . $foto_name;

        // Validasi tipe file (hanya menerima gambar)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($foto_tmp_name);

        if (!in_array($file_type, $allowed_types)) {
            echo "<script>alert('Format file tidak valid! Hanya menerima JPG, PNG, dan GIF.'); window.history.back();</script>";
            exit();
        }

        // Pindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($_FILES['foto_kandidat']['tmp_name'], 'uploads/' . $new_filename)) {
            // Proses unggah berhasil
            echo "Foto kandidat berhasil diunggah.";
        } else {
            // Proses unggah gagal
            echo "Gagal mengunggah foto: " . $_FILES['foto_kandidat']['error'];
        }

        // Update database dengan nama file foto baru
        $sql = "UPDATE kandidat SET nama = ?, foto_kandidat = ? WHERE id_kandidat = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $nama, $foto_name, $id);
    } else {
        // Jika tidak ada file foto yang diunggah, hanya update nama kandidat
        $sql = "UPDATE kandidat SET nama = ? WHERE id_kandidat = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $nama, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        echo "<script>alert('Data kandidat berhasil disimpan!'); window.location.href='kandidat.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data kandidat!'); window.history.back();</script>";
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    // Jika bukan metode POST, redirect ke halaman admin panel
    header("Location: kandidat.php");
    exit();
}
?>