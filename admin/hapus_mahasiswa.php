<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nim'])) {
    $nim = $_POST['nim'];

    $checkSql = "SELECT * FROM pemilih WHERE nim = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $nim);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "DELETE FROM pemilih WHERE nim = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $nim);

        if ($stmt->execute()) {
            header("Location: pemilih.php");
            echo "Data berhasil dihapus.";
        } else {
            echo "Gagal menghapus data: " . $stmt->error;
        }
    } else {
        echo "Data dengan NIM ini tidak ditemukan.";
    }

    $checkStmt->close();
    $stmt->close();
    $conn->close();
} else {
    echo "Metode tidak valid atau NIM tidak diterima.";
}
?>
