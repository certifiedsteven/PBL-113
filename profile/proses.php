<?php
include '../connect.php';

function gantiPassword($conn, $password_lama, $password_baru, $konfirmasi) {
    session_start();
    if (!isset($_SESSION['id'])) {
        return ["status" => "error", "pesan" => "Session tidak ditemukan. Silakan login ulang."];
    }

    if ($password_baru !== $konfirmasi) {
        return ["status" => "error", "pesan" => "Kata sandi baru dan konfirmasi tidak cocok."];
    }

    $id_pemilih = $_SESSION['id'];
    $password_lama_hashed = md5($password_lama);
    $password_baru_hashed = md5($password_baru);

    // Cek apakah password lama sesuai
    $sql = "SELECT katasandi FROM pemilih WHERE id_pemilih = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_pemilih);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['katasandi'] === $password_lama_hashed) {
            // Update password dengan password baru
            $update_sql = "UPDATE pemilih SET katasandi = ? WHERE id_pemilih = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $password_baru_hashed, $id_pemilih);

            if ($update_stmt->execute()) {
                // Logout pengguna
                session_destroy();
                return ["status" => "success", "pesan" => "Password berhasil diganti. Anda akan diarahkan ke halaman login."];
            } else {
                return ["status" => "error", "pesan" => "Gagal mengganti password. Silakan coba lagi."];
            }
        } else {
            return ["status" => "error", "pesan" => "Password lama salah."];
        }
    } else {
        return ["status" => "error", "pesan" => "ID pemilih tidak ditemukan."];
    }
}

// Tangani permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    $hasil = gantiPassword($conn, $password_lama, $password_baru, $konfirmasi);

    // Redirect dengan notifikasi
    header("Location: ganti.php?status=" . urlencode($hasil['status']) . "&pesan=" . urlencode($hasil['pesan']));
    exit;
}
?>
