<?php
// Pastikan session dimulai untuk memeriksa apakah pengguna sudah login
session_start();

include 'connect.php';

// Ganti dengan ID pengguna yang aktif (misalnya dari session)
$userId = $_SESSION['id'];

// Query untuk mengecek apakah pengguna sudah memilih kandidat
$query = "SELECT id_kandidat FROM voting_kandidat WHERE id_pemilih = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($selectedCandidateId);
$stmt->fetch();

if ($selectedCandidateId) {
    // Jika sudah memilih kandidat, kirimkan ID kandidat yang dipilih
    echo json_encode(['selectedCandidateId' => $selectedCandidateId]);
} else {
    // Jika belum memilih kandidat, kirimkan status kosong
    echo json_encode(['selectedCandidateId' => null]);
}

$stmt->close();
?>
