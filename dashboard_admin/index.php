<?php
session_start();
include './model/config_db.php'; // include koneksi database

$error = ''; 

// --- AUTO LOGIN JIKA ADA COOKIE ---
if (isset($_COOKIE['remember_user']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['remember_user'];
    header("Location: view/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query PostgreSQL
    $query = "SELECT * FROM admin WHERE username = $1 AND password = $2";
    $result = pg_query_params($conn, $query, [$username, $password]);

    if (pg_num_rows($result) == 1) {
        // --- SET SESSION ---
        $_SESSION['username'] = $username;

        // --- SET COOKIE REMEMBER ME ---
        if (isset($_POST['remember'])) {
            setcookie('remember_user', $username, time() + (86400 * 30), "/");  // berlaku 30 hari
        } else {
            // hapus cookie jika tidak dicentang
            setcookie('remember_user', "", time() - 3600, "/");
        }

        header("Location: view/index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url('./assets/img/background.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 250px;
        }

        .login-left h1 {
            font-size: 3rem;
            font-weight: 700;
            justify-content: start;
        }

        .login-left p {
            font-size: 1.1rem;
            margin-top: 15px;
        }

        .login-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            background-color: #f7f8fa;
        }
        .login-right p {
            transform: translateY(-20px) ;
            font-size: 14px;
            opacity: 0.5;
        }

        .login-form {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .login-form h3 {
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-control {
            border-radius: 50px;
            padding: 10px 20px;
        }

        .btn-login {
            border-radius: 50px;
            padding: 10px;
            background-color: #03406C;
            color: white;
            font-weight: 600;
            width: 100%;
            border: none;
        }

        .btn-login:hover {
            background-color: white;
            border: 1px solid #03406C;
            
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .forgot-pass {
            font-size: 0.9rem;
            float: right;
        }

        .logo {
            height: 50px;
            margin-bottom: 30px;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #444;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <!-- LEFT -->
        <div class="login-left">
            <h1>Selamat Datang di Dashboard Admin!</h1>
            <p>Page for modifying the content of the Laboratory Applied Informatics website</p>
        </div>

        <!-- RIGHT -->
        <div class="login-right">
            <form action="" method="POST" class="login-form">
                <img src="./assets/img/logo.png" class="logo" alt="Logo">
                <h3>Sign in to the system</h3>
                <p>Enter your credentials to access the system</p>
                <?php if ($error != '') {
                    echo "<div class='error-msg'>$error</div>";
                } ?>

                <div class="mb-3">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukan Username" required>
                </div>

                <div class="mb-3 password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i id="eyeIcon" class="fa-solid fa-eye-slash"></i>
                    </span>
                </div>


                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-pass">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-login">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>

</html>