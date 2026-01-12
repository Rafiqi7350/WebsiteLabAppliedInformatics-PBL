<?php

// Ambil semua berita
function getAllNews($conn) {
    $query = "SELECT * FROM news ORDER BY id DESC";
    return pg_query($conn, $query);
}

// Tambah berita
function addNews($conn, $judul, $isi, $gambar, $tanggal) {
    $query = "INSERT INTO news (judul, isi, gambar, tanggal)
              VALUES ($1, $2, $3, $4)";
    $params = array($judul, $isi, $gambar, $tanggal);
    return pg_query_params($conn, $query, $params);
}

// Update berita
function updateNews($conn, $id, $judul, $isi, $gambar, $tanggal) {
    $query = "UPDATE news 
              SET judul=$1, isi=$2, gambar=$3, tanggal=$4 
              WHERE id=$5";
    $params = array($judul, $isi, $gambar, $tanggal, $id);
    return pg_query_params($conn, $query, $params);
}

// Hapus berita
function deleteNews($conn, $id) {
    $query = "DELETE FROM news WHERE id=$1";
    return pg_query_params($conn, $query, array($id));
}

?>
