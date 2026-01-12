<?php
include '../model/config_db.php';
include '../model/model_asisten.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = $_GET['id'];

rejectAsisten($conn, $id);

header("Location: ../view/daftar_asisten.php?status=rejected");
exit();
