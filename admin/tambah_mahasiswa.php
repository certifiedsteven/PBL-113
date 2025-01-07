<?php
include '../connect.php';

// Jika request untuk AJAX (mengambil Prodi berdasarkan Jurusan)
if (isset($_GET['id_jurusan'])) {
    include '../connect.php'; // Pastikan file koneksi

    $id_jurusan = $_GET['id_jurusan'];

    // Query untuk mengambil data Prodi berdasarkan Jurusan
    $query = "
        SELECT id_prodi, prodi 
        FROM prodi 
        WHERE id_jurusan = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_jurusan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Loop hasil query untuk opsi dropdown
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id_prodi'] . "'>" . $row['prodi'] . "</option>";
        }
    } else {
        // Jika tidak ada Prodi ditemukan
        echo "<option value='' disabled>Tidak ada Prodi untuk Jurusan ini</option>";
    }

    $stmt->close();
    $conn->close();
    exit();
}



// Handle request untuk menambah mahasiswa baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $katasandi = $_POST['katasandi'];  // Kata sandi yang dimasukkan oleh pengguna
    $email = $_POST['email'];
    $jurusan = $_POST['jurusanMhs'];
    $prodi = $_POST['prodiMhs'];
    $angkatan = $_POST['angkatan'];

    // Validasi angkatan
    if (empty($angkatan) || $angkatan == '0') {
        $angkatan = date('Y');
    }


    // Validasi input (opsional, bisa ditambahkan lebih lanjut)
    if (empty($nama) || empty($nim) || empty($katasandi) || empty($email) || empty($jurusan) || empty($prodi) || empty($angkatan)) {
        die("Semua field harus diisi.");
    }

    // Hash kata sandi dengan MD5
    $hashedPassword = md5($katasandi);

    // Simpan data mahasiswa ke database
    $stmt = $conn->prepare("INSERT INTO pemilih (nama, nim, katasandi, email, jurusan, prodi, angkatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssii", $nama, $nim, $hashedPassword, $email, $jurusan, $prodi, $angkatan);

    if ($stmt->execute()) {
        echo "Data mahasiswa berhasil disimpan.";
        header("Location: pemilih.php"); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
