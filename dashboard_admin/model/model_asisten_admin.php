<?php
// MODEL UNTUK KELOLA PENDAFTARAN ASISTEN OLEH ADMIN

function getPendingAsisten($conn) {
    $query = "SELECT * FROM rekrut_asisten WHERE status = 'pending' ORDER BY id DESC";
    return pg_query($conn, $query);
}

function getAcceptedAsisten($conn) {
    $query = "SELECT * FROM rekrut_asisten WHERE status = 'diterima' ORDER BY id DESC";
    return pg_query($conn, $query);
}

function acceptAsisten($conn, $id){   
    $query = "UPDATE rekrut_asisten SET status = $1 WHERE id = $2";
    $params = ['diterima', $id];
    return pg_query_params($conn, $query, $params);
}

function rejectAsisten($conn, $id){
    $query = "DELETE FROM rekrut_asisten WHERE id = $1";
    return pg_query_params($conn, $query, [$id]);
}

function deleteAsisten($conn, $id) {
    $query = "DELETE FROM rekrut_asisten WHERE id=$1";
    return pg_query_params($conn, $query, [$id]);
}
?>
