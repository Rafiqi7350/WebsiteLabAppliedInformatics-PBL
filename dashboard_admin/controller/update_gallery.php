<?php
include '../model/config_db.php';
include '../model/model_gallery.php';

$id = $_POST['id'];
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];

$gambarBaru = $_FILES['gambar']['name'];

if ($gambarBaru != "") {
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "../assets/img/gallery/" . $gambarBaru);

    updateGallery($conn, $id, $judul, $deskripsi, $gambarBaru);
} else {
    updateGallery($conn, $id, $judul, $deskripsi);
}

header("Location: ../view/gallery.php");
exit();
