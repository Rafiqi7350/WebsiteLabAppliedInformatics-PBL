<?php
include '../model/config_db.php';

$nama       = $_POST['nama'];
$role       = $_POST['role'];
$expertise  = $_POST['expertise'];
$deskripsi  = $_POST['deskripsi'];

$foto_name = $_FILES['foto']['name'];
$tmp       = $_FILES['foto']['tmp_name'];
$folder    = "../assets/img/members/dosen/";

move_uploaded_file($tmp, $folder . $foto_name);

$query = "INSERT INTO member_dosen 
(nama, role, expertise, deskripsi, foto)
VALUES
('$nama', '$role', '$expertise', '$deskripsi', '$foto_name')";

pg_query($conn, $query);

header("Location: ../view/members_dosen.php");