<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olympvote Login</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <script>
        function redirectToLogin() {
            setTimeout(() => {
                window.location.href = '../dashboard.php?logout=true';
            }, 3000);
        }

        function togglePasswordVisibility(id, iconId) {
            const passwordField = document.getElementById(id);
            const icon = document.getElementById(iconId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[@$!%*?&]/.test(password)) strength++;

            strengthBar.value = strength;
            const levels = ["Very Weak", "Weak", "Fair", "Good", "Strong"];
            strengthText.textContent = levels[strength - 1] || "Very Weak";
        }
    </script>
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
        /* Icons untuk password visibilitas */
        .icon-container {
            position: relative;
        }
        .icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }
        .strength-wrapper {
            margin-top: 10px;
        }
        .strength-bar {
            width: 100%;
            height: 10px;
            margin-bottom: 5px;
        }
        .strength-text {
            font-size: 0.9rem;
            text-align: right;
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
    <div class="left-section">
        <form action="proses.php" method="post">
            <div class="text-center mb-4">
                <img src="../img/logo.png" alt="Logo" style="width: 80px; height: 80px;">
            </div>

            <?php if (isset($_GET['pesan'])): ?>
                <div class="alert alert-<?= htmlspecialchars($_GET['status'] === 'success' ? 'success' : 'danger') ?> text-center">
                    <?= htmlspecialchars($_GET['pesan']) ?>
                </div>
                <?php if ($_GET['status'] === 'success'): ?>
                    <script>redirectToLogin();</script>
                <?php endif; ?>
            <?php endif; ?>

            <div class="icon-container">
                <input type="password" name="password_lama" id="passwordLama" class="form-control bg-light text-dark" placeholder="KATA SANDI LAMA" required>
                <i class="fas fa-eye icon" id="togglePasswordLama" onclick="togglePasswordVisibility('passwordLama', 'togglePasswordLama')"></i>
            </div>

            <div class="icon-container">
                <input type="password" name="password_baru" id="passwordBaru" class="form-control bg-light text-dark" placeholder="KATA SANDI BARU" required oninput="checkPasswordStrength(this.value)">
                <i class="fas fa-eye icon" id="togglePasswordBaru" onclick="togglePasswordVisibility('passwordBaru', 'togglePasswordBaru')"></i>
            </div>

            <div class="icon-container">
                <input type="password" name="konfirmasi" id="passwordKonfirmasi" class="form-control bg-light text-dark" placeholder="KONFIRMASI" required>
                <i class="fas fa-eye icon" id="togglePasswordKonfirmasi" onclick="togglePasswordVisibility('passwordKonfirmasi', 'togglePasswordKonfirmasi')"></i>
            </div>

            <div class="strength-wrapper">
                <progress id="passwordStrength" class="strength-bar" value="0" max="5"></progress>
                <div id="strengthText" class="strength-text">Very Weak</div>
            </div>

            <button type="submit" class="btn btn-login mt-2">SUBMIT</button>
        </form>
    </div>  
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>
</body>
</html>