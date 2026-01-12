<?php
session_start();
include '../model/config_db.php';
include '../model/model_magang_admin.php';

$id = $_GET['id'] ?? null;

if ($id) {
    rejectMagang($conn, $id);
}

header("Location: ../view/daftar_magang_admin.php?status=rejected");
exit();
