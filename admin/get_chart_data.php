<?php
include '../connect.php';

$id_pemilihan = isset($_GET['id_pemilihan']) ? $_GET['id_pemilihan'] : 0;

$sql = "SELECT kandidat.nama, COUNT(suara.id_kandidat) AS total_suara
        FROM suara
        JOIN kandidat ON suara.id_kandidat = kandidat.id_kandidat
        WHERE suara.id_pemilihan = ?
        GROUP BY suara.id_kandidat, kandidat.nama";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_pemilihan);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => $row['nama'],
            'value' => $row['total_suara']
        ];
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
