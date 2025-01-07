<?php
include '../connect.php'; // Pastikan koneksi database terhubung

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id = $_POST['id_kandidat'];
    $nama = $_POST['nama'];
    $fotoLama = $_POST['fotoLama'];

    // Proses file foto kandidat jika diunggah
    if (!empty($_FILES['foto_kandidat']['name'])) {
        $foto = $_FILES['foto_kandidat'];
        $namaFoto = $foto['name'];
        $tmpFoto = $foto['tmp_name'];
        $ukuranFoto = $foto['size'];

        // Validasi tipe file
        $ekstensiValid = ['jpg', 'jpeg', 'png', 'gif'];
        $ekstensiFoto = strtolower(pathinfo($namaFoto, PATHINFO_EXTENSION));

        if (in_array($ekstensiFoto, $ekstensiValid)) {
            if ($ukuranFoto <= 2000000) { // Maksimal 2MB
                // Direktori penyimpanan
                $direktoriUpload = '../uploads/';
                $namaFotoBaru = uniqid() . '.' . $ekstensiFoto;
                $pathFoto = $direktoriUpload . $namaFotoBaru;

                // Hapus foto lama jika ada
                if (!empty($fotoLama) && file_exists('../uploads/' . $fotoLama)) {
                    unlink('../uploads/' . $fotoLama);
                }

                // Pindahkan file baru ke direktori tujuan
                if (move_uploaded_file($tmpFoto, $pathFoto)) {
                    $fotoBaru = $namaFotoBaru;
                } else {
                    echo "<script>alert('Gagal mengunggah foto baru!'); window.location.href='edit_kandidat.php?id=$id';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Ukuran foto terlalu besar! Maksimal 2MB.'); window.location.href='edit_kandidat.php?id=$id';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format foto tidak valid! Hanya JPG, JPEG, PNG, dan GIF yang diizinkan.'); window.location.href='edit_kandidat.php?id=$id';</script>";
            exit;
        }
    } else {
        // Jika tidak ada foto baru diunggah, gunakan foto lama
        $fotoBaru = $fotoLama;
    }

    // Query update data kandidat
    $sql = "UPDATE kandidat SET nama = ?, foto_kandidat = ? WHERE id_kandidat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $nama, $fotoBaru, $id);

    // Eksekusi query
    if ($stmt->execute()) {
        echo "<script>alert('Data kandidat berhasil diperbarui!'); window.location.href='adminpanel.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data kandidat!'); window.location.href='edit_kandidat.php?id=$id';</script>";
    }

    // Menutup koneksi
    $stmt->close();
    $conn->close();
} else {
    // Jika bukan metode POST, redirect ke halaman utama
    header("Location: adminpanel.php");
    exit();
}
