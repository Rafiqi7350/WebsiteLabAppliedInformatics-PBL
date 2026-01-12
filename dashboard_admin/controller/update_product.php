<?php
include '../model/config_db.php';
include '../model/model_products.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$link = $_POST['link'];

$gambarBaru = $_FILES['gambar']['name'];

if ($gambarBaru != "") {
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "../assets/img/products/" . $gambarBaru);

    updateProduct($conn, $id, $nama, $deskripsi, $link, $gambarBaru);
} else {
    updateProduct($conn, $id, $nama, $deskripsi,$link );
}

header("Location: ../view/products.php");
exit();
