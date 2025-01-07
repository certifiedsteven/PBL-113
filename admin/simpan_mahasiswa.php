<?php
include '../connect.php'; // Pastikan Anda menghubungkan ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $jurusan = $_POST['jurusan'];
    $prodi = $_POST['prodi'];
    $angkatan = $_POST['angkatan'];

    // Menyiapkan query untuk memperbarui data mahasiswa
    $sql = "UPDATE pemilih SET nama = ?, email = ?, jurusan = ?, prodi = ?, angkatan = ? WHERE nim = ?";
    $stmt = $conn->prepare($sql);

    // Mengikat parameter (pastikan urutannya benar)
    $stmt->bind_param('ssssss', $nama, $email, $jurusan, $prodi, $angkatan, $nim);

    // Menjalankan query
    if ($stmt->execute()) {
        // Jika berhasil, tampilkan pesan sukses
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='pemilih.php';</script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>alert('Gagal menyimpan data!'); window.location.href='edit_mahasiswa.php?nim=" . htmlspecialchars($nim) . "';</script>";
    }

    // Menutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    // Jika bukan metode POST, redirect ke halaman daftar mahasiswa
    header("Location: pemilih.php");
    exit();
}
?>
