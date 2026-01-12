<?php
include '../model/config_db.php';
include '../model/model_asisten.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = $_GET['id'];

deleteAsisten($conn, $id);

header("Location: ../view/daftar_asisten.php?status=deleted");
exit();
