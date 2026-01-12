<?php
include '../model/config_db.php';

$id = $_GET['id'];

$get = pg_query($conn, "SELECT foto FROM member_dosen WHERE id='$id'");
$data = pg_fetch_assoc($get);

$folder = "../assets/img/members/dosen/";
if (!empty($data['foto'])) {
    unlink($folder . $data['foto']);
}

pg_query($conn, "DELETE FROM member_dosen WHERE id='$id'");

header("Location: ../view/members_dosen.php");