<?php
include '../model/config_db.php';
include '../model/model_news.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul   = $_POST['judul'];
    $isi     = $_POST['isi'];
    $tanggal = $_POST['tanggal'];

    // --- Upload Gambar ---
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    // Folder sudah ada → langsung ke path
    $uploadPath = "../assets/img/news/" . $gambar;

    // Pindahkan file
    move_uploaded_file($tmp, $uploadPath);

    // Simpan ke database
    $insert = addNews($conn, $judul, $isi, $gambar, $tanggal);

    if ($insert) {
        header('Location: ../view/news.php');
        exit();
    } else {
        header('Location: ../views/news.php?error=1');
        exit();
    }
}
?>
