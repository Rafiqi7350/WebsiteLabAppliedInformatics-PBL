<?php
include '../model/config_db.php';
include '../model/model_peminjaman_admin.php';

$id = $_GET['id'];

acceptPeminjaman($conn, $id);

header("Location: ../view/peminjaman.php?status=updated");
exit();
