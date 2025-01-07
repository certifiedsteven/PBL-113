<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pemilihan = $_POST['id_pemilihan'];
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $waktu_mulai = $_POST['waktu_mulai'];

    $update_sql = "UPDATE pemilihan SET judul = ?, tanggal_mulai = ?, waktu_mulai = ? WHERE id_pemilihan = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $judul, $tanggal_mulai, $waktu_mulai, $id_pemilihan);

    if ($stmt->execute()) {
        echo "<script>alert('Event berhasil diperbarui!'); window.location.href = 'event_page.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui event.'); window.location.href = 'event_page.php';</script>";
    }
}
?>
