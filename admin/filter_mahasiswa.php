<?php
include '../connect.php';

// Ambil parameter dari AJAX
$field = isset($_POST['field']) ? $_POST['field'] : 'nim'; // Default: NIM
$order = isset($_POST['order']) ? $_POST['order'] : 'ASC'; // Default: ASC

// Validasi input
$allowedFields = ['nim', 'nama', 'angkatan'];
$allowedOrders = ['ASC', 'DESC'];

if (!in_array($field, $allowedFields)) {
    $field = 'nim';
}

if (!in_array($order, $allowedOrders)) {
    $order = 'ASC';
}

// Query untuk mendapatkan data sesuai filter
$sql = "
    SELECT pemilih.*, jurusan.nama_jurusan, prodi.prodi
    FROM pemilih
    JOIN jurusan ON pemilih.jurusan = jurusan.id_jurusan
    JOIN prodi ON pemilih.prodi = prodi.id
    ORDER BY $field $order
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_jurusan']) . "</td>";
        echo "<td>" . htmlspecialchars($row['prodi']) . "</td>"; 
        echo "<td>" . htmlspecialchars($row['angkatan']) . "</td>";
        echo "<td>
                <form method='POST' action='edit_mahasiswa.php' style='display:inline;'>
                    <input type='hidden' name='nim' value='" . htmlspecialchars($row['nim']) . "'>
                    <button type='submit' class='btn-data'>Edit</button>
                </form>
                <form method='POST' action='hapus_mahasiswa.php' style='display:inline;' onsubmit='return confirm(\"Yakin ingin menghapus?\");'>
                    <input type='hidden' name='nim' value='" . htmlspecialchars($row['nim']) . "'>
                    <button type='submit' class='btn-data'>Hapus</button>
                </form>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Tidak ada data.</td></tr>";
}

$conn->close();
?>
