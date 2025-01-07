<?php
include "koneksi.php";

if (isset($_POST['submit'])) {
    $id_pemilih = $_POST['id_pemilih'];
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $katasandi = $_POST['katasandi'];
    $email = $_POST['email'];
    $jurusan = $_POST['jurusan'];
    $prodi = $_POST['prodi'];
    $angkatan = $_POST['angkatan'];

    $sql = "UPDATE pemilih 
            SET nim = '$nim', nama = '$nama', katasandi = '$katasandi', email = '$email', 
                jurusan = '$jurusan', prodi = '$prodi', angkatan = '$angkatan' 
            WHERE id_pemilih = '$id_pemilih'";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: pemilih.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }
}
?>
