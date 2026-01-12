<?php
include '../model/config_db.php';
include '../model/model_products.php';

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$link = $_POST['link'];


$gambar = $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];

move_uploaded_file($tmp, "../assets/img/products/" . $gambar);

addProduct($conn, $nama, $deskripsi, $link, $gambar);

header("Location: ../view/products.php");
exit();
