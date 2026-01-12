<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Hapus cookie remember me
setcookie('remember_user', "", time() - 3600, "/");

// Redirect ke login
header("Location: ../index.php");
exit;
