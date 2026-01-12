<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika user belum login, tapi ada cookie remember me → auto login
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_user'])) {
    $_SESSION['username'] = $_COOKIE['remember_user'];
}

// Jika tetap tidak ada session → paksa login
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>
