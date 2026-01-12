<?php
include '../model/config_db.php';
include '../model/model_gallery.php';

$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];

$gambar = $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];

move_uploaded_file($tmp, "../assets/img/gallery/" . $gambar);

addGallery($conn, $judul, $deskripsi, $gambar);

header("Location: ../view/gallery.php");
exit();
