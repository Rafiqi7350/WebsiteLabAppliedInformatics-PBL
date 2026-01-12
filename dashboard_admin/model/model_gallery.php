<?php

function getAllGallery($conn) {
    return pg_query($conn, "SELECT * FROM gallery ORDER BY id DESC");
}

function addGallery($conn, $judul, $deskripsi, $gambar) {
    $query = "INSERT INTO gallery (judul, deskripsi, gambar) VALUES ($1, $2, $3)";
    return pg_query_params($conn, $query, [$judul, $deskripsi, $gambar]);
}

function updateGallery($conn, $id, $judul, $deskripsi, $gambar = null) {

    if ($gambar === null) {
        $query = "UPDATE gallery SET judul=$1, deskripsi=$2 WHERE id=$3";
        return pg_query_params($conn, $query, [$judul, $deskripsi, $id]);
    } else {
        $query = "UPDATE gallery SET judul=$1, deskripsi=$2, gambar=$3 WHERE id=$4";
        return pg_query_params($conn, $query, [$judul, $deskripsi, $gambar, $id]);
    }
}

function deleteGallery($conn, $id) {
    return pg_query_params($conn, "DELETE FROM gallery WHERE id=$1", [$id]);
}
