<?php
require '../vendor/autoload.php'; // Pastikan Anda sudah menginstal PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];

        try {
            // Membaca file Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Loop untuk menyimpan data ke database
            foreach ($rows as $index => $row) {
                // Lewati baris pertama jika itu adalah header
                if ($index === 0) continue;

                $nama = $row[0];
                $nim = $row[1];
                $katasandi = md5($row[2]); // Enkripsi kata sandi dengan MD5
                $email = $row[3];
                $jurusan = (int)$row[4];
                $prodi = (int)$row[5];
                $angkatan = (int)$row[6];

                // Query untuk menyimpan data ke database
                $stmt = $conn->prepare("INSERT INTO pemilih (nama, nim, katasandi, email, jurusan, prodi, angkatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssiii", $nama, $nim, $katasandi, $email, $jurusan, $prodi, $angkatan);
                $stmt->execute();
            }

            echo "Data berhasil diunggah ke database.";
        } catch (Exception $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        echo "File tidak valid atau terjadi kesalahan saat mengunggah.";
    }
}
?>