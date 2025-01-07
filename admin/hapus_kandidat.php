<?php
include '../connect.php';

if (isset($_POST['id_kandidat'])) {
    $id = $_POST['id_kandidat'];

    if (is_numeric($id)) {
        $sql = "DELETE FROM kandidat WHERE id_kandidat = $id";

        if ($conn->query($sql) === TRUE) {
            header("Location: kandidat.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "ID tidak valid!";
    }
} else {
    echo "ID kandidat tidak ditemukan!";
}

$conn->close();
?>