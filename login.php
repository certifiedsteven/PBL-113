<head>
<link rel="icon" href="img/logo.png" type="image/icon type">
</head>
<?php
session_start();

// Data dummy untuk autentikasi
$valid_nim = "123456";
$valid_password = "password123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    // Validasi login
    if ($nim === $valid_nim && $password === $valid_password) {
        $_SESSION['nim'] = $nim;
        header("Location: indexx.html"); // Redirect ke dashboard setelah login berhasil
        exit();
    } else {
        echo "<script>alert('NIM atau kata sandi salah'); window.location.href='login.html';</script>";
    }
}
?>
