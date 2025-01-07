<?php
include '../connect.php'; // Pastikan Anda menghubungkan ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil dan memvalidasi data dari form
    $id_pemilihan = (int)$_POST['id_pemilihan'];
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $jurusan = (int)$_POST['jurusan'];
    $prodi = isset($_POST['prodi']) ? (int)$_POST['prodi'] : null; // Prodi bisa null
    $angkatan = (int)$_POST['angkatan'];

    // Validasi tambahan untuk input
    if (empty($judul) || empty($deskripsi) || empty($tanggal_mulai) || empty($waktu_mulai) || empty($tanggal_selesai) || empty($waktu_selesai) || empty($jurusan) || empty($angkatan)) {
        echo "<script>alert('Semua kolom wajib diisi!'); window.history.back();</script>";
        exit();
    }


    // Menyiapkan query untuk memperbarui data event
    $sql = "UPDATE pemilihan 
            SET judul = ?, deskripsi = ?, tanggal_mulai = ?, waktu_mulai = ?, tanggal_selesai = ?, waktu_selesai = ?, jurusan = ?, angkatan = ?, prodi = ? 
            WHERE id_pemilihan = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "<script>alert('Gagal menyiapkan query: " . $conn->error . "'); window.history.back();</script>";
        exit();
    }

    // Mengikat parameter (pastikan urutannya sesuai)
    $stmt->bind_param('sssssssisi', $judul, $deskripsi, $tanggal_mulai, $waktu_mulai, $tanggal_selesai, $waktu_selesai, $jurusan, $angkatan, $prodi, $id_pemilihan);

    // Menjalankan query
    if ($stmt->execute()) {
        // Jika berhasil, tampilkan pesan sukses
        echo "<script>alert('Event berhasil diperbarui!'); window.location.href = 'event.php';</script>";
    } else {
        // Jika gagal, tampilkan pesan error dengan informasi detail
        echo "<script>alert('Gagal memperbarui event: " . $stmt->error . "'); window.history.back();</script>";
    }

    // Menutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    // Jika bukan metode POST, redirect ke halaman admin panel
    header("Location: event.php");
    exit();
}
?>
