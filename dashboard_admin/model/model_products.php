<?php

function getAllProducts($conn) {
    return pg_query($conn, "SELECT * FROM products ORDER BY id ASC");
}

function addProduct($conn, $nama, $deskripsi, $link, $gambar) {
    $query = "INSERT INTO products (nama, deskripsi, link, gambar) VALUES ($1, $2, $3, $4)";
    return pg_query_params($conn, $query, [$nama, $deskripsi, $link, $gambar]);
}


function updateProduct($conn, $id, $nama, $deskripsi, $link, $gambar = null) {
    if ($gambar === null) {
        $query = "UPDATE products SET nama=$1, deskripsi=$2, link=$3 WHERE id=$4";
        return pg_query_params($conn, $query, [$nama, $deskripsi, $link, $id]);
    } else {
        $query = "UPDATE products SET nama=$1, deskripsi=$2, link=$3, gambar=$4 WHERE id=$5";
        return pg_query_params($conn, $query, [$nama, $deskripsi, $link, $gambar, $id]);
    }
}

function deleteProduct($conn, $id) {
    $query = "DELETE FROM products WHERE id=$1";
    return pg_query_params($conn, $query, [$id]);
}
