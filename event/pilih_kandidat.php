<?php 
session_start();
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Session pemilih tidak ditemukan']);
        exit();
    }

    $id_pemilih = $_SESSION['id'];
    $id_kandidat = $_POST['id_kandidat'] ?? null;
    $id_pemilihan = $_POST['id_voting'] ?? null;

    // Validasi data
    if (empty($id_pemilih) || empty($id_kandidat) || empty($id_pemilihan)) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit();
    }

    // Cek apakah pemilih sudah memberikan suara sebelumnya
    $sqlCheck = "SELECT * FROM suara WHERE id_pemilih = ? AND id_pemilihan = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $id_pemilih, $id_pemilihan);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Jika sudah memilih, tambahkan notifikasi ke session
        $_SESSION['notification'] = "Anda sudah memilih kandidat untuk pemilihan ini.";
        header("Location: vote.php?id_pemilihan=$id_pemilihan"); // Ganti dengan halaman voting
        exit();
    }

    // Insert suara ke tabel
    $sqlInsert = "INSERT INTO suara (id_pemilih, id_kandidat, id_pemilihan, suara) VALUES (?, ?, ?, 1)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iii", $id_pemilih, $id_kandidat, $id_pemilihan);

    if ($stmtInsert->execute()) {
        // Set session untuk menampilkan modal di halaman vote.php
        $_SESSION['voting_success'] = true;

        // Redirect ke halaman yang sesuai (menggunakan query string id_voting)
        header("Location: vote.php?id_pemilihan=$id_pemilihan");
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat memberikan suara']);
    }

    $stmtInsert->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode pengiriman tidak valid']);
    exit();
}
?>
