<?php
include '../model/config_db.php';

$id         = $_POST['id'];
$nama       = $_POST['nama'];
$role       = $_POST['role'];
$expertise  = $_POST['expertise'];
$deskripsi  = $_POST['deskripsi'];
$foto_lama  = $_POST['foto_lama'];

if (!empty($_FILES['foto']['name'])) {
    $foto_baru = $_FILES['foto']['name'];
    $tmp       = $_FILES['foto']['tmp_name'];
    $folder    = "../assets/img/members/dosen/";

    move_uploaded_file($tmp, $folder . $foto_baru);

    if (!empty($foto_lama)) {
        unlink($folder . $foto_lama);
    }

    $query = "UPDATE member_dosen SET 
        nama='$nama',
        role='$role',
        expertise='$expertise',
        deskripsi='$deskripsi',
        foto='$foto_baru'
        WHERE id='$id'";
} else {
    $query = "UPDATE member_dosen SET 
        nama='$nama',
        role='$role',
        expertise='$expertise',
        deskripsi='$deskripsi'
        WHERE id='$id'";
}

pg_query($conn, $query);

header("Location: ../view/members_dosen.php");