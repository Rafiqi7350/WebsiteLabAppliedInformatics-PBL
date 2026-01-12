<?php
session_start();
include '../model/config_db.php';
include '../model/model_magang_admin.php';

$id = $_GET['id'] ?? null;

deleteMagang($conn, $id);

header("Location: ../view/daftar_magang.php?status=deleted");
exit();
