<?php
include '../model/config_db.php';
include '../model/model_membersMahasiswa.php';

$id = $_GET['id'];

deleteMembersMahasiswa($conn, $id);

header("Location: ../view/members_mahasiswa.php");
exit();
