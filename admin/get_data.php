<?php
include '../connect.php';

// Ambil data jurusan
$sql_jurusan = "SELECT * FROM jurusan";
$result_jurusan = $conn->query($sql_jurusan);

// Ambil data prodi berdasarkan jurusan yang dipilih
$prodi_options = [];
if (isset($_POST['jurusan'])) {
    $id_jurusan = $_POST['jurusan'];
    $sql_prodi = "SELECT * FROM prodi WHERE id_jurusan = ?";
    $stmt = $conn->prepare($sql_prodi);
    $stmt->bind_param("i", $id_jurusan);
    $stmt->execute();
    $result_prodi = $stmt->get_result();
    
    while ($row = $result_prodi->fetch_assoc()) {
        $prodi_options[] = $row;
    }
}

$conn->close();

// Kembalikan hasil dalam format array
echo json_encode([
    'jurusan' => $result_jurusan->fetch_all(MYSQLI_ASSOC),
    'prodi' => $prodi_options
]);
?>
