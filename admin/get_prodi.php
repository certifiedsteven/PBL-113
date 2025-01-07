<?php
include '../koneksi.php'; // Pastikan file koneksi database sudah disiapkan

if (isset($_GET['id_jurusan'])) {
    $id_jurusan = $_GET['id_jurusan'];

    $query = $conn->prepare("SELECT id, prodi FROM prodi WHERE id_jurusan = ?");
    $query->bind_param("i", $id_jurusan);
    $query->execute();
    $result = $query->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}
?>
