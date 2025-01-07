<?php
include '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $foto = $_FILES['foto_kandidat'];
    $target_dir = "../img/kandidat/";

    // Membuat nama file unik dengan menambahkan timestamp
    $filename = pathinfo($foto['name'], PATHINFO_FILENAME); // Nama file tanpa ekstensi
    $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION)); // Ekstensi file
    $unique_name = $filename . "_" . time() . "." . $extension; // Nama file unik
    $target_file = $target_dir . $unique_name;

    $uploadOk = 1;
    $imageFileType = $extension;

    // Validasi tipe file
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Validasi file adalah gambar
    if ($uploadOk && !getimagesize($foto['tmp_name'])) {
        echo "File yang diunggah bukan gambar.";
        $uploadOk = 0;
    }

    // Proses unggah file
    if ($uploadOk && move_uploaded_file($foto['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO kandidat (nama, foto_kandidat) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $unique_name);

        if ($stmt->execute()) {
            header("Location: kandidat.php");
            exit(); 
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Pengunggahan foto gagal.";
    }
}

$conn->close();
?>
