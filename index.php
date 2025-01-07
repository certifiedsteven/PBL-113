<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include 'connect.php';

$error = "";

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION["is_login"]) && $_SESSION["is_login"] === true) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/event.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nim']) && isset($_POST['katasandi'])) {
        $nim = $_POST['nim'];
        $katasandi = $_POST['katasandi'];
        $hashed_sandi = md5($katasandi); 

        if (strpos($nim, '.') !== false) {
            list($nama, $nim) = explode('.', $nim, 2);

            // Verif login apakah admin
            $stmt = $conn->prepare("SELECT * FROM admin WHERE nama = ? AND nim = ? AND katasandi = ?");
            $stmt->bind_param("sss", $nama, $nim, $hashed_sandi);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['nim'] = $row['nim'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['role'] = 'admin';
                $_SESSION["is_login"] = true;
                header("Location: admin/event.php");
                exit();
            } else {
                $error = "Nama atau NIM atau KATA SANDI salah";
            }
        } else {
            $stmt = $conn->prepare("SELECT * FROM pemilih WHERE nim = ? AND katasandi = ?");
            $stmt->bind_param("ss", $nim, $hashed_sandi);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['nim'] = $row['nim'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['id'] = $row['Id_pemilih'];
                $_SESSION['role'] = 'mahasiswa';
                $_SESSION['prodi'] = $row['prodi'];
                $_SESSION["is_login"] = true;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "NIM atau KATA SANDI salah";
            }
        }
    } else {
        $error = "Harap masukkan NIM dan kata sandi";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olympvote Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .full-screen {
            display: flex;
            height: 100vh;
            flex-wrap: wrap;
        }
        .left-section {
            flex: 1;
            background-color: #333333;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 20px;
            min-height: 100vh; /* Menjamin bagian ini memenuhi seluruh layar */
        }
        .left-section form {
            width: 100%;
            max-width: 350px;
        }
        .right-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #d9d9d9;
        }
        .logo {
            text-align: center;
            color: black;
            font-family: 'Lexend';
        }
        .form-control {
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .btn-login {
            background-color: black;
            color: white;
            width: 100%;
            border-radius: 5px;
        }
        .additional-links {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: white;
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            .full-screen {
                flex-direction: column;
                height: auto;
            }
            .left-section {
                width: 100%;
                padding: 15px;
                min-height: 100vh; /* Mastiin bagian hitam memenuhi layar */
            }
            .right-section {
                display: none; /* Sembunyiin bagian hitam di kanan layar pada perangkat mobile*/
            }
            .logo img {
                width: 120px;
            }
            .btn-login {
                padding: 15px;
                font-size: 1rem;
            }
            .form-control {
                padding: 12px;
                font-size: 1rem;
            }
            .additional-links {
                font-size: 0.8rem;
                flex-direction: column;
                align-items: center;
            }
            .additional-links a {
                margin-top: 5px;
            }
        }
        @media (max-width: 480px) {
            .logo img {
                width: 100px;
            }
            .btn-login {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="full-screen">
        <!-- Left Section for Login Form -->
        <div class="left-section">
            <form action="index.php" method="post">
                <?php if (!empty($error)): ?>
                    <p class="text-danger text-center"><?= htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <div class="text-center mb-4">
                    <img src="img/logo.png" alt="Logo" style="width: 80px; height: 80px;">
                </div>
                <input type="text" name="nim" class="form-control bg-light text-dark" placeholder="NIM" required>
                <input type="password" name="katasandi" class="form-control bg-light text-dark" placeholder="KATA SANDI" required>
                <button type="submit" class="btn btn-login mt-2">MASUK</button>
                <div class="additional-links">
                    <a href="https://wa.me/6281534337041" class="text-white">Lupa kata sandi?</a>
                    <a href="https://wa.me/6281534337041" class="text-white">Belum ada akun?</a>
                </div>
            </form>
        </div>  

        <!-- Right Section for Logo -->
        <div class="right-section">
            <div class="logo">
                <img src="img/logo.png" alt="Olympvote Logo" style="width: 150px;">
                <h1 class="mt-3">OLYMPVOTE</h1>
            </div>
        </div>
    </div>
</body>
</html>
