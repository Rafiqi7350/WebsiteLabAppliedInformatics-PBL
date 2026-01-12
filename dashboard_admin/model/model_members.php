<?php

function getAllMembers($conn) {
    return pg_query($conn, "SELECT * FROM members ORDER BY id ASC");
}

function addMember($conn, $nama, $role, $deskripsi, $foto) {
    $query = "INSERT INTO members (nama, role, deskripsi, foto) VALUES ($1, $2, $3, $4)";
    return pg_query_params($conn, $query, [$nama, $role, $deskripsi, $foto]);
}

function updateMember($conn, $id, $nama, $role, $deskripsi, $foto = null) {

    if ($foto === null) {
        $query = "UPDATE members SET nama=$1, role=$2, deskripsi=$3 WHERE id=$4";
        return pg_query_params($conn, $query, [$nama, $role, $deskripsi, $id]);
    } else {
        $query = "UPDATE members SET nama=$1, role=$2, deskripsi=$3, foto=$4 WHERE id=$5";
        return pg_query_params($conn, $query, [$nama, $role, $deskripsi, $foto, $id]);
    }
}

function deleteMember($conn, $id) {
    $query = "DELETE FROM members WHERE id=$1";
    return pg_query_params($conn, $query, [$id]);
}
