<?php
include '../model/config_db.php';

$id       = $_POST['id'];
$judul    = $_POST['judul'];
$isi      = $_POST['isi'];
$tanggal  = $_POST['tanggal'];
$link     = $_POST['link']; // ✅ tambahkan

/* CEK ADA GAMBAR ATAU TIDAK */
if (!empty($_FILES['gambar']['name'])) {

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "../assets/img/news/" . $gambar);

    $query = "
        UPDATE news 
        SET judul = $1, isi = $2, gambar = $3, tanggal = $4, link = $5
        WHERE id = $6
    ";

    pg_query_params($conn, $query, [
        $judul,
        $isi,
        $gambar,
        $tanggal,
        $link,
        $id
    ]);

} else {

    // TANPA GANTI GAMBAR
    $query = "
        UPDATE news 
        SET judul = $1, isi = $2, tanggal = $3, link = $4
        WHERE id = $5
    ";

    pg_query_params($conn, $query, [
        $judul,
        $isi,
        $tanggal,
        $link,
        $id
    ]);
}

/* BALIK KE HALAMAN */
header("Location: ../view/news.php");
exit;
